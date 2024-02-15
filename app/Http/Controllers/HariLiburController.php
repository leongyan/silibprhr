<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HariLiburController extends Controller
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

        if(request()->filled('year')){
            $year = request()->year;
        }
        if($year == null){
            $year = Carbon::now()->format('Y');
        }

        $date = Carbon::now()->year($year);

        $lbr = DB::table($db.'_hrlibur')
                ->where('tgl', '>=', $year.'-01-01')
                ->where('tgl', '<=', $year.'-12-31')
                ->orderBy('sts_by', 'desc')
                ->orderBy('tgl', 'asc')
                ->get();

        return view('pages.hrlibur', [
            'db' => $db,
            'lbr' => $lbr,
            'date' => $date
        ]);
    }

    public function editFixed(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'year' => 'required|digits:4|integer|min:2020|max:'.(date('Y') + 1)
        ]);

        $year = $r->input('year');

        // dd($year);

        $sabtu = false;
        $minggu = false;

        if($r->input('sabtu') == 1){
            $sabtu = true;
        }
        if($r->input('minggu') == 1){
            $minggu = true;
        }

        DB::beginTransaction();

        try {
            DB::table($db.('_hrlibur'))
                ->where('tgl', '>=', $year.'-01-01')
                ->where('tgl', '<=', $year.'-12-31')
                ->where('sts_by', '=', 1)
                ->delete();

            $start = Carbon::createFromDate($year, 1, 1)->sub('1 day')->next(Carbon::SATURDAY);
            $end = Carbon::createFromDate($year, 12, 31);

            // dd($start->format('Y-m-d'));

            $satsun = array();

            for ($d = $start; $d->lte($end); $d->addWeek()) {
                if($sabtu){
                    array_push($satsun, array(
                        'tgl' => $d->format('Y-m-d'),
                        'desc' => 'SABTU',
                        'sts_by' => 1
                    ));
                }

                if($minggu){
                    $ds = $d->copy()->next(Carbon::SUNDAY);
                    if(!$ds->gt($end)){
                        array_push($satsun, array(
                            'tgl' => $ds->format('Y-m-d'),
                            'desc' => 'MINGGU',
                            'sts_by' => 1
                        ));
                    }
                }                
            }

            // dd($satsun);

            DB::table($db.'_hrlibur')
                ->insert($satsun);

            DB::commit();
            return back()->withStatus(__('Hari libur berhasil diupdate.'));
        } catch (Exception $e) {
            DB::rollback();
            return back()->withError(__('Hari libur gagal diupdate.'));
        }
    }

    public function tambah(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'tgl' => 'required|date',
            'ket' => 'required'
        ]);

        $added = DB::table($db.'_hrlibur')
                    ->insert([
                        'tgl' => $r->input('tgl'),
                        'desc' => $r->input('ket'),
                        'sts_by' => 2
                    ]);

        return back()->withStatus(__($added . ' hari libur berhasil ditambah.'));
    }

    public function editVar(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'id' => 'required',
            'ket' => 'required'
        ]);

        $updated = DB::table($db.'_hrlibur')
                    ->where('id', '=' ,$r->input('id'))
                    ->where('sts_by', '=', 2)
                    ->update([
                        'desc' => $r->input('ket'),
                    ]);
                    
        return back()->withStatus(__($updated . ' hari libur berhasil diedit.'));
    }

    public function hapus(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'id' => 'required'
        ]);

        $deleted = DB::table($db.'_hrlibur')
                    ->where('id', '=', $r->input('id'))
                    ->delete();

        return back()->withStatus(__($deleted . ' hari libur berhasil dihapus.'));
    }
}
