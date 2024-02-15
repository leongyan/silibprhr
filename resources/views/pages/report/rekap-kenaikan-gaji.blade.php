@extends('layouts.app', ['page' => __($db . ' / Report'), 'pageSlug' => $db.'-report'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 form-inline">
                            <h4 class="title mr-1">{{ strtoupper($db) }}</h4><h5>Rekap Kenaikan Gaji</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <form class="form-group" id="form" action="/{{ $db }}/report/rekapkenaikangaji/print" method="POST">
                                @csrf
                                <input type="hidden" name="id">
                                <div class="row">
                                    <div class="col-md-3 text-right">
                                        <label>ID Karyawan</label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="id_kry" readonly>
                                            <div class="input-group-append border-0">
                                                <button class="btn btn-primary btn-sm m-0" id="searchKryBtn" type="button" data-toggle="modal" data-target="#searchKryModal">
                                                    <i class="tim-icons icon-zoom-split"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100"></div>
                                    <div class="col-md-3 text-right">
                                        <label>Nama Karyawan</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="nama" class="form-control" readonly>
                                    </div>
                                    <div class="w-100 mb-2"></div>
                                    <div class="col-md-3 text-right">
                                        <label>Tahun Kenaikan</label>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="year">
                                            @for($i = date('Y') + 1; $i >= 2020; $i--)
                                            <option value="{{ $i }}" {{ $i === (int)$date->format('Y') - 1 ? "selected" : "" }}>
                                                {{ $i . ' - ' . $i + 1}}
                                            </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary" type="submit" form="form">VIEW</button>
                            <button class="btn btn-warning">CANCEL</button>
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
    <div class="modal fade" id="searchKryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cari Karyawan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <form class="form-group form-inline">
                            <div class="input-group w-100">
                                <input type="text" name="id" class="form-control" placeholder="{{ _('Search') }}">
                                <div class="input-group-append border-0">
                                    <button class="btn btn-sm m-0" type="button">
                                        <i class="tim-icons icon-zoom-split"></i>
                                    </button>
                                </div>
                                @include('alerts.feedback', ['field' => 'id'])
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class=" text-primary">
                                <th>ID</th>
                                <th>NAMA</th>
                                <th>STATUS</th>
                            </thead>
                            <tbody>
                                @foreach($kry as $k)
                                <tr>
                                    <td>
                                        <button class="btn btn-simple w-100 btn-select-kry" data-id="{{ $k->id }}">
                                            {{ $k->id_kry }}
                                        </button>
                                    </td>
                                    <td>{{ $k->nama }}</td>
                                    <td>
                                        @if($k->sts_kerja == 0)
                                        <span class="text-warning">Pending</span>
                                        @elseif($k->sts_kerja == 1)
                                        <span class="text-primary">Tetap</span>
                                        @elseif($k->sts_kerja == 2)
                                        <span class="text-info">Kontrak</span>
                                        @else
                                        <span class="text-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $("[data-toggle=popover]").popover();
        });

        $(".btn-select-kry").click(function(){
            var id = $(this).data("id");
            $.ajax({
                type:'GET',
                url:'/<?= $db ?>/karyawan/' + id,
                data:'_token = <?= csrf_token() ?>',
                success:function(data) {
                    var formID = '#form';
                    var k = jQuery.parseJSON(data);
                    $(formID + " input:hidden[name='id']").val(k.id);
                    $(formID + " input:text[name='id_kry']").val(k.id_kry);
                    $(formID + " input:text[name='nama']").val(k.nama);

                    $('#searchKryModal').modal('hide');
               }
            });
        });
    </script>
@endpush
