@extends('layouts.app', ['page' => __($db . ' / Gajian'), 'pageSlug' => $db.'-gajian'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 form-inline">
                            <h4 class="title mr-1">{{ strtoupper($db) }}</h4><h5>Gajian</h5>
                            {{-- <h5 class="title">{{ _('Karyawan') }}</h5> --}}
                        </div>
                        <div class="col-md-6">
                            <div class="float-right form-inline">
                            <a class="btn btn-simple" href="/{{$db}}/gajian{{$prev}}">
                                <span class="tim-icons icon-minimal-left"></span>
                            </a>
                            <form action="gajian">
                                <select class="form-control" name="month">
                                    @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i === (int)$date->format('m') ? "selected" : "" }}>
                                        {{ $date->copy()->day(1)->month($i)->format('M') }}
                                    </option>
                                    @endfor
                                </select>
                                <select class="form-control" name="year">
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ $i === (int)$date->format('Y') ? "selected" : "" }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <button type="submit" class="btn btn-simple">GO</button>
                            </form>
                            <a class="btn btn-simple" href="/{{$db}}/gajian{{$next}}">
                                <span class="tim-icons icon-minimal-right"></span>
                            </a>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="row">
                        <div class="col-md-6">
                            <h4>{{$date->format('F Y')}}</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                <div id="tab-btns" class="btn-group btn-group-toggle nav">
                                    <button class="btn btn-sm btn-default btn-simple active"
                                        data-toggle="pill" data-target="#tab-gaji">GAJI</button>
                                    <button class="btn btn-sm btn-default btn-simple"
                                        data-toggle="pill" data-target="#tab-bpjs">BPJS</button>
                                    @if (sizeof($thrs) > 0)
                                    <button class="btn btn-sm btn-default btn-simple"
                                        data-toggle="pill" data-target="#tab-thr">THR</button>
                                    @endif
                                    <button class="btn btn-sm btn-default btn-simple"
                                        data-toggle="pill" data-target="#tab-pajak">PAJAK</button>
                                    <button class="btn btn-sm btn-default btn-simple"
                                        data-toggle="pill" data-target="#tab-thp">THP</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-gaji">
                                <div class="table-responsive">
                                    <table class="table table-pc">
                                        <thead>
                                            <tr class="text-center">
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">NAMA</th>
                                                <th rowspan="2" class="text-right">POKOK</th>
                                                <th rowspan="2" class="text-right">HONOR</th>
                                                <th colspan="2">TUNJ. TETAP</th>
                                                <th colspan="3">TUNJ. TIDAK TETAP</th>
                                                <th colspan="2">LAIN-LAIN</th>
                                                <th rowspan="2" class="text-right">TOTAL</th>
                                            </tr>
                                            <tr class="text-right">
                                                <th>T. JABATAN</th>
                                                <th>T. MASA</th>
                                                <th>T. KERAPIAN</th>
                                                <th>T. TRANSPORT</th>
                                                <th>T. KEHADIRAN</th>
                                                <th>PENAMBAH</th>
                                                <th>PENGURANG</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(sizeof($pcs) == 0)
                                            <tr>
                                                <td colspan="12" class="text-center">Belum ada data gajian.</td>
                                            </tr>
                                            @else
                                                @foreach($pcs as $p)
                                                <tr class="text-right">
                                                    <td class="text-center">
                                                        <form action="/{{$db}}/karyawan" method="post">
                                                            @csrf
                                                            <input type="hidden" name="action" value="edit">
                                                            <input type="hidden" name="id_kry" value="{{$p->id_kry}}">
                                                            <button type="submit" class="btn btn-default btn-sm">
                                                                {{ $p->id_kry }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td class="text-left">{{ $p->nama }}</td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_gp) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_honor) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_t2jabatan) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_t2masa) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_t3kerapian) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_t3transport) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_t3kehadiran) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_lain) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->out_lain) }}
                                                    </td>
                                                    <td>
                                                        <b>{{ \App\Helpers\AppHelper::instance()->mf($p->gaji_total) }}</b>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-simple btn-edit-pc"
                                                            data-toggle="modal"
                                                            data-target="#editPcModal"
                                                            data-id="{{ $p->id }}"
                                                            data-json="{{ json_encode($p) }}">
                                                            <i class="tim-icons icon-pencil"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <tr class="text-right">
                                                    <td colspan="11"></td>
                                                    <td><b>{{ App\Helpers\AppHelper::instance()->mf($total_gaji) }}</b></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" data-toggle='modal' data-target="#hitungTHRModal" class="btn btn-sm btn-simple">
                                            HITUNG THR
                                        </button>
                                        <form action="/{{$db}}/gajian/hitung/gaji" data-msg="Hitung gajian?" method="post">
                                            @csrf
                                            <input type="hidden" name="per" value="{{ $date->format('Ym') }}">
                                            <button type="submit" class="btn btn-sm">
                                                HITUNG GAJI
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-bpjs">
                                <div class="table-responsive">
                                    <table class="table table-pc">
                                        <thead class="text-center">
                                            <tr>
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">NAMA</th>
                                                <th colspan="3">BPJS KESEHATAN</th>
                                                <th colspan="8">BPJS TENAGA KERJA</th>
                                            </tr>
                                            <tr class="text-right">
                                                <th>PERUSAHAAN</th>
                                                <th>KARYAWAN</th>
                                                <th>TOTAL</th>
                                                <th>JHT PER</th>
                                                <th>JHT KRY</th>
                                                <th>JKK</th>
                                                <th>JKM</th>
                                                <th>JKP</th>
                                                <th>JP PER</th>
                                                <th>JP KRY</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(sizeof($pcs) == 0)
                                            <tr>
                                                <td colspan="13" class="text-center">Belum ada data gajian.</td>
                                            </tr>
                                            @else
                                            @foreach($pcs as $p)
                                                <tr class="text-right">
                                                    <td class="text-center">{{ $p->id_kry }}</td>
                                                    <td class="text-left">{{ $p->nama }}</td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_bpjskes) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->out_bpjskes) }}
                                                    </td>
                                                    <td><b>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->bpjskes_total) }}</b>
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_bpjsjht) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->out_bpjsjht) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_bpjsjkk) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_bpjsjkm) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_bpjsjkp) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_bpjsjp) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->out_bpjsjp) }}
                                                    </td>
                                                    <td>
                                                        <b>{{ \App\Helpers\AppHelper::instance()->mf($p->bpjstk_total) }}</b>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-simple btn-edit-bpjs"
                                                            data-toggle="modal"
                                                            data-target="#editBPJSModal"
                                                            data-id="{{ $p->id }}"
                                                            data-json="{{ json_encode($p) }}">
                                                            <i class="tim-icons icon-pencil"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <tr class="text-right">
                                                    <td colspan="4"></td>
                                                    <td><b>{{ \App\Helpers\AppHelper::instance()->mf($total_bpjskes) }}</b></td>
                                                    <td colspan="7"></td>
                                                    <td><b>{{ \App\Helpers\AppHelper::instance()->mf($total_bpjstk) }}</b></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end">
                                        <form action="/{{$db}}/gajian/hitung/bpjs" data-msg="Hitung BPJS?" method="post">
                                            @csrf
                                            <input type="hidden" name="per" value="{{ $date->format('Ym') }}">
                                            <button type="submit" class="btn btn-sm">
                                                HITUNG
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-thr">
                                <div class="table-responsive">
                                    <table class="table table-pc">
                                        <thead class="text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>NAMA</th>
                                                <th>MULAI KERJA</th>
                                                <th>TGL HARI RAYA</th>
                                                <th>LAMA KERJA (bulan)</th>
                                                <th>PENGALI</th>
                                                <th class="text-right">GAJI 21 HARI</th>
                                                <th class="text-right">THR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(sizeof($thrs) == 0)
                                            <tr>
                                                <td colspan="13" class="text-center">Belum ada data THR.</td>
                                            </tr>
                                            @else
                                            @foreach($thrs as $p)
                                                <tr class="text-right">
                                                    <td class="text-center">{{ $p->id_kry }}</td>
                                                    <td class="text-left">{{ $p->nama }}</td>
                                                    <td class="text-center">{{ $p->tgl_mulai->format('m/Y') }}</td>
                                                    <td class="text-center">{{ $p->tgl_hr->format('d/m/Y') }}</td>
                                                    <td class="text-center">{{ $p->lama_kerja }}</td>
                                                    <td class="text-center">{{ $p->persentase }}%</td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->base) }}
                                                    </td>
                                                    <td>
                                                        <b>{{ \App\Helpers\AppHelper::instance()->mf($p->thr) }}</b>
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="/{{$db}}/gajian/thr/print" target="_blank" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$p->id}}">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-simple btn-slip-thr">
                                                                <i class="tim-icons icon-paper"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <tr class="text-right">
                                                    <td colspan="7"></td>
                                                    <td><b>{{ \App\Helpers\AppHelper::instance()->mf($total_thr) }}</b></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    {{-- <div class="d-flex justify-content-end">
                                        <form action="/{{$db}}/gajian/hitung/bpjs" data-msg="Hitung BPJS?" method="post">
                                            @csrf
                                            <input type="hidden" name="per" value="{{ $date->format('Ym') }}">
                                            <button type="submit" class="btn btn-sm">
                                                HITUNG
                                            </button>
                                        </form>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-pajak">
                                <div class="table-responsive">
                                    <table class="table table-pc">
                                        <thead class="text-center">
                                            <tr class="text-right">
                                                <th class="text-center">ID</th>
                                                <th class="text-center">NAMA</th>
                                                <th>PEND. BRUTO</th>
                                                @if($date->format('m') == 12)
                                                <th>PEND. BRUTO SETAHUN</th>
                                                @endif
                                                <th>PEND. NET SETAHUN</th>
                                                <th>PTKP</th>
                                                <th>PKP</th>
                                                <th class="text-center">NPWP</th>
                                                <th>PPH21 SETAHUN</th>
                                                <th>PPH21 SEBULAN</th>
                                                <th>SUDAH DIBAYAR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(sizeof($pcs) == 0)
                                            <tr>
                                                <td colspan="12" class="text-center">Belum ada data gajian.</td>
                                            </tr>
                                            @else
                                            @foreach($pcs as $p)
                                                <tr class="text-right">
                                                    <td class="text-center">{{ $p->id_kry }}</td>
                                                    <td class="text-left">{{ $p->nama }}</td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->pajak->bruto1) }}
                                                    </td>
                                                    @if($date->format('m') == 12)
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->pajak->bruto12) }}
                                                    </td>
                                                    @endif
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->pajak->net12) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->pajak->ptkp) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->pajak->pkp) }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if($p->pph21_npwp)
                                                        <span class="tim-icons icon-check-2 text-info"></span>
                                                        @else
                                                        <span class="tim-icons icon-simple-remove text-warning"></span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->pajak->pajak12) }}
                                                    </td>
                                                    <td>
                                                        <b>{{ \App\Helpers\AppHelper::instance()->mf($p->pajak->pajak1) }}</b>
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->pph21_paid) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-simple btn-edit-pajak"
                                                            data-toggle="modal"
                                                            data-target="#editPajakModal"
                                                            data-id="{{ $p->id }}"
                                                            data-json="{{ json_encode($p) }}">
                                                            <i class="tim-icons icon-pencil"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <tr class="text-right">
                                                    <td colspan="{{ $date->format('m') == 12 ? 9 : 8 }}"></td>
                                                    <td><b>{{ App\Helpers\AppHelper::instance()->mf($total_pajak) }}</b></td>
                                                    <td>{{ App\Helpers\AppHelper::instance()->mf($total_pajak_paid) }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end">
                                        <form action="/{{$db}}/gajian/pajak/csv" method="POST">
                                            @csrf
                                            <input type="hidden" name="per" value="{{ $date->format('Ym') }}">
                                            <button type="submit" class="btn btn-sm btn-simple">
                                                GET CSV
                                            </button>
                                        </form>
                                        <form action="/{{$db}}/gajian/simpan/pajak" data-msg="Simpan pajak yang sudah dibayar?" method="post">
                                            @csrf
                                            <input type="hidden" name="per" value="{{ $date->format('Ym') }}">
                                            <button type="submit" class="btn btn-sm">
                                                SAVE
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-thp">
                                <div class="table-responsive">
                                    <table class="table table-pc">
                                        <thead>
                                            <tr class="text-right">
                                                <th class="text-center">ID</th>
                                                <th class="text-center">NAMA</th>
                                                <th>GP + T2 + T3</th>
                                                <th>PENAMBAH</th>
                                                <th>PENGURANG</th>
                                                <th>THP</th>
                                                <th class="text-center">BANK</th>
                                                <th class="text-center">NOREK</th>
                                                <th class="text-center">ATAS NAMA</th>
                                                @if($db == 'sgn')
                                                <th>TRANSFER 1</th>
                                                <th>TRANSFER 2</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(sizeof($pcs) == 0)
                                            <tr>
                                                <td colspan="12" class="text-center">Belum ada data gajian.</td>
                                            </tr>
                                            @else
                                            @foreach($pcs as $p)
                                                <tr class="text-right">
                                                    <td class="text-center">{{ $p->id_kry }}</td>
                                                    <td class="text-left">{{ $p->nama }}</td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->gaji_total) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_bpjskes + $p->in_bpjsjht + $p->in_bpjsjkk + $p->in_bpjsjkm + $p->in_bpjsjkp + $p->in_bpjsjp + $p->in_lain) }}
                                                    </td>
                                                    <td>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->in_bpjskes + $p->in_bpjsjht + $p->in_bpjsjkk + $p->in_bpjsjkm + $p->in_bpjsjkp + $p->in_bpjsjp + $p->in_lain + $p->out_bpjskes + $p->out_bpjsjht + $p->out_bpjsjp + $p->out_lain) }}
                                                    </td>
                                                    <td><b>
                                                        {{ \App\Helpers\AppHelper::instance()->mf($p->thp) }}
                                                    </b></td>
                                                    <td class="text-left">{{ $p->rek_bank }}</td>
                                                    <td class="text-left">{{ $p->rek_no }}</td>
                                                    <td class="text-left">{{ $p->rek_nama }}</td>
                                                    @if($db == 'sgn')
                                                    <td>{{ \App\Helpers\AppHelper::instance()->mf($p->tf1) }}</td>
                                                    <td>{{ \App\Helpers\AppHelper::instance()->mf($p->tf2) }}</td>
                                                    @endif
                                                    <td class="text-center">
                                                        <form action="/{{$db}}/gajian/print" target="_blank" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$p->id}}">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-simple btn-slip">
                                                                <i class="tim-icons icon-paper"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="text-right">
                                                <td colspan="5"></td>
                                                <td><b>{{ App\Helpers\AppHelper::instance()->mf($total_thp) }}</b></td>
                                                <td colspan="3"></td>
                                                @if($db == 'sgn')
                                                
                                                <td><b>{{ App\Helpers\AppHelper::instance()->mf($total_tf1) }}</b></td>
                                                <td><b>{{ App\Helpers\AppHelper::instance()->mf($total_tf2) }}</b></td>
                                                @endif
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

    {{-- edit paycheck modal --}}
    <div class="modal fade" id="editPcModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editPcForm" action="/{{$db}}/gajian/edit/gaji" method="POST" data-msg="Edit data gajian?">
                    @csrf
                    <input type="hidden" name="idpc">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Gajian</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ _('Nama') }}</label>
                                <div class="form-group">
                                    <input type="text" name="nama" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Periode') }}</label>
                                <div class="form-group">
                                    <input type="text" name="per" class="form-control" value="{{ $date->format('Y/m') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Gaji Pokok') }}</label>
                                <div class="form-group">
                                    <input type="text" name="gp" class="form-control" placeholder="{{ _('Gaji Pokok') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Honor') }}</label>
                                <div class="form-group">
                                    <input type="text" name="honor" class="form-control" placeholder="{{ _('Gaji Pokok') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('T2. Jabatan') }}</label>
                                <div class="form-group">
                                    <input type="text" name="t2jabatan" class="form-control" placeholder="{{ _('T2. Jabatan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('T2. Masa Kerja') }}</label>
                                <div class="form-group">
                                    <input type="text" name="t2masa" class="form-control" placeholder="{{ _('T2. Masa Kerja') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('T3. Kerapian') }}</label>
                                <div class="form-group">
                                    <input type="text" name="t3kerapian" class="form-control" placeholder="{{ _('T3. Kerapian') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('T3. Transport') }}</label>
                                <div class="form-group">
                                    <input type="text" name="t3transport" class="form-control" placeholder="{{ _('T3. Transport') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('T3. Kehadiran') }}</label>
                                <div class="form-group">
                                    <input type="text" name="t3kehadiran" class="form-control" placeholder="{{ _('T3. Kehadiran') }}">
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-6">
                                <label>{{ _('Penambah Lain') }}</label>
                                <div class="form-group">
                                    <input type="text" name="inlain" class="form-control" placeholder="{{ _('Penambah Lain') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Pengurang Lain') }}</label>
                                <div class="form-group">
                                    <input type="text" name="outlain" class="form-control" placeholder="{{ _('Pengurang Lain') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-simple btn-warning" data-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary ml-2">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit bpjs modal --}}
    <div class="modal fade" id="editBPJSModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editBPJSForm" action="/{{$db}}/gajian/edit/bpjs" method="POST" data-msg="Edit data bpjs?">
                    @csrf
                    <input type="hidden" name="idpc">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit BPJS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ _('Nama') }}</label>
                                <div class="form-group">
                                    <input type="text" name="nama" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Periode') }}</label>
                                <div class="form-group">
                                    <input type="text" name="per" class="form-control" value="{{ $date->format('Y/m') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Kesehatan Perusahaan') }}</label>
                                <div class="form-group">
                                    <input type="text" name="bpjskesprs" class="form-control" placeholder="{{ _('Kesehatan Perusahaan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Kesehatan Karyawan') }}</label>
                                <div class="form-group">
                                    <input type="text" name="bpjskeskry" class="form-control" placeholder="{{ _('Kesehatan Karyawan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('JHT Perusahaan') }}</label>
                                <div class="form-group">
                                    <input type="text" name="bpjsjhtprs" class="form-control" placeholder="{{ _('JHT Perusahaan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('JHT Karyawan') }}</label>
                                <div class="form-group">
                                    <input type="text" name="bpjsjhtkry" class="form-control" placeholder="{{ _('JHT Karyawan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('JKK') }}</label>
                                <div class="form-group">
                                    <input type="text" name="bpjsjkk" class="form-control" placeholder="{{ _('JKK') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('JKM') }}</label>
                                <div class="form-group">
                                    <input type="text" name="bpjsjkm" class="form-control" placeholder="{{ _('JKM') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('JKP') }}</label>
                                <div class="form-group">
                                    <input type="text" name="bpjsjkp" class="form-control" placeholder="{{ _('JKP') }}">
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-6">
                                <label>{{ _('JP Perusahaan') }}</label>
                                <div class="form-group">
                                    <input type="text" name="bpjsjpprs" class="form-control" placeholder="{{ _('JP Perusahaan') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('JP Karyawan') }}</label>
                                <div class="form-group">
                                    <input type="text" name="bpjsjpkry" class="form-control" placeholder="{{ _('JP Karyawan') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-simple btn-warning" data-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary ml-2">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit pajak modal --}}
    <div class="modal fade" id="editPajakModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editPajakForm" action="/{{$db}}/gajian/edit/pajak" method="POST" data-msg="Edit jumlah pajak yang sudah dibayar?">
                    @csrf
                    <input type="hidden" name="idpc">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Pajak</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ _('Nama') }}</label>
                                <div class="form-group">
                                    <input type="text" name="nama" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Periode') }}</label>
                                <div class="form-group">
                                    <input type="text" name="per" class="form-control" value="{{ $date->format('Y/m') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('NPWP') }}</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="npwp" id="npwp1" value="1" checked>
                                            <label class="form-check-label pl-1" for="npwp1">Ya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="npwp" id="npwp0" value="0">
                                            <label class="form-check-label pl-1" for="npwp0">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('PTKP') }}</label>
                                <div class="form-group">
                                    <input type="text" name="ptkp" class="form-control" placeholder="{{ _('PTKP') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('PPh21 Terbayar') }}</label>
                                <div class="form-group">
                                    <input type="text" name="paidpph" class="form-control" placeholder="{{ _('PPh21 Terbayar') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-simple btn-warning" data-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary ml-2">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- hitung thr modal --}}
    <div class="modal fade" id="hitungTHRModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="hitungTHRForm" action="/{{$db}}/gajian/hitung/thr" method="POST" data-msg="Hitung THR?">
                    @csrf
                    <input type="hidden" name="per">
                    <div class="modal-header">
                        <h4 class="modal-title">Hitung THR</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ _('Tanggal Hari Raya') }}</label>
                                <div class="form-group">
                                    <input type="date" name="tglhr" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Periode Bayar') }}</label>
                                <div class="form-group">
                                    <input type="text" name="per" class="form-control" value="{{ $date->format('Y/m') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-simple btn-warning" data-dismiss="modal">BATAL</button>
                        <button type="submit" class="btn btn-primary ml-2">HITUNG</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            
        });

        $(".btn-edit-pc").click(function(){
            var formID = "#editPcForm";
            var pc = $(this).data('json');
            $(formID + " input:hidden[name='idpc']").val(pc.id);
            $(formID + " input[name='nama']").val(pc.nama);
            $(formID + " input[name='gp']").val(pc.in_gp);
            $(formID + " input[name='honor']").val(pc.in_honor);
            $(formID + " input[name='t2jabatan']").val(pc.in_t2jabatan);
            $(formID + " input[name='t2masa']").val(pc.in_t2masa);
            $(formID + " input[name='t3kerapian']").val(pc.in_t3kerapian);
            $(formID + " input[name='t3transport']").val(pc.in_t3transport);
            $(formID + " input[name='t3kehadiran']").val(pc.in_t3kehadiran);
            $(formID + " input[name='inlain']").val(pc.in_lain);
            $(formID + " input[name='outlain']").val(pc.out_lain);
        });

        $(".btn-edit-bpjs").click(function(){
            var formID = "#editBPJSForm";
            var b = $(this).data('json');
            $(formID + " input:hidden[name='idpc']").val(b.id);
            $(formID + " input[name='nama']").val(b.nama);
            $(formID + " input[name='bpjskesprs']").val(b.in_bpjskes);
            $(formID + " input[name='bpjskeskry']").val(b.out_bpjskes);
            $(formID + " input[name='bpjsjhtprs']").val(b.in_bpjsjht);
            $(formID + " input[name='bpjsjhtkry']").val(b.out_bpjsjht);
            $(formID + " input[name='bpjsjkk']").val(b.in_bpjsjkk);
            $(formID + " input[name='bpjsjkm']").val(b.in_bpjsjkm);
            $(formID + " input[name='bpjsjkp']").val(b.in_bpjsjkp);
            $(formID + " input[name='bpjsjpprs']").val(b.in_bpjsjp);
            $(formID + " input[name='bpjsjpkry']").val(b.out_bpjsjp);
        });

        $(".btn-edit-pajak").click(function(){
            var formID = "#editPajakForm";
            var b = $(this).data('json');
            $(formID + " input:hidden[name='idpc']").val(b.id);
            $(formID + " input[name='nama']").val(b.nama);
            $(formID + " input[name='npwp']").val([b.pph21_npwp]);
            $(formID + " input[name='ptkp']").val(b.pph21_ptkp);
            $(formID + " input[name='paidpph']").val(b.pph21_paid);
        });
    </script>
@endpush
