<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

define('DEFAULT_CUTI', 12);

class CutiController extends Controller
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

        $date = Carbon::createFromDate($year, 1, 1);
        $start = $date->copy()->startOfYear();
        $end = $date->copy()->endOfYear();

        $krys = DB::table($db.'_karyawan')
                ->join($db.'_golongan', $db.'_golongan.id', '=', $db.'_karyawan.gol')
                ->select($db.'_karyawan.*', $db.'_golongan.workday', $db.'_golongan.paidleavediv')
                ->where('tgl_mulai', '<=', $end->toDateString())
                ->where('gol', '!=', 'KOM')
                ->where(function ($q) use ($start) {
                    $q->Where('tgl_akhir', '=', null);
                })
                ->orderBy('seq')
                ->orderBy('id_prefix')
                ->get();

        $atts = DB::table($db.'_kehadiran')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_kehadiran.id_kry')
                ->select($db.'_kehadiran.*', $db.'_karyawan.nama')
                ->where('per', 'LIKE', $date->format('Y').'%')
                ->where(function ($q) {
                    $q->where('att', '=', 22)
                        ->orWhere('att', '=', 23)
                        ->orWhere('att', '=', 24);
                })
                ->get();
        // dd($atts);

        $detailcbersama = DB::table($db.'_hrlibur')
                            ->where('desc', 'LIKE', 'Cuti Bersama%')
                            ->where('tgl', '>=', $start->format('Y-m-d'))
                            ->where('tgl', '<=', $end->format('Y-m-d'))
                            ->get();

        foreach($detailcbersama as $d){
            $d->tgl = Carbon::createFromDate($d->tgl);
        }

        $totalcbersama = count($detailcbersama);

        $totalkompensasi = 0;

        $clain = DB::table($db.'_cuti')
                    ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_cuti.id_kry')
                    ->select($db.'_cuti.*', $db.'_karyawan.nama')
                    ->where('per', '=', $year)
                    ->orderBy('tgl')
                    ->get();

        foreach($clain as $c){
            if($c->tipe == 1){
                $c->tipestr = 'CUTI PRIBADI';
            }
            else if($c->tipe == 9){
                $c->tipestr = 'CUTI LAIN';
            }
            else if($c->tipe == 7){
                $c->tipestr = 'TAMBAHAN CUTI';
            }
            else{
                $c->tipestr = '';
            }
        }

        foreach($krys as $kry){
            $masakerja = $end->diffInMonths(Carbon::createFromDate($kry->tgl_mulai));
            $kry->jatahcpribadi = 0;
            $kry->cpribadiused = array();

            if($masakerja < 12){
                $kry->jatahcpribadi = 0;
            }
            else if($masakerja >= 12 && $masakerja < 24){
                if($kry->id == 18){
                    // dd($masakerja);
                }
                switch ($masakerja){
                    case 12:
                        $kry->jatahcpribadi = 1;
                        break;
                    case 13:
                    case 14:
                        $kry->jatahcpribadi = 2;
                        break;
                    case 15:
                        $kry->jatahcpribadi = 3;
                        break;
                    case 16:
                    case 17:
                        $kry->jatahcpribadi = 4;
                        break;
                    case 18:
                        $kry->jatahcpribadi = 5;
                        break;
                    case 19:
                    case 20:
                        $kry->jatahcpribadi = 6;
                        break;
                    case 21:
                        $kry->jatahcpribadi = 7;
                        break;
                    case 22:
                    case 23:
                        $kry->jatahcpribadi = 8;
                        break;
                }
                $kry->jatahcpribadi = floor($kry->jatahcpribadi * (DEFAULT_CUTI - $totalcbersama) / 8);
            }
            else{
                $kry->jatahcpribadi = DEFAULT_CUTI - $totalcbersama;
            }
            $kry->cpribadi = 0;
            $kry->cbersama = 0;
            $kry->ckhusus = 0;
            $kry->sisacuti = 0;
            foreach($atts as $att){
                if($att->id_kry == $kry->id){
                    if($att->att == 22){
                        $kry->cpribadi++;
                        array_push($kry->cpribadiused, array(
                            'tgl' => Carbon::createFromDate($att->tgl),
                            'ket' => 'Belum ada keterangan'
                        ));
                    }
                    else if($att->att == 23){
                        $kry->cbersama++;
                    }
                    else if($att->att == 24){
                        $kry->ckhusus++;
                    }
                }
            }

            // $arr = array();
            // array_push($arr, array(
            //     'key' => 1,
            //     'val' => 1
            // ));
            // array_push($arr, array(
            //     'key' => 2,
            //     'val' => 2
            // ));
            // dd(array_search(3, array_column($arr, 'key')));

            $kry->tcuti = 0;
            $kry->clain = 0;
            foreach($clain as $c){
                if($c->id_kry == $kry->id){
                    if($c->tipe == 1){
                        
                        

                        $found = false;
                        foreach($kry->cpribadiused as $d){
                            // dd($d);
                            if($c->tgl == $d['tgl']->format('Y-m-d')){
                                $i = array_search($c->tgl, array_column($kry->cpribadiused, 'tgl'));
                                ($kry->cpribadiused)[$i]['ket'] = $c->ket;
                                $found = true;
                                break;
                            }
                        }
                        if(!$found){
                            array_push($kry->cpribadiused, array(
                                'tgl' => Carbon::createFromDate($c->tgl),
                                'ket' => $c->ket
                            ));
                            $kry->cpribadi++;
                        }
                    }
                    if($c->tipe == 9){
                        $kry->clain++;
                    }
                    else if($c->tipe == 7){
                        $kry->tcuti++;
                    }
                }
            }

            $kry->sisacuti = $kry->jatahcpribadi - $kry->cpribadi - $kry->clain + $kry->tcuti;

            // if($date->format('Y') < 2023){
            //     $kry->kompensasi = round((($kry->gp + $kry->t2masa + $kry->t2jabatan + $kry->honor) / 21 + $kry->t3kerapian + $kry->t3transport + $kry->t3kehadiran) * $kry->sisacuti, -2);
            // }   
            // else{
                $kry->kompensasi = round((($kry->gp + $kry->t2masa + $kry->t2jabatan + $kry->honor) / $kry->paidleavediv + $kry->t3kerapian + $kry->t3transport + $kry->t3kehadiran) * $kry->sisacuti, -2);
            // }

            $totalkompensasi += $kry->kompensasi;
        }

        $cpribadi = array();
        $cbersama = array();
        $ckhusus = array();
        foreach($atts as $att){
            if($att->att == 22){
                array_push($cpribadi, $att);
            }
            else if($att->att == 23){
                array_push($cbersama, $att);
            }
            else if($att->att == 24){
                array_push($ckhusus, $att);
            }
        }

        // dd($krys);

        return view('pages.cuti', [
            'db' => $db,
            'date' => $date,
            'krys' => $krys,
            'cpribadi' => $cpribadi,
            'cbersama' => $cbersama,
            'ckhusus' => $ckhusus,
            'totalkompensasi' => $totalkompensasi,
            'detailcbersama' => $detailcbersama,
            'clain' => $clain,
            'prev' => $date->copy()->startOfMonth()->subMonth()->format('/Y'),
            'next' => $date->copy()->endOfYear()->addMonth()->format('/Y'),
        ]);
    }

    public function tambah(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'id_kry' => 'required|numeric',
            'tgl' => 'required|date',
            'ket' => 'required',
            'per' => 'required',
            'tipe' => 'required|numeric'
        ]);

        $added = DB::table($db.'_cuti')
                    ->insert([
                        'id_kry' => $r->input('id_kry'),
                        'tgl' => $r->input('tgl'),
                        'ket' => $r->input('ket'),
                        'per' => $r->input('per'),
                        'tipe' => $r->input('tipe'),
                    ]);

        return back()->withStatus(__($added . ' data cuti berhasil ditambah.'));
    }

    public function hapus(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'id' => 'required'
        ]);

        $deleted = DB::table($db.'_cuti')
                    ->where('id', '=', $r->input('id'))
                    ->delete();

        return back()->withStatus(__($deleted . ' data cuti berhasil dihapus.'));
    }
}
