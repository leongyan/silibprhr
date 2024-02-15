@extends('layouts.app', ['page' => __($db . ' / Report'), 'pageSlug' => $db.'-report'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 form-inline">
                            <h4 class="title mr-1">{{ strtoupper($db) }}</h4><h5>Report Menu</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-pc table-hover table-borderless table-striped">
                                <tr>
                                    <td width="20%"><a href="#" class="btn btn-primary w-100" disabled>000001</a></td>
                                    <td><span class="px-2">Data Karyawan</span></td>
                                </tr>
                                <tr>
                                    <td width="20%"><a href="#" class="btn btn-primary w-100" disabled>000002</a></td>
                                    <td><span class="px-2">Data Gaji</span></td>
                                </tr>
                                <tr>
                                    <td width="20%"><a href="#" class="btn btn-primary w-100" disabled>000003</a></td>
                                    <td><span class="px-2">Rekap Gaji Setahun</span></td>
                                </tr>
                                <tr>
                                    <td width="20%"><a href="/{{$db}}/report/rekapkenaikangajiall" class="btn btn-primary w-100">000004</a></td>
                                    <td><span class="px-2">Rekap Kenaikan Gaji</span></td>
                                </tr>
                                <tr>
                                    <td width="20%"><a href="/{{$db}}/report/rekapkenaikangaji" class="btn btn-primary w-100">000005</a></td>
                                    <td><span class="px-2">Rekap Kenaikan Gaji Perorang</span></td>
                                </tr>
                                <tr>
                                    <td width="20%"><a href="#" class="btn btn-primary w-100" disabled>000006</a></td>
                                    <td><span class="px-2">Rekap Absensi</span></td>
                                </tr>
                                <tr>
                                    <td width="20%"><a href="#" class="btn btn-primary w-100" disabled>000007</a></td>
                                    <td><span class="px-2">Rekap Cuti</span></td>
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

    {{-- search user modal --}}
    <div class="modal fade" id="addCutiModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addCutiForm" action="/{{$db}}/cuti/tambah" method="POST" data-msg="Tambah data cuti?">
                    @csrf
                    {{-- <input type="hidden" name="per" value="{{ $date->format('Y') }}"> --}}
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Data Cuti</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        
                    <div class="modal-body">
                        
                    
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
