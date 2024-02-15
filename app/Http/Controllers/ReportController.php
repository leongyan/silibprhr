<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

define('DEFAULT_CUTI', 12);

class ReportController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index($year = null)
    {   
        $db = explode('.', Route::currentRouteName())[0];

        $per = 202312;
        $workday = 21;

        $krys = DB::table($db.'_karyawan')
                ->join($db.'_gajian', $db.'_gajian.id_kry', '=', $db.'_karyawan.id')
                ->where('per', $per)
                ->get();

        foreach($krys as $k){
            $k->col1 = $k->param_gp + $k->param_honor + $k->param_t2masa + $k->param_t2jabatan + ($k->param_t3kehadiran + $k->param_t3transport + $k->param_t3kerapian) * $workday;
            $k->col2 = $k->in_gp + $k->in_honor + $k->in_t2masa + $k->in_t2jabatan + $k->in_t3kehadiran + $k->in_t3transport + $k->in_t3kerapian;
        }

        return view('pages.report', [
            'db' => $db,
            'datas' => $krys,
            'per' => $per
        ]);
    }

    public function rekapKenaikanGaji(){
        $db = explode('.', Route::currentRouteName())[0];

        $date = Carbon::now();

        $kry = DB::table($db.'_karyawan')
                ->select('id_prefix', 'id', 'nama', 'sts_kerja')
                ->orderBy('sts_kerja')
                ->orderBy('id_prefix')
                ->get();
        foreach ($kry as $k){
            $k->id_kry = $k->id_prefix.sprintf('%03d', $k->id);
        }

        return view('pages.report.rekap-kenaikan-gaji',[
            'db' => $db,
            'kry' => $kry,
            'date' => $date
        ]);
    }

    public function rekapKenaikanGajiAll(){
        $db = explode('.', Route::currentRouteName())[0];

        $date = Carbon::now();

        return view('pages.report.rekap-kenaikan-gaji-all',[
            'db' => $db,
            'date' => $date
        ]);
    }

    public function rekapKenaikanGajiAllPrint(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $v = $r->validate([
            'year' => 'required'
        ]);

        $k = DB::table($db.'_karyawan')
                ->join($db.'_golongan', $db.'_karyawan.gol', '=', $db.'_golongan.id')
                ->join($db.'_gajian', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                ->where($db.'_gajian.per', '=', $v['year'].'12')
                ->get();
        dd($k);

        $k->id_kry = $k->id_prefix.sprintf('%03d', $k->id);
        
    }

    public function rekapKenaikanGajiPrint(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $v = $r->validate([
            'id' => 'required',
            'year' => 'required'
        ]);

        $k = DB::table($db.'_karyawan')
                ->join($db.'_golongan', $db.'_karyawan.gol', '=', $db.'_golongan.id')
                ->join($db.'_gajian', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                ->where($db.'_karyawan.id', '=', $v['id'])
                ->where($db.'_gajian.per', '=', $v['year'].'12')
                ->first();

        $k->id_kry = $k->id_prefix.sprintf('%03d', $k->id);
        $k->lama_kerja = Carbon::createFromDate($v['year'].'-12-31')->diffInMonths(Carbon::createFromDate($k->tgl_mulai));

        $pcCurrent = array();
        $totalharikerjaCurrent = 0;
        $totalpcCurrent = 0;
        for($i = 1; $i <= 12; $i++){
            $date = Carbon::createFromDate($v['year'], $i, 1);
            $start = $this->getStartDate($db, $date);
            $end = $this->getEndDate($db, $date);

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
                    if($l->tgl->isSameDay($day->tgl) && !str_contains($l->desc, 'Cuti')){
                        $day->lbr = 1;
                        break;
                    }
                }
                array_push($days, $day);
            }

            $harikerja = 0;

            foreach($days as $d){
                if($d->lbr == 0){
                    $harikerja++;
                    $totalharikerjaCurrent++;
                }
            }

            $pc = $k->param_gp + $k->param_honor + $k->param_t2masa + $k->param_t2jabatan + ($k->param_t3kehadiran + $k->param_t3transport + $k->param_t3kerapian) * $harikerja;
            $totalpcCurrent += $pc;

            array_push($pcCurrent, array(
                'per' => $date->format('M Y'),
                'harikerja' => $harikerja,
                'pc' => $pc
            ));
        }

        $pcNext = array();
        $totalharikerjaNext = 0;
        $totalpcNext = 0;
        for($i = 1; $i <= 12; $i++){
            $date = Carbon::createFromDate($v['year'] + 1, $i, 1);
            $start = $this->getStartDate($db, $date);
            $end = $this->getEndDate($db, $date);

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
                    if($l->tgl->isSameDay($day->tgl) && !str_contains($l->desc, 'Cuti')){
                        if($k->workday == 25 && str_contains($l->desc, 'SABTU')){
                            $day->lbr = 0;
                            break;
                        }
                        $day->lbr = 1;
                        break;
                    }

                }
                array_push($days, $day);
            }

            $harikerja = 0;

            foreach($days as $d){
                if($d->lbr == 0){
                    $harikerja++;
                    $totalharikerjaNext++;
                }
            }

            $pc = $k->gp + $k->honor + $k->t2masa + $k->t2jabatan + ($k->t3kehadiran + $k->t3transport + $k->t3kerapian) * $harikerja;
            $totalpcNext += $pc;

            array_push($pcNext, array(
                'per' => $date->format('M Y'),
                'harikerja' => $harikerja,
                'pc' => $pc
            ));
        }

        $pdf = PDF::loadView('pages.report.pdf-rekap-kenaikan-gaji', [
                    'db' => $db,
                    'k' => $k,
                    'currentY' => $v['year'],
                    'nextY' => $v['year'] + 1,
                    'pcCurrent' => $pcCurrent,
                    'pcNext' => $pcNext,
                    'currentTotalHariKerja' => $totalharikerjaCurrent,
                    'currentTotalPc' => $totalpcCurrent,
                    'nextTotalHariKerja' => $totalharikerjaNext,
                    'nextTotalPc' => $totalpcNext
                ])->setPaper('A4', 'portrait');  
                
        $filename = $v['year'] . '-' . $v['year'] + 1 . '-' . strtoupper(str_replace(' ', '-', $k->nama));

        return $pdf->stream($filename);
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
}
