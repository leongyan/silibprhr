@extends('layouts.app', ['page' => __($db . ' / Cuti'), 'pageSlug' => $db.'-cuti'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 form-inline">
                            <h4 class="title mr-1">{{ strtoupper($db) }}</h4><h5>Cuti</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right form-inline">
                                <a class="btn btn-simple" href="/{{$db}}/cuti{{$prev}}">
                                    <span class="tim-icons icon-minimal-left"></span>
                                </a>
                                <form action="cuti">
                                    <select class="form-control" name="year">
                                        @for($i = date('Y') + 1; $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ $i === (int)$date->format('Y') ? "selected" : "" }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-simple">GO</button>
                                </form>
                                <a class="btn btn-simple" href="/{{$db}}/cuti{{$next}}">
                                    <span class="tim-icons icon-minimal-right"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{$date->copy()->startOfYear()->format('d M Y')}} - {{$date->copy()->endOfYear()->format('d M Y')}}</h4>
                        </div>
                    </div>
                    <div class="row text-primary mb-3">
                        @foreach($detailcbersama as $d)
                            @if($d->sts_by == 2)
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        {{$d->tgl->format('d/m D')}}
                                    </div>
                                    <div class="col-md-9">
                                        {{$d->desc}}
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-primary"><b>REKAP CUTI</b></h4>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th>ID</th>
                                            <th>NAMA</th>
                                            <th>MULAI KERJA</th>
                                            <th>JATAH CUTI PRIBADI</th>
                                            <th>CUTI PRIBADI</th>
                                            <th>CUTI BERSAMA</th>
                                            <th>CUTI KHUSUS</th>
                                            <th>CUTI LAIN</th>
                                            <th>TAMBAHAN CUTI</th>
                                            <th>SISA CUTI</th>
                                            <th class="text-right">KOMPENSASI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($krys as $k)
                                        <tr class="text-center">
                                            <td>{{ $k->id }}</td>
                                            <td class="text-left">{{ $k->nama }}</td>
                                            <td>{{ $k->tgl_mulai }}</td>
                                            <td>{{ \App\Helpers\AppHelper::instance()->mf($k->jatahcpribadi) }}</td>
                                            <td>
                                                <div>
                                                    <span class="mr-2">{{ \App\Helpers\AppHelper::instance()->mf($k->cpribadi) }}</span>
                                                    <button type="button" class="btn btn-info btn-link p-0 pb-2" data-toggle="popover" title="Cuti Pribadi" data-placement="right" data-html="true"
                                                    data-content="@foreach($k->cpribadiused as $c) {{ $c['tgl']->format('Y-m-d D')." : ".$c['ket']."<br>" }} @endforeach">
                                                        <i class="tim-icons icon-alert-circle-exc"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>{{ \App\Helpers\AppHelper::instance()->mf($k->cbersama) }}</td>
                                            <td>{{ \App\Helpers\AppHelper::instance()->mf($k->ckhusus) }}</td>
                                            <td>{{ \App\Helpers\AppHelper::instance()->mf($k->clain) }}</td>
                                            <td>{{ \App\Helpers\AppHelper::instance()->mf($k->tcuti) }}</td>
                                            <td>{{ \App\Helpers\AppHelper::instance()->mf($k->sisacuti) }}</td>
                                            <td class="text-right font-weight-bold">{{ \App\Helpers\AppHelper::instance()->mf($k->kompensasi) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="10" class="text-right"><b>TOTAL KOMPENSASI CUTI</b></td>
                                            <td class="text-right"><b>{{ \App\Helpers\AppHelper::instance()->mf($totalkompensasi) }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="text-primary"><b>CUTI LAIN</b></h4>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button class="btn btn-simple" data-toggle="modal" data-target="#addCutiModal">
                                        <b><i class="tim-icons icon-simple-add"></i></b>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Nama Karyawan</th>
                                        <th>Kepentingan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($clain) != 0)
                                        @foreach($clain as $c)
                                        <tr>
                                            <td>{{ $c->tgl }}</td>
                                            <td>{{ $c->tipestr }}</td>
                                            <td>{{ $c->nama }}</td>
                                            <td>{{ $c->ket }}</td>
                                            <td>
                                                <div class="form-inline">
                                                    <div class="mr-1">
                                                        <button type="button"
                                                            class="btn btn-sm btn-edit-lbr"
                                                            data-toggle="modal"
                                                            data-target="#editCutiModal"
                                                            data-id="{{ $c->id }}"
                                                            data-idkry="{{ $c->id_kry }}"
                                                            data-tgl="{{ $c->tgl }}"
                                                            data-ket="{{ $c->ket }}">
                                                            <i class="tim-icons icon-pencil"></i>
                                                        </button>
                                                    </div>
                                                    <form action="cuti/hapus" data-msg="Hapus data cuti?" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $c->id }}">
                                                        <button type="submit" class="btn btn-simple btn-warning btn-sm">
                                                            <i class="tim-icons icon-trash-simple"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5"><div class="text-warning">Belum ada data.</div></td>
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

    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

    {{-- search user modal --}}
    <div class="modal fade" id="addCutiModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addCutiForm" action="/{{$db}}/cuti/tambah" method="POST" data-msg="Tambah data cuti?">
                    @csrf
                    <input type="hidden" name="per" value="{{ $date->format('Y') }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Data Cuti</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ _('Tanggal') }}</label>
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="tgl">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ _('Tipe Cuti') }}</label>
                                    <div class="form-group">
                                        <select class="form-control" name="tipe">
                                            <option value="1">Cuti Pribadi</option>
                                            <option value="9">Cuti Lain</option>
                                            <option value="7">Tambahan Cuti</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ _('Karyawan') }}</label>
                                    <div class="form-group">
                                        <select class="form-control" name="id_kry">
                                            <option value="0">-- Pilih Karyawan --</option>
                                            @foreach($krys as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                 <div class="form-group">
                                    <label>{{ _('Keterangan') }}</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="ket">
                                    </div>
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
            $("[data-toggle=popover]").popover();
        });

        $(".btn-edit-lbr").click(function(){
            var formID = "#editCutiForm";
            $(formID + " input:hidden[name='id']").val($(this).data('id'));
            $(formID + " input[name='tgl']").val($(this).data('tgl'));
            $(formID + " input:text[name='ket']").val($(this).data('ket'));
        });
    </script>
@endpush
