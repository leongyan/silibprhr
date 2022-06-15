@extends('layouts.app', ['page' => __($db . ' / Kehadiran'), 'pageSlug' => $db.'-kehadiran'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 form-inline">
                            <h4 class="title mr-1">{{ strtoupper($db) }}</h4><h5>Kehadiran</h5>
                            {{-- <h5 class="title">{{ _('Karyawan') }}</h5> --}}
                        </div>
                        <div class="col-md-6">
                            <div class="float-right form-inline">
                                <a class="btn btn-simple" href="/{{$db}}/kehadiran{{$prev}}">
                                    <span class="tim-icons icon-minimal-left"></span>
                                </a>
                                <form action="kehadiran">
                                    <select class="form-control" name="month">
                                        @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $i === (int)$end->format('m') ? "selected" : "" }}>
                                            {{ $end->copy()->day(1)->month($i)->format('M') }}
                                        </option>
                                        @endfor
                                    </select>
                                    <select class="form-control" name="year">
                                        @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ $i === (int)$end->format('Y') ? "selected" : "" }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-simple">GO</button>
                                </form>
                                <a class="btn btn-simple" href="/{{$db}}/kehadiran{{$next}}">
                                    <span class="tim-icons icon-minimal-right"></span>
                                </a>
                                {{-- <select id="pageAction" name="pageaction" class="form-control">
                                    <option value="1">Tambah</option>
                                    <option value="2">Display</option>
                                    <option value="3">Edit</option>
                                    <option value="4">Hapus</option>
                                </select> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="row">
                        <div class="col-md-6">
                            <h4>{{$start->format('d M Y')}} - {{$end->format('d M Y')}}</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="form-inline justify-content-end">
                                <form class="form-inline justify-content-end" action="/{{$db}}/kehadiran/getcsv" method="POST">
                                    @csrf
                                    <input type="hidden" name="tgl" value="{{$end}}">
                                    <button type="submit" class="btn btn-simple btn-sm">GET CSV</button>
                                </form>
                                <form class="form-inline justify-content-end" action="/{{$db}}/kehadiran/import"
                                    method="POST" enctype="multipart/form-data" data-msg="Import data kehadiran?">
                                    @csrf
                                    <input type="hidden" name="tgl" value="{{$end}}">
                                    <input type="file" name="csv" class="form-control-file w-auto ml-2">
                                    <button type="submit" class="btn btn-primary btn-sm">IMPORT</button>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row text-primary mb-3">
                        @foreach($lbr as $l)
                            @if($l->sts_by == 2)
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        {{$l->tgl->format('d/m')}}
                                    </div>
                                    <div class="col-md-9">
                                        {{$l->desc}}
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="table-responsive">
                        <table class="table table-att">
                            <thead>
                                <tr>
                                    <td rowspan="2">ID</td>
                                    <td rowspan="2">NAMA</td>
                                    @foreach($days as $d)
                                    <td class="text-center {{ $d->lbr === 1 ? "att-green" : "" }}">{{ $d->tgl->format('d') }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach($days as $d)
                                    <td class="text-center {{ $d->lbr === 1 ? "att-green" : "" }}">{{  substr($d->tgl->format('D'), 0, 1) }}</td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($atts as $a)
                                <tr>
                                    <td>{{ $a->id_kry }}</td>
                                    <td>{{ $a->nama }}</td>
                                    @foreach($days as $d)
                                        @if(array_key_exists($d->tgl->toDateString(), $a->atts))
                                        <td class="text-center">
                                            <button type="button" class="btn btn-link btn-att btn-att-edit"
                                                data-toggle="modal" data-target="#editAttModal"
                                                data-id="{{ ($a->atts)[$d->tgl->toDateString()]->id }}"
                                                data-nama="{{$a->nama}}"
                                                data-tgl="{{ $d->tgl->toDateString() }}"
                                                data-att="{{ ($a->atts)[$d->tgl->toDateString()]->att }}">
                                                @switch( ($a->atts)[$d->tgl->toDateString()]->att )
                                                    @case(11)
                                                        <span class="glyphicon dot-blue"></span>
                                                        @break
                                                    @case(12)
                                                        <b class="att-blue">W</b>
                                                        @break
                                                    @case(13)
                                                        <b class="att-blue">D</b>
                                                        @break
                                                    @case(21)
                                                        <b class="att-green">S</b>
                                                        @break
                                                    @case(22)
                                                        <span class="glyphicon dot-green"></span>
                                                        @break
                                                    @case(23)
                                                        <span class="glyphicon dot-green"></span>
                                                        @break
                                                    @case(24)
                                                        <b class="att-green">K</b>
                                                        @break
                                                    @case(31)
                                                        <b class="att-red">S</b>
                                                        @break
                                                    @case(32)
                                                        <b class="att-red">I</b>
                                                        @break
                                                    @case(33)
                                                        <span class="glyphicon dot-red"></span>
                                                        @break
                                                    @default
                                                        <span></span>
                                                @endswitch
                                            </button>
                                        </td>
                                        @else
                                        <td class="text-center">
                                            <button type="button" class="btn btn-link btn-att btn-att-tambah"
                                                data-toggle="modal" data-target="#tambahAttModal"
                                                data-kryid="{{ $a->id_kry }}" data-nama="{{ $a->nama }}"
                                                data-tgl="{{ $d->tgl->toDateString() }}"
                                                >
                                                <span class="tim-icons icon-simple-add"></span>
                                            </button>
                                        </td>
                                        @endif
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-att">
                                <tr>
                                    <td width="30px" class="text-center"><span class="glyphicon dot-blue"></span></td>
                                    <td>HADIR</td>
                                </tr>
                                <tr>
                                    <td width="30px" class="text-center"><b class="att-blue">W</b></td>
                                    <td>WORK FROM HOME</td>
                                </tr>
                                <tr>
                                    <td width="30px" class="text-center"><b class="att-blue">D</b></td>
                                    <td>DINAS DI LUAR KANTOR</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-att">
                                <tr>
                                    <td width="30px" class="text-center"><b class="att-green">S</b></td>
                                    <td>SAKIT DENGAN KETERANGAN DOKTER</td>
                                </tr>
                                <tr>
                                    <td width="30px" class="text-center"><span class="glyphicon dot-green"></span></td>
                                    <td>CUTI PRIBADI / BERSAMA</td>
                                </tr>
                                <tr>
                                    <td width="30px" class="text-center"><b class="att-green">K</b></td>
                                    <td>CUTI KHUSUS</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-att">
                                <tr>
                                    <td width="30px" class="text-center"><b class="att-red">S</b></td>
                                    <td>SAKIT TANPA KETERANGAN DOKTER</td>
                                </tr>
                                <tr>
                                    <td width="30px" class="text-center"><b class="att-red">I</b></td>
                                    <td>IZIN</td>
                                </tr>
                                <tr>
                                    <td width="30px" class="text-center"><span class="glyphicon dot-red"></span></td>
                                    <td>TIDAK HADIR TANPA KETERANGAN</td>
                                </tr>
                            </table>
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

    {{-- edit kehadiran modal --}}
    <div class="modal fade" id="editAttModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editAttForm" action="/{{$db}}/kehadiran/edit" method="POST" data-msg="Update kehadiran?">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Kehadiran</h4>
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
                                <label>{{ _('Tanggal') }}</label>
                                <div class="form-group">
                                    <input type="date" name="tgl" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>{{ _('Kehadiran') }}</label>
                                <div class="form-group">
                                    <select name="att" class="form-control">
                                        <option value="11">11 - Hadir</option>
                                        <option value="12">12 - Work From Home</option>
                                        <option value="13">13 - Dinas di luar kantor</option>
                                        <option value="21">21 - Sakit dengan keterangan dokter</option>
                                        <option value="22">22 - Cuti pribadi</option>
                                        <option value="23">23 - Cuti bersama</option>
                                        <option value="24">24 - Cuti khusus</option>
                                        <option value="31">31 - Sakit tanpa keterangan dokter</option>
                                        <option value="32">32 - Izin</option>
                                        <option value="33">33 - Tidak hadir tanpa keterangan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div>
                            <button type="submit" name="submit" value="delete" class="btn btn-danger">DELETE</button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-simple btn-warning" data-dismiss="modal">BATAL</button>
                            <button type="submit" name="submit" value="edit" class="btn btn-primary ml-2">SAVE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- tambah kehadiran modal --}}
    <div class="modal fade" id="tambahAttModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="tambahAttForm" action="/{{$db}}/kehadiran/tambah" method="POST" data-msg="Tambah kehadiran?">
                    @csrf
                    <input type="hidden" name="kryid">
                    <input type="hidden" name="per" value="{{ $end->format('Ym') }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Kehadiran</h4>
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
                                <label>{{ _('Tanggal') }}</label>
                                <div class="form-group">
                                    <input type="date" name="tgl" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>{{ _('Kehadiran') }}</label>
                                <div class="form-group">
                                    <select name="att" class="form-control">
                                        <option value="11">11 - Hadir</option>
                                        <option value="12">12 - Work From Home</option>
                                        <option value="13">13 - Dinas di luar kantor</option>
                                        <option value="21">21 - Sakit dengan keterangan dokter</option>
                                        <option value="22">22 - Cuti pribadi</option>
                                        <option value="23">23 - Cuti bersama</option>
                                        <option value="24">24 - Cuti khusus</option>
                                        <option value="31">31 - Sakit tanpa keterangan dokter</option>
                                        <option value="32">32 - Izin</option>
                                        <option value="33">33 - Tidak hadir tanpa keterangan</option>
                                    </select>
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
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

        });

        $(".btn-att.btn-att-edit").click(function(){
            var formID = "#editAttForm";
            $(formID + " input:hidden[name='id']").val($(this).data('id'));
            $(formID + " input[name='nama']").val($(this).data('nama'));
            $(formID + " input[name='tgl']").val($(this).data('tgl'));
            $(formID + " select[name='att']").val($(this).data('att'));
        });

        $(".btn-att.btn-att-tambah").click(function(){
            var formID = "#tambahAttForm";
            $(formID + " input:hidden[name='kryid']").val($(this).data('kryid'));
            $(formID + " input[name='nama']").val($(this).data('nama'));
            $(formID + " input[name='tgl']").val($(this).data('tgl'));
        });
    </script>
@endpush
