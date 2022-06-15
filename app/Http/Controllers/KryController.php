<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KryController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index()
    {   
        $db = explode('.', Route::currentRouteName())[0];

        $kry = DB::table($db.'_karyawan')
                ->select('id_prefix', 'id', 'nama', 'sts_kerja')
                ->orderBy('sts_kerja')
                ->orderBy('id_prefix')
                ->get();

        foreach ($kry as $k){
            $k->id_kry = $k->id_prefix.sprintf('%03d', $k->id);
        }

        // dd($kry);

        return view('pages.kry', [
            'db' => $db,
            'kry' => $kry
        ]);
    }

    public function index2(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $pageAction = $r->input('action');
        $sID = $r->input('id_kry');

        $kry = DB::table($db.'_karyawan')
                ->select('id_prefix', 'id', 'nama', 'sts_kerja')
                ->orderBy('sts_kerja')
                ->orderBy('id_prefix')
                ->get();

        $selected = array();
        foreach ($kry as $k){
            $k->id_kry = $k->id_prefix.sprintf('%03d', $k->id);
        }

        $action = 1;
        if($pageAction == 'edit'){
            $action = 3;
        }
        else if($pageAction == 'display'){
            $action = 2;
        }

        $s = DB::table($db.'_karyawan')
                ->where('id', '=', $sID)
                ->first();
        $s->id_kry = $s->id_prefix.sprintf('%03d', $s->id);

        return view('pages.kry', [
            'db' => $db,
            'kry' => $kry,
            'action' => $action,
            'selected' => $s
        ]);
    }

    public function detail($id){
        $db = explode('.', Route::currentRouteName())[0];

        $k = DB::table($db.'_karyawan')
                ->where('id', '=', $id)
                ->first();

        $k->id_kry = $k->id_prefix.sprintf('%03d', $k->id);

        return json_encode($k);
    }

    public function tambah(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        $validated = $r->validate([
            'nama' => 'required',
            'jkel' => 'required',
            'nik' => 'required|digits:16',
            'stspajak' => 'required',
            'tanggungan' => 'required',
            'bpjskes' => 'required',
            'bpjstk' => 'required'
        ]);

        if($r->input('npwp') == null){
            $npwp = '000000000000000';
        }
        else{
            $npwp = $r->input('npwp');
        }

        if($db == 'sgn'){
            $idPref = '101';
        }
        else if($db == 'scb'){
            $idPref = '102';
        }
        
        $idPref .= Carbon::now()->format('ym');

        DB::table($db.'_karyawan')->insert([
            'id_prefix' => $idPref,
            'nama' => $r->input('nama'),
            'jenis_kelamin' => $r->input('jkel'),
            'tempat_lahir' => $r->input('tmptlahir'),
            'tgl_lahir' => $r->input('tgllahir'),
            'alamat' => $r->input('alamat'),
            'no_hp' => $r->input('nohp'),
            'nik' => $r->input('nik'),
            'rek_bank' => $r->input('bank'),
            'rek_no' => $r->input('rek'),
            'rek_nama' => $r->input('anrek'),
            'npwp' => $npwp,
            'pph_sts' => $r->input('stspajak'),
            'pph_tg' => $r->input('tanggungan'),
            'bpjs_kes' => $r->input('bpjskes'),
            'bpjs_tk' => $r->input('bpjstk'),
            'tgl_mulai' => $r->input('tglmulai'),
            'tgl_akhir' => $r->input('tglakhir'),
            'sts_kerja' => $r->input('stskerja'),
            'gp' => $r->input('gp') ?? 0,
            't2masa' => $r->input('t2masa') ?? 0,
            't2jabatan' => $r->input('t2jabatan') ?? 0,
            't3kerapian' => $r->input('t3kerapian') ?? 0,
            't3transport' => $r->input('t3transport') ?? 0,
            't3kehadiran' => $r->input('t3kehadiran') ?? 0,
            'honor' => $r->input('honor') ?? 0,
            'base_bpjskes' => $r->input('basebpjskes') ?? 0,
            'base_bpjstk' => $r->input('basebpjstk') ?? 0,
        ]);

        return back()->withStatus(__('Data karyawan berhasil ditambah.'));
    }

    public function edit(Request $r){
        $db = explode('.', Route::currentRouteName())[0];

        // dd($r->input());

        $validated = $r->validate([
            'idKry' => 'required',
            'nama' => 'required',
            'jkel' => 'required',
            'nik' => 'required|digits:16',
            'stspajak' => 'required',
            'tanggungan' => 'required',
            'bpjskes' => 'required',
            'bpjstk' => 'required'
        ]);

        if($r->input('npwp') == null){
            $npwp = '000000000000000';
        }
        else{
            $npwp = $r->input('npwp');
        }

        DB::table($db.'_karyawan')
            ->where('id', '=', $r->input('id'))
            ->update([
                'nama' => $r->input('nama'),
                'jenis_kelamin' => $r->input('jkel'),
                'tempat_lahir' => $r->input('tmptlahir'),
                'tgl_lahir' => $r->input('tgllahir'),
                'alamat' => $r->input('alamat'),
                'no_hp' => $r->input('nohp'),
                'nik' => $r->input('nik'),
                'rek_bank' => $r->input('bank'),
                'rek_no' => $r->input('rek'),
                'rek_nama' => $r->input('anrek'),
                'npwp' => $npwp,
                'pph_sts' => $r->input('stspajak'),
                'pph_tg' => $r->input('tanggungan'),
                'bpjs_kes' => $r->input('bpjskes'),
                'bpjs_tk' => $r->input('bpjstk'),
                'tgl_mulai' => $r->input('tglmulai'),
                'tgl_akhir' => $r->input('tglakhir'),
                'sts_kerja' => $r->input('stskerja'),
                'gp' => $r->input('gp') ?? 0,
                't2masa' => $r->input('t2masa') ?? 0,
                't2jabatan' => $r->input('t2jabatan') ?? 0,
                't3kerapian' => $r->input('t3kerapian') ?? 0,
                't3transport' => $r->input('t3transport') ?? 0,
                't3kehadiran' => $r->input('t3kehadiran') ?? 0,
                'honor' => $r->input('honor') ?? 0,
                'base_bpjskes' => $r->input('basebpjskes') ?? 0,
                'base_bpjstk' => $r->input('basebpjstk') ?? 0
        ]);

        return back()->withStatus(__('Data karyawan berhasil diedit.'));
    }

    // public function hapus(Request $r){
    //     $db = explode('.', Route::currentRouteName())[0];

    //     // dd($r->input());

    //     $validated = $r->validate([
    //         'idKry' => 'required'
    //     ]);

    //     DB::table($db.'_karyawan')
    //         ->where('id', '=', $r->input('id'))
    //         ->delete();
    //     ]);

    //     return back()->withStatus(__('Data karyawan berhasil diedit.'));
    // }
}
