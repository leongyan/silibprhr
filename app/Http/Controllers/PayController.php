<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Response;

define('BPJSKES_LIMIT', 12000000);
define('BPJSJP_LIMIT', 8939700);
define('BPJSKES_PRS', 0.04);
define('BPJSKES_KRY', 0.01);
define('BPJSJHT_PRS', 0.037);
define('BPJSJHT_KRY', 0.02);
define('BPJSJKK', 0.0024);
define('BPJSJKM', 0.003);
define('BPJSJKP', 0);
define('BPJSJP_PRS', 0.02);
define('BPJSJP_KRY', 0.01);
// define('BPJSJP_PRS', 0);
// define('BPJSJP_KRY', 0);
define('PTKP_TK', 54000000);
define('PTKP_K', 58500000);
define('MAX_B_JABATAN', 6000000);
define('PTKP_TANGGUNGAN', 4500000);
define('PPH_LIMIT_1_2021', 50000000);
define('PPH_LIMIT_2_2021', 250000000);
define('PPH_LIMIT_3_2021', 500000000);
define('PPH_LIMIT_1_2022', 60000000);
define('PPH_LIMIT_2_2022', 250000000);
define('PPH_LIMIT_3_2022', 500000000);
define('MAX_CUTI', 12);
define('MAX_CUTI_PRIBADI', 8);
define('AVG_WORK_DAYS', 21);
define('PPH_TER2024', 0);


class PayController extends Controller
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

        $thrs = DB::table($db.'_thr')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_thr.id_kry')
                ->select($db.'_karyawan.nama', $db.'_karyawan.tgl_mulai', $db.'_thr.*')
                ->where('per', '=', $date->format('Ym'))
                ->orderBy('id_prefix', 'asc')
                ->orderBy('id_kry', 'asc')
                ->get();

        $totalTHR = 0;
        foreach($thrs as $t){
            $t->tgl_mulai = Carbon::create($t->tgl_mulai);
            $t->tgl_hr = Carbon::create($t->tgl_hr);
            $totalTHR += $t->thr;
        }

        $pcs = DB::table($db.'_gajian')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                ->select($db.'_karyawan.*', $db.'_gajian.*')
                ->where('per', '=', $date->format('Ym'))
                ->orderBy('id_prefix', 'asc')
                ->orderBy('id_kry', 'asc')
                ->get();

        $totalGaji = 0;
        $totalBPJSKes = 0;
        $totalBPJSTK = 0;
        $totalPajak = 0;
		$totalPajak12 = 0;
        $totalPajakPaid = 0;
        $totalTHP = 0;
        $totalTF1 = 0;
        $totalTF2 = 0;
        $totalBPJSTKPrs = 0;
        $totalBPJSTKKry = 0;
        $totalPendBrutoPajak = 0;
		$totalPendBrutoPajak12 = 0;
        $totalBPJSKesPrs = 0;
        $totalBPJSKesKry = 0;
        $totalBPJSJHTPrs = 0;
        $totalBPJSJHTKry = 0;
        $totalBPJSJKK = 0;
        $totalBPJSJKM = 0;
        $totalBPJSJPPrs = 0;
        $totalBPJSJPKry = 0;
        foreach($pcs as $p){
            foreach($thrs as $t){
                if($p->id_kry == $t->id_kry){
                    $p->thr = $t->thr;
                }
            }

            $p->gaji_total = $p->in_gp + $p->in_honor + $p->in_t2jabatan + $p->in_t2masa + $p->in_t3kerapian + $p->in_t3transport + $p->in_t3kehadiran + $p->in_lain - $p->out_lain;

            $p->bpjskes_total = $p->in_bpjskes + $p->out_bpjskes;
            $p->bpjstk_total = $p->in_bpjsjht + $p->out_bpjsjht + $p->in_bpjsjkk + $p->in_bpjsjkm + $p->in_bpjsjkp + $p->in_bpjsjp + $p->out_bpjsjp;

            $p->thp = $p->gaji_total - $p->out_bpjskes - $p->out_bpjsjht - $p->out_bpjsjp;

            $p->tf1 = $p->in_gp + $p->in_honor + $p->in_t2jabatan + $p->in_t2masa - $p->out_lain;
            $p->tf2 = $p->in_t3kerapian + $p->in_t3transport + $p->in_t3kehadiran + $p->in_lain - $p->out_bpjskes - $p->out_bpjsjht - $p->out_bpjsjp;
            if($p->tf2 < 0){
                $p->tf1 += $p->tf2;
                $p->tf2 = 0;
            }

            $totalGaji += $p->gaji_total;
            $totalBPJSKes += $p->bpjskes_total;
            $totalBPJSTK += $p->bpjstk_total;
            $totalTHP += $p->thp;
            $totalTF1 += $p->tf1;
            $totalTF2 += $p->tf2;
            $totalPendBrutoPajak += $p->gaji_total + $p->in_bpjskes + $p->in_bpjsjkk + $p->in_bpjsjkm;
            $totalBPJSKesPrs += $p->in_bpjskes;
            $totalBPJSKesKry += $p->out_bpjskes;
            $totalBPJSJHTPrs += $p->in_bpjsjht;
            $totalBPJSJHTKry += $p->out_bpjsjht;
            $totalBPJSJKK += $p->in_bpjsjkk;
            $totalBPJSJKM += $p->in_bpjsjkm;
            $totalBPJSJPPrs += $p->in_bpjsjp;
            $totalBPJSJPKry += $p->out_bpjsjp;

            if($date->format('m') == 12){
                $ypcs = DB::table($db.'_gajian')
                    ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                    ->select($db.'_karyawan.*', $db.'_gajian.*')
                    ->where('per', '>=', $date->format('Y01'))
                    ->where('per', '<=', $date->format('Y12'))
                    ->where('id_kry', $p->id_kry)
                    ->get();

                $thrs = DB::table($db.'_thr')
                    ->where('per', '>=', $date->format('Y01'))
                    ->where('per', '<=', $date->format('Y12'))
                    ->where('id_kry', $p->id_kry)
                    ->get();

                $p->pajak = $this->getPajak12($ypcs, $thrs);
				$totalPendBrutoPajak12 += $p->pajak->bruto12;
            }
            else{
                if($year >= 2024 && PPH_TER2024 == 1){
                    $p->pajak = $this->getPajak2024($p);
                }
                else{
                    $p->pajak = $this->getPajak($p);
                }
            }

            $totalPajak += $p->pajak->pajak1;
			$totalPajak12 += $p->pajak->pajak12;
			

            $totalPajakPaid += $p->pph21_paid;

            $totalBPJSTKPrs += $p->in_bpjsjkk + $p->in_bpjsjkm + $p->in_bpjsjp + $p->in_bpjsjkp + $p->in_bpjsjht;
            $totalBPJSTKKry += $p->out_bpjsjp + $p->out_bpjsjht;
        }
        
        return view('pages.pay', [
            'db' => $db,
            'date' => $date,
            'prev' => $date->copy()->startOfMonth()->subMonth()->format('/Y/m'),
            'next' => $date->copy()->startOfMonth()->addMonth()->format('/Y/m'),
            'pcs' => $pcs,
            'thrs' => $thrs,
            'total_gaji' => $totalGaji,
            'total_bpjskes' => $totalBPJSKes,
            'total_bpjstk' => $totalBPJSTK,
            'total_pajak' => $totalPajak,
			'total_pajak12' => $totalPajak12,
            'total_pajak_paid' => $totalPajakPaid,
            'total_thr' => $totalTHR,
            'total_thp' => $totalTHP,
            'total_tf1' => $totalTF1,
            'total_tf2' => $totalTF2,
            'total_bpjs_tkprs' => $totalBPJSTKPrs,
            'total_bpjs_tkkry' => $totalBPJSTKKry,
            'total_pendbruto_pajak' => $totalPendBrutoPajak,
			'total_pendbruto_pajak12' => $totalPendBrutoPajak12,
            'total_bpjs_kesprs' => $totalBPJSKesPrs,
            'total_bpjs_keskry' => $totalBPJSKesKry,
            'total_bpjs_jhtprs' => $totalBPJSJHTPrs,
            'total_bpjs_jhtkry' => $totalBPJSJHTKry,
            'total_bpjs_jkk' => $totalBPJSJKK,
            'total_bpjs_jkm' => $totalBPJSJKM,
            'total_bpjs_jpprs' => $totalBPJSJPPrs,
            'total_bpjs_jpkry' => $totalBPJSJPKry
        ]);
    }

    public function hitungGaji(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'per' => 'required|digits:6|integer'
        ]);

        $per = $r->input('per');

        $date = Carbon::createFromDate(substr($per, 0, 4), substr($per, 4, 2), 1);
        $start = $this->getStartDate($db, $date);
        $end = $this->getEndDate($db, $date);

        $krys = DB::table($db.'_karyawan')
                ->join($db.'_golongan', $db.'_golongan.id', '=', $db.'_karyawan.gol')
                ->select($db.'_karyawan.*', $db.'_golongan.workday')
                ->where('tgl_mulai', '<=', $end->toDateString())
                ->where(function ($q) use ($start) {
                    $q->where('tgl_akhir', '>=', $start->toDateString())
                        ->orWhere('tgl_akhir', '=', null);
                })
                ->orderBy('id_prefix')
                ->get();

        $atts = DB::table($db.'_kehadiran')
                ->where('per', '=', $per)
                ->get();

        if(sizeof($atts) == 0){
            return back()->withError(__('Belum ada data absensi.'));
        }

        $attRecs = array();
        foreach($atts as $a){
            if(array_key_exists($a->id_kry, $attRecs)){
                if(Carbon::create($a->tgl)->gt(Carbon::create($attRecs[$a->id_kry]['trial_end'])) ||
                    Carbon::create($a->tgl)->isSameDay(Carbon::create($attRecs[$a->id_kry]['trial_end']))){
                    $attRecs[$a->id_kry]['regular'][$a->att] += 1;
                }
                else{
                    $attRecs[$a->id_kry]['trial'][$a->att] += 1;
                }
            }
            else{
                foreach($krys as $k){
                    if($k->id == $a->id_kry){
                        $trialEnd = Carbon::create($k->tgl_mulai)->addMonth(3);
                        break;
                    }
                }
                $attRecs[$a->id_kry] = array(
                    'regular' => array(
                        11 => 0,
                        12 => 0,
                        13 => 0,
                        21 => 0,
                        22 => 0,
                        23 => 0,
                        24 => 0,
                        31 => 0,
                        32 => 0,
                        33 => 0
                    ),
                    'trial' => array(
                        11 => 0,
                        12 => 0,
                        13 => 0,
                        21 => 0,
                        22 => 0,
                        23 => 0,
                        24 => 0,
                        31 => 0,
                        32 => 0,
                        33 => 0
                    ),
                    'trial_end' => $trialEnd
                );
                if(Carbon::create($a->tgl)->gt($trialEnd) || Carbon::create($a->tgl)->isSameDay($trialEnd)){
                    $attRecs[$a->id_kry]['regular'][$a->att] += 1;
                }
                else{
                    $attRecs[$a->id_kry]['trial'][$a->att] += 1;
                }
            }
        }

        $pcs = array();

        foreach($krys as $k){
            if(array_key_exists($k->id, $attRecs)){
                $k->atts = $attRecs[$k->id];
            }
            else{
                $k->atts = array(
                    'regular' => array(
                        11 => 0,
                        12 => 0,
                        13 => 0,
                        21 => 0,
                        22 => 0,
                        23 => 0,
                        24 => 0,
                        31 => 0,
                        32 => 0,
                        33 => 0
                    ),
                    'trial' => array(
                        11 => 0,
                        12 => 0,
                        13 => 0,
                        21 => 0,
                        22 => 0,
                        23 => 0,
                        24 => 0,
                        31 => 0,
                        32 => 0,
                        33 => 0
                    )
                );
            }

            $pDays = $k->atts['regular'][11] + $k->atts['regular'][12] + $k->atts['regular'][13] + 
                        $k->atts['regular'][21] + $k->atts['regular'][22] + $k->atts['regular'][23] + 
                        $k->atts['regular'][24];
            $pDaysTrial = $k->atts['trial'][11] + $k->atts['trial'][12] + $k->atts['trial'][13] + 
                        $k->atts['trial'][21] + $k->atts['trial'][22] + $k->atts['trial'][23] + 
                        $k->atts['trial'][24];
            $npDays = $k->atts['regular'][31] + $k->atts['regular'][32] + $k->atts['regular'][33] + $k->atts['trial'][31] + $k->atts['trial'][32] + $k->atts['trial'][33];;

            if($k->pph_sts == 1){
                $ptkp = PTKP_K;
            }
            else{
                $ptkp = PTKP_TK;
            }
            $ptkp += PTKP_TANGGUNGAN * $k->pph_tg;

            $haveNPWP = 1;
            if($k->npwp == '000000000000000'){
                $haveNPWP = 0;
            }

            // untuk periode 2023 dan seterusnya, workday pakai parameter dari golongan
            $workdaydiv = 21;
            if(substr($per, 0, 4) >= 2023){
                $workdaydiv = $k->workday;
            }

            // dd($workdaydiv);

            // kalau baru masuk (gajian pertama)   
            if(Carbon::create($k->tgl_mulai)->diffInMonths($end) < 1){
				if($k->gol == 'KOM'){
					$ingp = $k->gp;
					$inhonor = $k->honor;
					$int2jabatan = 0;
					$int2masa = 0;
				}
				else{
					if($pDaysTrial > $workdaydiv){
						$pDaysTrial = $workdaydiv;
					}
					$ingp = round($k->gp * $pDaysTrial / $workdaydiv);
					$inhonor = round($k->honor * $pDaysTrial / $workdaydiv);
					$int2jabatan = round($k->t2jabatan * $pDaysTrial / $workdaydiv);
					$int2masa = round($k->t2masa * $pDaysTrial / $workdaydiv);
				}
            }
            else{
                if($k->tgl_akhir != null){
                    // kalau resign dan tgl resign ada dalam periode
                    $resignDate = Carbon::create($k->tgl_akhir);
                    if($resignDate->gt($start) && $resignDate->lt($end)){
                        $ingp = round($k->gp * $pDays / $workdaydiv);
                        $inhonor =  round($k->honor * $pDays / $workdaydiv);
                        $int2jabatan =  round($k->t2jabatan * $pDays / $workdaydiv);
                        $int2masa =  round($k->t2masa * $pDays / $workdaydiv);
                    }
                }
                else{
                    // per 2023 gp honor jabatan masa dipotong 1/21 atau 1/25 setiap hari tidak hadir
                    if($k->gol == 'KOM'){
                        $ingp = $k->gp;
                        $inhonor = $k->honor;
                        $int2jabatan = $k->t2jabatan;
                        $int2masa = $k->t2masa;
                    }
                    else{
                        $ingp = round($k->gp * ($workdaydiv - $npDays) / $workdaydiv);
                        $inhonor = round($k->honor * ($workdaydiv - $npDays) / $workdaydiv);
                        $int2jabatan = round($k->t2jabatan * ($workdaydiv - $npDays) / $workdaydiv);
                        $int2masa = round($k->t2masa * ($workdaydiv - $npDays) / $workdaydiv);
                    }
                }
            }

            $pc = array(
                'id_kry' => $k->id,
                'per' => $per,
                'in_gp' => $ingp,
                'in_honor' => $inhonor,
                'in_t2jabatan' => $int2jabatan,
                'in_t2masa' => $int2masa,
                'in_t3kerapian' => $k->t3kerapian * $pDays,
                'in_t3transport' => $k->t3transport * $pDays,
                'in_t3kehadiran' => $k->t3kehadiran * $pDays,
                'pph21_npwp' => $haveNPWP,
                'pph21_ptkp' => $ptkp,
				'param_gp' => $k->gp,
                'param_honor' => $k->honor,
                'param_t2jabatan' => $k->t2jabatan,
                'param_t2masa' => $k->t2masa,
                'param_t3kerapian' => $k->t3kerapian,
                'param_t3transport' => $k->t3transport,
                'param_t3kehadiran' => $k->t3kehadiran,
                'param_bpjskes' => $k->bpjs_kes,
                'param_bpjstk' => $k->bpjs_tk,
                'param_basebpjskes' => $k->base_bpjskes,
                'param_basebpjstk' => $k->base_bpjstk,
                'param_gol' => $k->gol
            );

            array_push($pcs, $pc);
        }

        DB::beginTransaction();

        try {
            DB::table($db.'_gajian')
                ->where('per', '=', $per)
                ->delete();

            DB::table($db.'_gajian')
                ->insert($pcs);

            DB::commit();

            return back()->withStatus(__('Gajian berhasil dihitung.'));
        } catch (Exception $e) {
            DB::rollback();
            return back()->withError(__('Gajian gagal dihitung.'));
        }
    }

    public function hitungBPJS(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'per' => 'required|digits:6|integer'
        ]);

        $per = $r->input('per');

        $pcs = DB::table($db.'_gajian')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                ->select($db.'_karyawan.bpjs_kes', $db.'_karyawan.bpjs_tk',
                 $db.'_karyawan.base_bpjskes', $db.'_karyawan.base_bpjstk', $db.'_gajian.*')
                ->where('per', '=', $per)
                ->get();

        if(sizeof($pcs) == 0){
            return back()->withError(__('Belum ada data gaji.'));
        }

        foreach($pcs as $p){
            $b = array(
                'in_bpjskes' => 0,
                'out_bpjskes' => 0,
                'in_bpjsjht' => 0,
                'out_bpjsjht' => 0,
                'in_bpjsjkk' => 0,
                'in_bpjsjkm' => 0,
                'in_bpjsjkp' => 0,
                'in_bpjsjp' => 0,
                'out_bpjsjp' => 0,
            );


            $dasarBPJSKes = $p->base_bpjskes;
            $dasarBPJSTK = $p->base_bpjstk;

            if($p->bpjs_kes == 1){
                if($dasarBPJSKes > BPJSKES_LIMIT){
                    $dasarBPJSKes = BPJSKES_LIMIT;
                }
                $b['in_bpjskes'] = round(BPJSKES_PRS * $dasarBPJSKes);
                $b['out_bpjskes'] = round(BPJSKES_KRY * $dasarBPJSKes);
            }

            if($p->bpjs_tk == 1){
                $dasarBPJSJP = $dasarBPJSTK;
                if($dasarBPJSJP > BPJSJP_LIMIT){
                    $dasarBPJSJP = BPJSJP_LIMIT;
                }
                
                $b['in_bpjsjht'] = round(BPJSJHT_PRS * $dasarBPJSTK);
                $b['out_bpjsjht'] = round(BPJSJHT_KRY * $dasarBPJSTK);
                $b['in_bpjsjkk'] = round(BPJSJKK * $dasarBPJSTK);
                $b['in_bpjsjkm'] = round(BPJSJKM * $dasarBPJSTK);
                $b['in_bpjsjkp'] = round(BPJSJKP * $dasarBPJSTK);
                $b['in_bpjsjp'] = round(BPJSJP_PRS * $dasarBPJSJP);
                $b['out_bpjsjp'] = round(BPJSJP_KRY * $dasarBPJSJP);
            }

            $p->bpjs = $b;
        }

        DB::beginTransaction();

        try {
            foreach($pcs as $p){
                DB::table($db.'_gajian')
                    ->where('id', $p->id)
                    ->update($p->bpjs);
            }

            DB::commit();

            return back()->withStatus(__('BPJS berhasil dihitung.'));
        } catch (Exception $e) {
            DB::rollback();
            return back()->withError(__('BPJS gagal dihitung.'));
        }
    }

    public function simpanPajak(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'per' => 'required|digits:6|integer'
        ]);

        $per = $r->input('per');

        $date = Carbon::createFromDate(substr($per, 0, 4), substr($per, 4, 2), 1);

        $pcs = DB::table($db.'_gajian')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                ->select($db.'_karyawan.*', $db.'_gajian.*')
                ->where('per', '=', $date->format('Ym'))
                ->get();

        $thrs = DB::table($db.'_thr')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_thr.id_kry')
                ->select($db.'_karyawan.nama', $db.'_karyawan.tgl_mulai', $db.'_thr.*')
                ->where('per', '=', $date->format('Ym'))
                ->get();

        foreach($pcs as $p){
            foreach($thrs as $t){
                if($p->id_kry == $t->id_kry){
                    $p->thr = $t->thr;
                }
            }
            if($date->format('m') == 12){
                $ypcs = DB::table($db.'_gajian')
                    ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                    ->select($db.'_karyawan.*', $db.'_gajian.*')
                    ->where('per', '>=', $date->format('Y01'))
                    ->where('per', '<=', $date->format('Y12'))
                    ->where('id_kry', $p->id_kry)
                    ->get();

                $thrs = DB::table($db.'_thr')
                    ->where('per', '>=', $date->format('Y01'))
                    ->where('per', '<=', $date->format('Y12'))
                    ->where('id_kry', $p->id_kry)
                    ->get();

                $p->pajak = $this->getPajak12($ypcs, $thrs);
            }
            else{
                if($date->format('Y') >= 2024 && PPH_TER2024 == 1){
                    $p->pajak = $this->getPajak2024($p);
                }
                else{
                    $p->pajak = $this->getPajak($p);
                }
            }
        }

        DB::beginTransaction();

        try {
            foreach($pcs as $p){
                DB::table($db.'_gajian')
                    ->where('id', $p->id)
                    ->update([
                        'pph21_paid' => $p->pajak->pajak1]
                    );
            }

            DB::commit();

            return back()->withStatus(__('Pajak berhasil disimpan.'));
        } catch (Exception $e) {
            DB::rollback();
            return back()->withError(__('Pajak gagal disimpan..'));
        }
    }

    public function hitungTHR(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'tglhr' => 'required|date',
            'per' => 'required|date_format:Y/m'
        ]);

        $per = str_replace('/', '', $r->input('per'));

        $tglhr = Carbon::create($r->input('tglhr'));

        $krys = DB::table($db.'_karyawan')
                ->join($db.'_golongan', $db.'_golongan.id', '=', $db.'_karyawan.gol')
                ->select($db.'_karyawan.*', $db.'_golongan.workday')
                ->where('tgl_mulai', '<=', $tglhr->toDateString())
                ->where(function ($q) use ($tglhr) {
                    $q->where('tgl_akhir', '>=', $tglhr->toDateString())
                        ->orWhere('tgl_akhir', '=', null);
                })
                ->orderBy('id_prefix')
                ->get();

        $thrs = array();
        foreach($krys as $k){
            $base = $k->gp + $k->honor + $k->t2jabatan + $k->t2masa + $k->workday * ($k->t3kerapian + $k->t3transport + $k->t3kehadiran);

            $tglMulai = Carbon::create($k->tgl_mulai);
            $lamaKerja = $tglMulai->diffInMonths($tglhr);

            if($lamaKerja >= 120){
                $pengali = 2;
            }
            else if($lamaKerja >= 60){
                $pengali = 1.5;
            }
            else if($lamaKerja >= 12){
                $pengali = 1;
            }
            else{
                $pengali = $lamaKerja / 12;
            }

            array_push($thrs, array(
                'id_kry' => $k->id,
                'per' => $per,
                'tgl_hr' => $tglhr->toDateString(),
                'base' => $base,
                'persentase' => round($pengali * 100, 2),
                'lama_kerja' => $lamaKerja,
                'thr' => round($base * $pengali),
            ));
        }

        DB::beginTransaction();

        try {
            DB::table($db.'_thr')
                ->where('per', '=', $per)
                ->delete();

            DB::table($db.'_thr')
                ->insert($thrs);

            DB::commit();

            return back()->withStatus(__('THR berhasil disimpan.'));
        } catch (Exception $e) {
            DB::rollback();
            return back()->withError(__('THR gagal disimpan..'));
        }
    }

    public function editGaji(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'idpc' => 'required',
            'gp' => 'required',
            'honor' => 'required',
            't2jabatan' => 'required',
            't2masa' => 'required',
            't3kerapian' => 'required',
            't3transport' => 'required',
            't3kehadiran' => 'required',
            'inlain' => 'required',
            'outlain' => 'required'
        ]);

        $updated = DB::table($db.'_gajian')
                    ->where('id', '=' ,$r->input('idpc'))
                    ->update([
                        'in_gp' => $r->input('gp'),
                        'in_honor' => $r->input('honor'),
                        'in_t2jabatan' => $r->input('t2jabatan'),
                        'in_t2masa' => $r->input('t2masa'),
                        'in_t3kerapian' => $r->input('t3kerapian'),
                        'in_t3transport' => $r->input('t3transport'),
                        'in_t3kehadiran' => $r->input('t3kehadiran'),
                        'in_lain' => $r->input('inlain'),
                        'out_lain' => $r->input('outlain')
                    ]);
                    
        return back()->withStatus(__($updated . ' data gajian berhasil diedit.'));
    }

    public function editBPJS(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'idpc' => 'required',
            'bpjskesprs' => 'required',
            'bpjskeskry' => 'required',
            'bpjsjhtprs' => 'required',
            'bpjsjhtkry' => 'required',
            'bpjsjkk' => 'required',
            'bpjsjkm' => 'required',
            'bpjsjkp' => 'required',
            'bpjsjpprs' => 'required',
            'bpjsjpkry' => 'required',
        ]);

        $updated = DB::table($db.'_gajian')
                    ->where('id', '=' ,$r->input('idpc'))
                    ->update([
                        'in_bpjskes' => $r->input('bpjskesprs'),
                        'out_bpjskes' => $r->input('bpjskeskry'),
                        'in_bpjsjht' => $r->input('bpjsjhtprs'),
                        'out_bpjsjht' => $r->input('bpjsjhtkry'),
                        'in_bpjsjkk' => $r->input('bpjsjkk'),
                        'in_bpjsjkm' => $r->input('bpjsjkm'),
                        'in_bpjsjkp' => $r->input('bpjsjkp'),
                        'in_bpjsjp' => $r->input('bpjsjpprs'),
                        'out_bpjsjp' => $r->input('bpjsjpkry')
                    ]);
                    
        return back()->withStatus(__($updated . ' data BPJS berhasil diedit.'));
    }

    public function editPajak(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'idpc' => 'required',
            'npwp' => 'required',
            'ptkp' => 'required',
            'paidpph' => 'required',
        ]);

        $updated = DB::table($db.'_gajian')
                    ->where('id', '=' ,$r->input('idpc'))
                    ->update([
                        'pph21_npwp' => $r->input('npwp'),
                        'pph21_ptkp' => $r->input('ptkp'),
                        'pph21_paid' => $r->input('paidpph')
                    ]);
                    
        return back()->withStatus(__($updated . ' data pajak berhasil diedit.'));
    }

    public function getPajakCsv(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'per' => 'required|digits:6|integer'
        ]);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=".$db."-pph21-".$r->input('per').".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Masa Pajak', 'Tahun Pajak', 'Pembetulan', 'NPWP', 'Nama', 'Kode Pajak', 'Jumlah Bruto', 'Jumlah PPh', 'Kode Negara');

        $pcs = DB::table($db.'_gajian')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                ->select($db.'_karyawan.nama', $db.'_karyawan.npwp', $db.'_gajian.*')
                ->where('per', $r->input('per'))
                ->get();

        $thrs = DB::table($db.'_thr')
                ->where('per', $r->input('per'))
                ->get();

        $callback = function() use ($columns, $pcs, $thrs)
        {
            $file = fopen('php://output', 'w');
            // fputcsv($file, $columns, ';');
            fputs($file, implode(';', $columns)."\n");

            foreach($pcs as $p) {
                $masa = ltrim(substr($p->per, 4), '0');
                $tahun = substr($p->per, 0, 4);
                $bruto = $p->in_gp + $p->in_honor + $p->in_t2jabatan + $p->in_t2masa + $p->in_t3kerapian + $p->in_t3transport + $p->in_t3kehadiran + $p->in_bpjskes + $p->in_bpjsjkk + $p->in_bpjsjkm + $p->in_lain - $p->out_lain;

                foreach($thrs as $t){
                    if($t->id_kry == $p->id_kry){
                        $bruto += $t->thr;
                    }
                }

                if($p->pph21_paid > 0){
                    // fputcsv($file, array($masa, $tahun, 0, sprintf('%015d', $p->npwp), $p->nama, '21-100-01', $bruto, $p->pph21_paid), ';');
                    fputs($file, implode(';', array($masa, $tahun, 0, sprintf('%015d', $p->npwp), $p->nama, '21-100-01', $bruto, $p->pph21_paid, ''))."\n");
                }
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function getRekapCsv(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'per' => 'required|digits:4|integer'
        ]);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=".$db."-rekappajak -".$r->input('per').".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('idkry', 'nama', 'totalgaji', 'thr', 'jkes-prs', 'jkk-prs', 'jkm-prs', 'totalbruto', 'jht-kry', 'jp-kry', 'bjabatan', 'totalnet', 'ptkp', 'pkp', 'pph21', 'sudahbayar');

        $pcs = DB::table($db.'_gajian')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                ->select($db.'_karyawan.nama', $db.'_gajian.*')
                ->where('per', 'LIKE', $r->input('per').'%' )
                ->get();

        $thrs = DB::table($db.'_thr')
                ->where('per', 'LIKE', $r->input('per').'%')
                ->get();

        $datas = array();
        foreach($pcs as $p) {
            $i = array_search($p->id_kry, array_column($datas, 'id_kry'));
            if($i === false){
                $datas[$p->id_kry] = array(
                    'id_kry' => $p->id_kry,
                    'nama' => $p->nama,
                    'totalgaji' => 0, 
                    'thr' => 0, 
                    'jkes-prs' => 0, 
                    'jkk-prs' => 0, 
                    'jkm-prs' => 0, 
                    'totalbruto' => 0, 
                    'jht-kry' => 0, 
                    'jp-kry' => 0, 
                    'bjabatan' => 0, 
                    'totalnet' => 0, 
                    'ptkp' => $p->pph21_ptkp, 
                    'pkp' => 0, 
                    'pph21' => 0, 
                    'sudahbayar' => 0
                );
            }
            
            $datas[$p->id_kry]['totalgaji'] += $p->in_gp + $p->in_honor + $p->in_t2jabatan + $p->in_t2masa + $p->in_t3kerapian + $p->in_t3transport + $p->in_t3kehadiran + $p->in_lain - $p->out_lain;
            $datas[$p->id_kry]['jkes-prs'] += $p->in_bpjskes;
            $datas[$p->id_kry]['jkk-prs'] += $p->in_bpjsjkk;
            $datas[$p->id_kry]['jkm-prs'] += $p->in_bpjsjkm;
            $datas[$p->id_kry]['jht-kry'] += $p->out_bpjsjht;
            $datas[$p->id_kry]['jp-kry'] += $p->out_bpjsjp;
            $datas[$p->id_kry]['sudahbayar'] += $p->pph21_paid;
        }

        foreach($datas as &$d){
            foreach($thrs as $t){
                if($t->id_kry == $d['id_kry']){
                    $d['thr'] += $t->thr;
                }
            }

            $d['totalbruto'] = $d['totalgaji'] + $d['jkes-prs'] + $d['jkk-prs'] + $d['jkm-prs'] + $d['thr'];
            $d['bjabatan'] = $d['totalbruto'] * 0.05;
            if($d['bjabatan'] > MAX_B_JABATAN){
                $d['bjabatan'] = MAX_B_JABATAN;
            }
            $d['totalnet'] = $d['totalbruto'] - $d['jht-kry'] - $d['jp-kry'] - $d['bjabatan'];
            $d['pkp'] = $this->floor1000($d['totalnet'] - $d['ptkp']);
            if($d['pkp'] < 0){
                $d['pkp'] = 0;
            }
            $d['pph21'] = $this->calcPajak($d['pkp'], $r->input('per'));
            // dd($d);
        }

        $callback = function() use ($columns, $datas)
        {
            $file = fopen('php://output', 'w');
            // fputcsv($file, $columns, ';');
            fputs($file, implode(';', $columns)."\n");

            foreach($datas as $d){
                fputs($file, implode(';', $d)."\n");
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function getPajakCsvFinal(Request $r){
        $db = explode('.', Route::currentRouteName())[0];
        $idpemotongsgn = 3;
        $idpemotongscb = 3;
        $idpemotong = 0;
        if($db == 'sgn'){
            $idpemotong = $idpemotongsgn;
        }
        else if($db == 'scb'){
            $idpemotong = $idpemotongscb;
        }

        $validated = $r->validate([
            'per' => 'required|digits:4|integer'
        ]);

        $date = Carbon::createFromDate($validated['per'].'-01-01');

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=".$db."-rekappajakfinal -".$r->input('per').".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Masa Pajak', 'Tahun Pajak', 'Pembetulan', 'Nomor Bukti Potong', 'Masa Perolehan', 'Masa Perolehan', 'NPWP', 'NIK', 'Nama', 'Alamat', 'Jenis Kelamin', 'Status PTKP', 'Jumlah Tanggungan', 'Nama Jabatan', 'WP Luar Negeri', 'Kode Negara', 'Kode Pajak', 'Jumlah 1', 'Jumlah 2', 'Jumlah 3', 'Jumlah 4', 'Jumlah 5', 'Jumlah 6', 'Jumlah 7', 'Jumlah 8', 'Jumlah 9', 'Jumlah 10', 'Jumlah 11', 'Jumlah 12', 'Jumlah 13', 'Jumlah 14', 'Jumlah 15', 'Jumlah 16', 'Jumlah 17', 'Jumlah 18', 'Jumlah 19', 'Jumlah 20', 'Status Pindah', 'NPWP Pemotong', 'Nama Pemotong', 'Tanggal Bukti Potong');

        $krys = DB::table($db.'_karyawan')
                ->where('tgl_mulai', '<=', $date->endOfYear()->toDateString())
                ->where(function ($q) use ($date) {
                    $q->where('tgl_akhir', '>=', $date->startOfYear()->toDateString())
                        ->orWhere('tgl_akhir', '=', null);
                })
                ->orderBy('id_prefix')
                ->get();

        $pcs = DB::table($db.'_gajian')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                ->select($db.'_karyawan.nama', $db.'_gajian.*')
                ->where('per', 'LIKE', $r->input('per').'%' )
                ->get();

        $thrs = DB::table($db.'_thr')
                ->where('per', 'LIKE', $r->input('per').'%')
                ->get();

        $i = $krys->search(function($k) use ($idpemotong) {
            return $k->id === $idpemotong;
        });
        $namapemotong = $krys[$i]->nama;
        $npwppemotong = $krys[$i]->npwp;

        $datas = array();
        $counter = 1;
        foreach($krys as $k){
            // dd($k);
            $id = $k->id;
            $i = array_search($id, array_column($datas, 'id_kry'));
            if($i === false){
                $datas[$id] = array(
                    'masapajak' => 12,
                    'tahunpajak' => $date->format('Y'),
                    'pembetulan' => 0, 
                    'nomorbupot' => '1.1-12.'.$date->format('y').'-'.sprintf('%07d', $counter), 
                    'masaperolehan1' => 0, 
                    'masaperolehan2' => 0, 
                    'npwp' => '="'.$k->npwp.'"',
                    'nik' => '="'.$k->nik.'"', 
                    'nama' => $k->nama, 
                    'alamat' => $k->alamat, 
                    'jk' => ($k->jenis_kelamin == 0) ? 'F' : 'M', 
                    'statusptkp' => ($k->pph_sts == 0) ? 'TK' : 'K', 
                    'tanggungan' => $k->pph_tg, 
                    'jabatan' => $k->jabatan, 
                    'wpluarnegeri' => 'N', 
                    'kodenegara' => '',
                    'kodepajak' => '21-100-01',
                    'jumlah1' => 0,
                    'jumlah2' => 0,
                    'jumlah3' => 0,
                    'jumlah4' => 0,
                    'jumlah5' => 0,
                    'jumlah6' => 0,
                    'jumlah7' => 0,
                    'jumlah8' => 0,
                    'jumlah9' => 0,
                    'jumlah10' => 0,
                    'jumlah11' => 0,
                    'jumlah12' => 0,
                    'jumlah13' => 0,
                    'jumlah14' => 0,
                    'jumlah15' => 0,
                    'jumlah16' => 0,
                    'jumlah17' => 0,
                    'jumlah18' => 0,
                    'jumlah19' => 0,
                    'jumlah20' => 0,
                    'statuspindah' => '',
                    'npwppemotong' => $npwppemotong,
                    'namapemotong' => $namapemotong,
                    'tglbupot' => $date->endOfYear()->format('d/m/y')
                );
                $mulaikerja = Carbon::createFromDate($k->tgl_mulai);
                if($mulaikerja->lt($date->startOfYear())){
                    $datas[$id]['masaperolehan1'] = 1;
                }
                else{
                    $datas[$id]['masaperolehan1'] = $mulaikerja->format('n');
                }
                if($k->tgl_akhir == null){
                    $datas[$id]['masaperolehan2'] = 12;
                }
                else{
                    $akhirkerja = Carbon::createFromDate($k->tgl_akhir);
                    if($akhirkerja->gt($date->endOfYear())){
                        $datas[$id]['masaperolehan2'] = 12;
                    }
                    else{
                        $datas[$id]['masaperolehan2'] = $akhirkerja->format('n');
                    }
                }
                $counter++;
            }
        }
        // dd($datas);
        foreach($pcs as $p) {
            $datas[$p->id_kry]['jumlah1'] += $p->in_gp + $p->in_honor + $p->in_t2jabatan + $p->in_t2masa + $p->in_t3kerapian + $p->in_t3transport + $p->in_t3kehadiran + $p->in_lain - $p->out_lain + $p->in_bpjskes + $p->in_bpjsjkk + $p->in_bpjsjkm;
            $datas[$p->id_kry]['jumlah10'] += $p->out_bpjsjp + $p->out_bpjsjht;
            $datas[$p->id_kry]['jumlah15'] = $p->pph21_ptkp;
            $datas[$p->id_kry]['jumlah20'] += $p->pph21_paid;
        }

        foreach($thrs as $t){
            $datas[$t->id_kry]['jumlah7'] += $t->thr;
        }

        // dd($datas);

        foreach($datas as &$d){
            // total pend bruto
            $d['jumlah8'] = $d['jumlah1'] + $d['jumlah7'];

            // total b jabatan
            $d['jumlah9'] = ceil($d['jumlah8'] * 0.05);
            if($d['jumlah9'] > MAX_B_JABATAN){
                $d['jumlah9'] = MAX_B_JABATAN;
            }

            // total pengurang
            $d['jumlah11'] = $d['jumlah9'] + $d['jumlah10'];

            // bruto - pengurang
            $d['jumlah12'] = $d['jumlah8'] - $d['jumlah11'];

            // total pend nett
            $d['jumlah14'] = $d['jumlah12'] - $d['jumlah13'];

            // total pkp setahun
            $d['jumlah16'] = $this->floor1000($d['jumlah14'] - $d['jumlah15']);
            if($d['jumlah16'] < 0){
                $d['jumlah16'] = 0;
            }

            // pph21 setahun
            $d['jumlah17'] = $this->calcPajak($d['jumlah16'], $validated['per']);
            if(str_contains($d['npwp'], '00000000')){
                $d['jumlah17'] = ceil($d['jumlah17'] * 1.2);
            }

            // pph21 kantor lain
            $d['jumlah18'] = 0;

            // pph21 hanya kantor ini
            $d['jumlah19'] = $d['jumlah17'] - $d['jumlah18'];
        }

        $callback = function() use ($columns, $datas)
        {
            $file = fopen('php://output', 'w');
            // fputcsv($file, $columns, ';');
            fputs($file, implode(';', $columns)."\n");

            foreach($datas as $d){
                fputs($file, implode(';', $d)."\n");
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function printSlip(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'id' => 'required'
        ]);

        $p = DB::table($db.'_gajian')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_gajian.id_kry')
                ->select($db.'_karyawan.*', $db.'_gajian.*')
                ->where($db.'_gajian.id', '=', $r->input('id'))
                ->first();

        $date = Carbon::createFromDate(substr($p->per, 0, 4), substr($p->per, 4, 2), 1)->locale('id')->settings(['formatFunction' => 'translatedFormat']);;
        $start = $this->getStartDate($db, $date);
        $end = $this->getEndDate($db, $date);
        $p->date = $end;

        $ls = DB::table($db.'_hrlibur')
                ->where('tgl', '>=', $start->toDateString())
                ->where('tgl', '<=', $end->toDateString())
                ->get();

        // dd($ls);

        $weekdays = 0;
        for ($d = $start->copy(); $d->lte($end); $d->addDay(1)) {
            $isL = false;
            foreach($ls as $l){
                if($l->tgl == $d->toDateString()){
                    $isL = true;
                    break;
                }
            }
            if(!$isL){
                $weekdays++;
            }
        }

        $p->in = $p->in_gp + $p->in_honor + $p->in_t2jabatan + $p->in_t2masa + $p->in_t3kerapian + $p->in_t3transport + $p->in_t3kehadiran + $p->in_bpjskes + $p->in_bpjsjht + $p->in_bpjsjkk + $p->in_bpjsjkm + $p->in_bpjsjkp + $p->in_bpjsjp + $p->in_lain;

        $p->out = $p->in_bpjskes + $p->in_bpjsjht + $p->in_bpjsjkk + $p->in_bpjsjkm + $p->in_bpjsjkp + $p->in_bpjsjp + $p->out_lain + $p->out_bpjskes + $p->out_bpjsjht + $p->out_bpjsjp;

        // dd($p);

        $att = DB::table($db.'_kehadiran')
                ->select(
                    DB::raw('SUM(IF(att = 11, 1, 0)) AS att11'),
                    DB::raw('SUM(IF(att = 12, 1, 0)) AS att12'),
                    DB::raw('SUM(IF(att = 13, 1, 0)) AS att13'),
                    DB::raw('SUM(IF(att = 21, 1, 0)) AS att21'),
                    DB::raw('SUM(IF(att = 22, 1, 0)) AS att22'),
                    DB::raw('SUM(IF(att = 23, 1, 0)) AS att23'),
                    DB::raw('SUM(IF(att = 24, 1, 0)) AS att24'),
                    DB::raw('SUM(IF(att = 31, 1, 0)) AS att31'),
                    DB::raw('SUM(IF(att = 32, 1, 0)) AS att32'),
                    DB::raw('SUM(IF(att = 33, 1, 0)) AS att33'))
                ->where('id_kry', $p->id_kry)
                ->where('per', $p->per)
                ->groupBy('id_kry')
                ->first();

        $cuti = DB::table($db.'_kehadiran')
                ->select(DB::raw('SUM(IF(att = 22, 1, 0)) AS count'))
                ->where('id_kry', $p->id_kry)
                ->where('tgl', '>=', $date->format('Y-01-01'))
                ->where('tgl', '<=', $end->format('Y-m-d'))
                ->first();

        // dd($cuti);

        $cutiB = DB::table($db.'_hrlibur')
                ->where('desc', 'like', '%cuti bersama%')
                ->where('tgl', '>=', $date->format('Y-01-01'))
                ->where('tgl', '<=', $date->format('Y-12-31'))
                ->count();

        $jatah = MAX_CUTI - $cutiB;

        // if($jatah > 8){
        //     $jatah = 8;
        // }

        $tglMulai = Carbon::create($p->tgl_mulai);
        if($tglMulai->diffInMonths($date) < 12){
            $jatah = 0;
        }
        else{
            $per1jan = $tglMulai->diffInMonths($date->copy()->month(1)->startOfMonth());
            if($per1jan < 12){
                $jatah = ceil($per1jan / 12 * $jatah);
            }
        }

        $p->jatah_cuti = $jatah;
        $p->cuti_terpakai = $cuti->count;
        $p->cuti_bersama = $cutiB;
        $p->sisa_cuti = $jatah - $cuti->count;

        $pdf = PDF::loadView('pages.pdf-slip', [
                    'db' => $db,
                    'p' => $p,
                    'att' => $att,
                    'weekdays' => $weekdays
                ])->setPaper('A5', 'landscape');  
                
        $filename = $p->per . '-' . strtoupper(str_replace(' ', '-', $p->nama) . '.pdf');
        return $pdf->stream($filename);
    }

    public function printThr(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'id' => 'required'
        ]);

        $p = DB::table($db.'_thr')
                ->join($db.'_karyawan', $db.'_karyawan.id', '=', $db.'_thr.id_kry')
                ->select($db.'_karyawan.*', $db.'_thr.*')
                ->where($db.'_thr.id', '=', $r->input('id'))
                ->first();

        // dd($p);

        $date = Carbon::createFromDate($p->tgl_hr)->locale('id')->settings(['formatFunction' => 'translatedFormat']);
        $p->date = $date;

        $pdf = PDF::loadView('pages.pdf-thr', [
                    'db' => $db,
                    'p' => $p,
                ])->setPaper('A5', 'landscape');  
                
        $filename = $p->per . '-' . strtoupper(str_replace(' ', '-', $p->nama));
        return $pdf->stream($filename);
    }

    // public function hapus(Request $r){
    //     $db = explode('.', Route::currentRouteName())[0];

    //     $validated = $r->validate([
    //         'id' => 'required'
    //     ]);

    //     $deleted = DB::table($db.'_hrlibur')
    //                 ->where('id', '=', $r->input('id'))
    //                 ->delete();

    //     return back()->withStatus(__($deleted . ' hari libur berhasil dihapus.'));
    // }

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

    private function getPajak($d){
        // $d = select join karyawan dan gajian
        $pajak = (object)array(
            'bruto1' => $d->in_gp + $d->in_honor + $d->in_t2jabatan + $d->in_t2masa + $d->in_t3kerapian + $d->in_t3transport + $d->in_t3kehadiran + $d->in_bpjskes + $d->in_bpjsjkk + $d->in_bpjsjkm + $d->in_lain - $d->out_lain,
            'net12' => 0,
            'ptkp' => 0,
            'pkp' => 0,
            'pajak12' => 0,
            'pajak1' => 0
        );

        $date = Carbon::createFromDate(substr($d->per, 0, 4), substr($d->per, 4, 2), 1);
        $tglMulai = Carbon::create($d->tgl_mulai);

        if($tglMulai->gt($date->copy()->subYear()->month(12)->endOfMonth()->endOfDay())){
            $masaKerja = 13 - $tglMulai->format('n');
        }
        else{
            $masaKerja = 12;
        }

        $bjabatan = $pajak->bruto1 * $masaKerja * 0.05;
        if($bjabatan > MAX_B_JABATAN){
            $bjabatan = MAX_B_JABATAN;
        }

        $pajak->net12 = ($pajak->bruto1 - $d->out_bpjsjht - $d->out_bpjsjp) * $masaKerja - $bjabatan;

        $pajak->ptkp = $d->pph21_ptkp;

        $pajak->pkp = $this->floor1000($pajak->net12 - $pajak->ptkp);
        if($pajak->pkp < 0){
            $pajak->pkp = 0;
        }

        $pajak->pajak12 = $this->calcPajak($pajak->pkp, $date->format('Y'));

        if(!$d->pph21_npwp){
            $pajak->pajak12 = $pajak->pajak12 * 120 / 100;
        }

        $pajak->pajak1 = round($pajak->pajak12 / 12);

        if(array_key_exists('thr', (array)$d)){
            $brutoT = $pajak->bruto1 * $masaKerja + $d->thr;
            $bjabatanT = $brutoT * 0.05;
            if($bjabatanT > MAX_B_JABATAN){
                $bjabatanT = MAX_B_JABATAN;
            }
            $netT = $brutoT - $masaKerja * ($d->out_bpjsjht + $d->out_bpjsjp) - $bjabatanT;
            $pkpT = $this->floor1000($netT - $pajak->ptkp);
            if($pkpT < 0){
                $pkpT = 0;
            }
            $pphT = $this->calcPajak($pkpT, $date->format('Y'));

            if(!$d->pph21_npwp){
                $pphT = $pphT * 120 / 100;
            }

            $pajak->pajak1 = $pajak->pajak1 + $pphT - $pajak->pajak12;
            $pajak->pajak12 = $pphT;
            $pajak->net12 = $netT;
            $pajak->pkp = $pkpT;
        }

        return $pajak;
    }

    private function getPajak2024($d){
        // $d = select join karyawan dan gajian
        $pajak = (object)array(
            'bruto1' => $d->in_gp + $d->in_honor + $d->in_t2jabatan + $d->in_t2masa + $d->in_t3kerapian + $d->in_t3transport + $d->in_t3kehadiran + $d->in_bpjskes + $d->in_bpjsjkk + $d->in_bpjsjkm + $d->in_lain - $d->out_lain,
            'net12' => 0,
            'ptkp' => 0,
            'pkp' => 0,
            'pajak12' => 0,
            'pajak1' => 0
        );

        $date = Carbon::createFromDate(substr($d->per, 0, 4), substr($d->per, 4, 2), 1);
        $tglMulai = Carbon::create($d->tgl_mulai);

        if($tglMulai->gt($date->copy()->subYear()->month(12)->endOfMonth()->endOfDay())){
            $masaKerja = 13 - $tglMulai->format('n');
        }
        else{
            $masaKerja = 12;
        }

        $bjabatan = $pajak->bruto1 * $masaKerja * 0.05;
        if($bjabatan > MAX_B_JABATAN){
            $bjabatan = MAX_B_JABATAN;
        }

        $pajak->net12 = ($pajak->bruto1 - $d->out_bpjsjht - $d->out_bpjsjp) * $masaKerja - $bjabatan;

        $pajak->ptkp = $d->pph21_ptkp;

        $pajak->pkp = $this->floor1000($pajak->net12 - $pajak->ptkp);
        if($pajak->pkp < 0){
            $pajak->pkp = 0;
        }

        $pajak->pajak12 = $this->calcPajak($pajak->pkp, $date->format('Y'));

        if(!$d->pph21_npwp){
            $pajak->pajak12 = $pajak->pajak12 * 120 / 100;
        }

        // perhitungan yg beda cuma pajak1
        // $pajak->pajak1 = round($pajak->pajak12 / 12);

        // perhitungan pajak1 sesuai aturan TER 2024
        $pph_ptkp = DB::table('pph_ptkp')
                        ->where('pph_sts', '=', $d->pph_sts)
                        ->where('pph_tg', '=', $d->pph_tg)
                        ->first();
        $pph_ter_tarif = DB::table('pph_ter_tarif')
                            ->where('id_ter', '=', $pph_ptkp->id_ter)
                            ->where('bruto1_bawah', '<=', $pajak->bruto1)
                            ->where('bruto1_atas', '>=', $pajak->bruto1)
                            ->first();

        $pajak->pajak1 = round($pajak->bruto1 * $pph_ter_tarif->tarif);
        if(!$d->pph21_npwp){
            $pajak->pajak1 = $pajak->pajak1 * 120 / 100;
        }

        if(array_key_exists('thr', (array)$d)){
            $brutoT = $pajak->bruto1 * $masaKerja + $d->thr;
            $bjabatanT = $brutoT * 0.05;
            if($bjabatanT > MAX_B_JABATAN){
                $bjabatanT = MAX_B_JABATAN;
            }
            $netT = $brutoT - $masaKerja * ($d->out_bpjsjht + $d->out_bpjsjp) - $bjabatanT;
            $pkpT = $this->floor1000($netT - $pajak->ptkp);
            if($pkpT < 0){
                $pkpT = 0;
            }
            $pphT = $this->calcPajak($pkpT, $date->format('Y'));

            if(!$d->pph21_npwp){
                $pphT = $pphT * 120 / 100;
            }

            $pajak->bruto1 = $pajak->bruto1 + $d->thr;

            $pph_ter_tarif_thr = DB::table('pph_ter_tarif')
                            ->where('id_ter', '=', $pph_ptkp->id_ter)
                            ->where('bruto1_bawah', '<=', $pajak->bruto1)
                            ->where('bruto1_atas', '>=', $pajak->bruto1)
                            ->first();

            $pajak->pajak1 = $pajak->bruto1 * $pph_ter_tarif_thr->tarif;
            $pajak->pajak12 = $pphT;
            $pajak->net12 = $netT;
            $pajak->pkp = $pkpT;
        }

        return $pajak;
    }

    private function getPajak12($ds, $thrs){
        // $ds = select join karyawan dan gajian, spesifik id_kry per Y01-Y12
        $pajak = (object)array(
            'bruto1' => 0,
            'bruto12' => 0,
            'net12' => 0,
            'ptkp' => 0,
            'pkp' => 0,
            'pajak12' => 0,
            'pajakpaid' => 0,
            'pajak1' => 0
        );

        $date = Carbon::createFromDate(substr($ds[0]->per, 0, 4), 12, 1);
        
        $tglMulai = Carbon::create($ds[0]->tgl_mulai);

        $outJHT12 = 0;
        $outJP12 = 0;
        $haveNPWP = 1;
        foreach($ds as $d){
            $pajak->bruto12 += $d->in_gp + $d->in_honor + $d->in_t2jabatan + $d->in_t2masa + $d->in_t3kerapian + $d->in_t3transport + $d->in_t3kehadiran + $d->in_bpjskes + $d->in_bpjsjkk + $d->in_bpjsjkm + $d->in_lain - $d->out_lain;
            $outJHT12 += $d->out_bpjsjht;
            $outJP12 += $d->out_bpjsjp;

            if($d->per == $date->format('Y12')){
                $pajak->bruto1 = $d->in_gp + $d->in_honor + $d->in_t2jabatan + $d->in_t2masa + $d->in_t3kerapian + $d->in_t3transport + $d->in_t3kehadiran + $d->in_bpjskes + $d->in_bpjsjkk + $d->in_bpjsjkm + $d->in_lain - $d->out_lain;
                $pajak->ptkp = $d->pph21_ptkp;
                $haveNPWP = $d->pph21_npwp;
            }
            else{
                $pajak->pajakpaid += $d->pph21_paid;
            }
        }

        foreach($thrs as $t){
            $pajak->bruto12 += $t->thr;
        }

        $bjabatan = $pajak->bruto12 * 0.05;
        if($bjabatan > MAX_B_JABATAN){
            $bjabatan = MAX_B_JABATAN;
        }

        $pajak->net12 = $pajak->bruto12 - $outJHT12 - $outJP12 - $bjabatan;

        $pajak->pkp = $this->floor1000($pajak->net12 - $pajak->ptkp);
        if($pajak->pkp < 0){
            $pajak->pkp = 0;
        }

        $pajak->pajak12 = $this->calcPajak($pajak->pkp, $date->format('Y'));
        if(!$d->pph21_npwp){
            $pajak->pajak12 = $pajak->pajak12 * 120 / 100;
        }
        $pajak->pajak1 = $pajak->pajak12 - $pajak->pajakpaid;

        // dd($pajak);

        return $pajak;
    }

    private function calcPajak($pkp, $year){
        if($year < 2022){
            $limit1 = PPH_LIMIT_1_2021;
            $limit2 = PPH_LIMIT_2_2021;
            $limit3 = PPH_LIMIT_3_2021;
        }
        else{
            $limit1 = PPH_LIMIT_1_2022;
            $limit2 = PPH_LIMIT_2_2022;
            $limit3 = PPH_LIMIT_3_2022;
        }

        $pph21 = 0;
        $sisapkp = $pkp;
        if($sisapkp > $limit1){
            $pph21 = $pph21 + $limit1 * 0.05;
            $sisapkp = $sisapkp - $limit1;
            if($sisapkp > ($limit2 - $limit1)){
                $pph21 = $pph21 + ($limit2 - $limit1) * 0.15;
                $sisapkp = $sisapkp - ($limit2 - $limit1);
                if($sisapkp > ($limit3 - $limit2)){
                    $pph21 = $pph21 + ($limit3 - $limit2) * 0.25;
                    $sisapkp = $sisapkp - ($limit3 - $limit2);

                    $pph21 = $pph21 + $this->floor1000($sisapkp) * 0.3;
                }
                else{
                    $pph21 = $pph21 + $this->floor1000($sisapkp) * 0.25;
                }
            }
            else{
                $pph21 = $pph21 + $this->floor1000($sisapkp) * 0.15;
            }
        }
        else {
            $pph21 = $pph21 + $this->floor1000($sisapkp) * 0.05;
        }

        return $pph21;
    }

    private function floor1000($n){
        return floor($n / 1000) * 1000;
    }
}
