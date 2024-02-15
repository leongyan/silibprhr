<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class KehadiranController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */

    public function index($year = null, $month = null)
    {   
        $db = explode('.', Route::currentRouteName())[0];

        if(request()->filled('year')){
            $year = request()->year;
        }
        if(request()->filled('month')){
            $month = request()->month;
        }

        if($year == null){
            $year = Carbon::now()->format('Y');
        }
        if($month == null){
            $month = Carbon::now()->format('m');
        }

        $date = Carbon::createFromDate($year, $month, 1);
        $start = $this->getStartDate($db, $date);
        $end = $this->getEndDate($db, $date);

        // dd($end->toDateString());

        $lbr = DB::table($db.'_hrlibur')
                ->where('tgl', '>=', $start->toDateString())
                ->where('tgl', '<=', $end->toDateString())
                ->get();

        foreach($lbr as $l){
            $l->tgl = Carbon::create($l->tgl);
        }

        $days = array();
        for ($d = $start->copy(); $d->lte($end); $d->addDay(1)) {
            $day = (object)array(
                'tgl' => $d->copy(),
                'lbr' => 0
            );
            foreach($lbr as $l){
                if($l->tgl->isSameDay($day->tgl)){
                    $day->lbr = 1;
                    break;
                }
            }
            array_push($days, $day);
        }

        // dd($days);

        // $krys = DB::table($db.'_karyawan')
        //     ->select('id', 'nama')
        //     ->where('sts_kerja', '=', 1)
        //     ->orWhere('sts_kerja', '=', 2)
        //     ->get();

        $atts = DB::table($db.'_kehadiran')
            ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_kehadiran.id_kry')
            ->select($db.'_karyawan.id', $db.'_karyawan.nama', $db.'_kehadiran.*')
            ->where($db.'_kehadiran.per', '=', $end->format('Ym'))
            ->orderBy($db.'_karyawan.id_prefix')
            ->get();

            // ->where('tgl', '>=', $start->toDateString())
            // ->where('tgl', '<=', $end->toDateString())

        $kryAtts = array();

        foreach($atts as $att){
            $i = array_search($att->id_kry, array_column($kryAtts, 'id_kry'));
            if($i === false){
                $kryAtt = array(
                    'id_kry' => $att->id_kry,
                    'nama' => $att->nama,
                    'atts' => array()
                );
                array_push($kryAtts, (object)$kryAtt);
            }

            $i = array_search($att->id_kry, array_column($kryAtts, 'id_kry'));
            ($kryAtts[$i]->atts)[$att->tgl] = $att;
        }

        return view('pages.att', [
            'db' => $db,
            'start' => $start,
            'end' => $end,
            'days' => $days,
            'lbr' => $lbr,
            'atts' => $kryAtts,
            'prev' => $end->copy()->startOfMonth()->subMonth()->format('/Y/m'),
            'next' => $end->copy()->startOfMonth()->addMonth()->format('/Y/m')
        ]);
    }

    public function tambah(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'kryid' => 'required',
            'per' => 'required',
            'tgl' => 'required|date',
            'att' => 'required'
        ]);

        DB::table($db.'_kehadiran')
                ->insert([
                    'id_kry' => $r->input('kryid'),
                    'per' => $r->input('per'),
                    'tgl' => $r->input('tgl'),
                    'att' => $r->input('att'),
                ]);

        return back()->withStatus(__('Data kehadiran berhasil ditambah.'));
    }

    public function edit(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $action = $r->input('submit');

        $validated = $r->validate([
            'id' => 'required',
            'tgl' => 'required|date',
            'att' => 'required'
        ]);

        if($action == 'edit'){
            DB::table($db.'_kehadiran')
                ->where('id', '=', $r->input('id'))
                ->update([
                    'att' => $r->input('att')
                ]);
        }
        else if($action == 'delete'){
            DB::table($db.'_kehadiran')
                ->where('id', '=', $r->input('id'))
                ->delete();
        }

        return back()->withStatus(__('Data kehadiran berhasil diedit.'));
    }

    public function import(Request $r){

        // bugs
        // - komp lain buka csv nya harus pake import text karena base nya semicolon

        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'tgl' => 'required|date',
            'csv' => 'required|file|mimes:csv,txt'
        ]);

        $date = Carbon::create($r->input('tgl'));
        $file = $r->file('csv');

        $delim = $this->detectDelimiter($file);

        $start = $this->getStartDate($db, $date);
        $end = $this->getEndDate($db, $date);

        $per = $end->format('Ym');

        DB::beginTransaction();

        try {
            DB::table($db.'_kehadiran')
                ->where('tgl', '>=', $start->toDateString())
                ->where('tgl', '<=', $end->toDateString())
                ->delete();

            if (($handle = fopen($file, "r")) !== FALSE) {
                $data = fgetcsv($handle, 1000, $delim);
                $dates = array();
                for ($i = 2; $i < count($data); $i++) { 
                    array_push($dates, Carbon::createFromFormat('Ymd', $data[$i]));
                }
                $atts = array();
                while (($data = fgetcsv($handle, 1000, $delim)) !== FALSE) {
                    $idKry = $data[0];
                    for ($i = 2; $i < count($dates) + 2; $i++) {
                        if(!empty($data[$i])){
                            switch ($data[$i]) {
                                case '.': //hadir
                                    $att = 11;
                                    break;
                                case 'w': // wfh
                                    $att = 12;
                                    break;
                                case 'd': //dinas
                                    $att = 13;
                                    break;
                                case 's+': //sakit dengan surat dokter
                                    $att = 21;
                                    break;
                                case 's': //sakit dengan surat dokter
                                    $att = 21;
                                    break;
                                case 'c': //cuti pribadi
                                    $att = 22;
                                    break;
                                case 'b': //cuti bersama
                                    $att = 23;
                                    break;
                                case 'k': //cuti khusus
                                    $att = 24;
                                    break;
                                case 's-': //sakit tanpa surat dokter
                                    $att = 31;
                                    break;
                                case 'i': //izin
                                    $att = 32;
                                    break;
                                case 'a': //alpa
                                    $att = 33;
                                    break;
                                default:
									$att = null;
                                    break;
                            }
                            if($att != null){
                                array_push($atts, array(
                                    'id_kry' => $idKry,
                                    'per' => $per,
                                    'tgl' => $dates[$i - 2]->toDateString(),
                                    'att' => $att
                                ));
                            }
                        }
                    }
                }
            }

            DB::table($db.'_kehadiran')->insert($atts);

            DB::commit();

            return back()->withStatus(__('Data kehadiran berhasil diimport.'));

        } catch (Exception $e) {
            DB::rollback();
            return back()->withError(__('Hari libur gagal diupdate.'));
        }
    }

    public function exportCSV(Request $r){
        $db = explode('.', Route::currentRouteName())[0];
        $date = Carbon::create($r->input('tgl'));

        $start = $this->getStartDate($db, $date);
        $end = $this->getEndDate($db, $date);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=".$db.$end->format('-Y-m').".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $krys = DB::table($db.'_karyawan')
                ->select('id', 'nama')
                ->where('tgl_mulai', '<=', $end->toDateString())
                ->where(function ($q) use ($start) {
                    $q->where('tgl_akhir', '>=', $start->toDateString())
                        ->orWhere('tgl_akhir', '=', null);
                })
                ->where('jabatan', 'not like', '%komisaris%')
                ->orderBy('id_prefix')
                ->get();

        // dd($krys);

        $columns = array('id kry', 'nama');

        for ($d = $start->copy(); $d->lte($end); $d->addDay(1)) {
            array_push($columns, $d->format('Ymd'));
        }

        $callback = function() use ($columns, $krys)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');

            foreach($krys as $k) {
                fputcsv($file, array($k->id, $k->nama), ';');
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function getStartDate($db, $date){
        if($db == 'sgn'){
            $start = $date->copy()->startOfMonth()->startOfDay();
        }
        else if($db == 'scb'){
            $start = $date->copy()->day(25)->subMonth()->startOfDay();
        }

        return $start;
    }

    private function getEndDate($db, $date){
        if($db == 'sgn'){
            $end = $date->copy()->endOfMonth()->endOfDay();
        }
        else if($db == 'scb'){
            $end = $date->copy()->day(24)->endOfDay();
        }

        return $end;
    }

    public function detectDelimiter($csvFile)
    {
        $delimiters = [";" => 0, "," => 0, "\t" => 0, "|" => 0];

        $handle = fopen($csvFile, "r");
        $firstLine = fgets($handle);
        fclose($handle); 
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }
}
