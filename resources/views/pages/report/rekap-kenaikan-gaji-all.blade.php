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
                            <form class="form-group" id="form" action="/{{ $db }}/report/rekapkenaikangajiall/print" method="POST">
                                @csrf
                                <input type="hidden" name="id">
                                <div class="row">
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
