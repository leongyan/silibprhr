@extends('layouts.app', ['page' => __($db . ' / Hari Libur'), 'pageSlug' => $db.'-hrlibur'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 form-inline">
                            <h4 class="title mr-1">{{ strtoupper($db) }}</h4><h5>Hari Libur</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right form-inline">
                                <form action="hrlibur">
                                    <select class="form-control" name="year">
                                        @for($i = date('Y') + 1; $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ $i === (int)$date->format('Y') ? "selected" : "" }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-simple">GO</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-wrap table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>TANGGAL</th>
                                            <th>KETERANGAN</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(sizeof($lbr) > 0)
                                            @foreach($lbr as $l)
                                            <tr>
                                                <td>{{ $l->tgl }}</td>
                                                <td>{{ $l->desc }}</td>
                                                <td>
                                                    <div class="form-inline">
                                                        <div class="mr-1">
                                                            <button type="button"
                                                                class="btn btn-sm btn-edit-lbr"
                                                                data-toggle="modal"
                                                                data-target="#editLbrModal"
                                                                data-id="{{ $l->id }}"
                                                                data-tgl="{{ $l->tgl }}"
                                                                data-ket="{{ $l->desc }}"
                                                                {{ $l->sts_by == 2 ? '' : ' disabled' }}>
                                                                <i class="tim-icons icon-pencil"></i>
                                                            </button>
                                                        </div>
                                                        <form action="hrlibur/hapus" data-msg="Hapus hari libur?" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $l->id }}">
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
                                            <td colspan="3" class="text-center">
                                                <h5>Belum ada data hari libur.</h5>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ _('Hari Libur Fixed') }}</h6>
                            <form action="hrlibur/edit/fixed" method="POST" data-msg="Update hari libur?">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ _('Tahun') }}</label>
                                            <div class="form-group {{ $errors->has('year') ? ' has-danger' : '' }}">
                                                <input type="text" name="year"
                                                    class="form-control{{ $errors->has('year') ? ' is-invalid' : '' }}"
                                                    placeholder="{{ _('Tahun') }}"
                                                    value="{{ $date->format('Y') }}"
                                                    readonly>
                                                @include('alerts.feedback', ['field' => 'year'])
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label>{{ _('Weekend') }}</label>
                                        <div class="form-group">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label mr-3">
                                                    <input class="form-check-input" type="checkbox" value="1" name="sabtu" checked>
                                                    <span class="form-check-sign">
                                                        <span class="check">Sabtu</span>
                                                    </span>
                                                </label>
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" value="1" name="minggu" checked>
                                                    <span class="form-check-sign">
                                                        <span class="check">Minggu</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">SAVE</button>
                                </div>
                            </form>
                            <hr>
                            <h6>{{ _('Hari Libur Lain') }}</h6>
                            <form class="form form-group" action="hrlibur/tambah" method="POST" data-msg="Tambah hari libur?">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>{{ _('Tanggal') }}</label>
                                        <div class="form-group {{ $errors->has('tgl') ? ' has-danger' : '' }}">
                                            <input type="date" name="tgl"
                                                class="form-control{{ $errors->has('tgl') ? ' is-invalid' : '' }}"
                                                placeholder="{{ _('Tanggal') }}"
                                                value="{{ old('tgl') }}">
                                            @include('alerts.feedback', ['field' => 'tgl'])
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>{{ _('Keterangan') }}</label>
                                        <div class="form-group {{ $errors->has('ket') ? ' has-danger' : '' }}">
                                            <input type="text" name="ket"
                                                class="form-control{{ $errors->has('ket') ? ' is-invalid' : '' }}"
                                                placeholder="{{ _('Keterangan') }}"
                                                value="{{ old('ket') }}">
                                            @include('alerts.feedback', ['field' => 'ket'])
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">SAVE</button>
                                </div>
                            </form>
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
    <div class="modal fade" id="editLbrModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editLbrForm" action="hrlibur/edit/var" method="POST" data-msg="Update hari libur?">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Hari Libur</h4>
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
                                        <input type="date" class="form-control" name="tgl" disabled>
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

        });

        $(".btn-edit-lbr").click(function(){
            var formID = "#editLbrForm";
            $(formID + " input:hidden[name='id']").val($(this).data('id'));
            $(formID + " input[name='tgl']").val($(this).data('tgl'));
            $(formID + " input:text[name='ket']").val($(this).data('ket'));
        });
    </script>
@endpush
