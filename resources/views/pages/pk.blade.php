@extends('layouts.app', ['page' => __($db . ' / Karyawan'), 'pageSlug' => $db.'-pk'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="pkForm" method="post" action="pk/tambah" autocomplete="off" data-msg="Proses data?">
                    <input type="text" name="id" value="{{ old('id') }}" hidden>
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 form-inline">
                                <h4 class="title mr-1">{{ strtoupper($db) }}</h4><h5>Perjanjian Kerja</h5>
                                {{-- <h5 class="title">{{ _('Karyawan') }}</h5> --}}
                            </div>
                            <div class="col-md-6">
                                <div class="float-right">
                                    <select id="pageAction" name="pageaction" class="form-control">
                                        <option value="1">Tambah</option>
                                        <option value="2">Display</option>
                                        <option value="3">Edit</option>
                                        <option value="4">Hapus</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        {{-- @method('put') --}}
                        @include('alerts.success')
                        <h3>Data Perjanjian Kerja</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ _('ID') }}</label>
                                <div class="input-group{{ $errors->has('idPk') ? ' has-danger' : '' }}">
                                    <input type="text" name="idPk" id="idTf" 
                                        class="form-control{{ $errors->has('idPk') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('ID Perjanjian Kerja') }}"
                                        value="{{ old('idPk') }}"
                                        disabled>
                                    <div class="input-group-append border-0">
                                        <div class="input-group-btn p-0">
                                            <button class="btn m-0" id="searchPkBtn" type="button" data-toggle="modal" data-target="#searchPkModal
                                            " disabled>
                                                <i class="tim-icons icon-zoom-split"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @include('alerts.feedback', ['field' => 'id'])
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Nomor') }}</label>
                                <div class="form-group {{ $errors->has('no') ? ' has-danger' : '' }}">
                                    <input type="text" name="no"
                                        class="form-control{{ $errors->has('no') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('Nomor') }}"
                                        value="{{ old('no') }}">
                                    @include('alerts.feedback', ['field' => 'no'])
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-3">
                                <div>
                                    <label>{{ _('Nama') }}</label>
                                    <div class="form-group {{ $errors->has('nama') ? ' has-danger' : '' }}">
                                        <input type="text" name="nama"
                                            class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}"
                                            placeholder="{{ _('Nama') }}"
                                            value="{{ old('nama') }}">
                                        @include('alerts.feedback', ['field' => 'nama'])
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>{{ _('Tipe') }}</label>
                                <div class="form-group {{ $errors->has('tipe') ? ' has-danger' : '' }}">
                                    <select class="form-control" name="tipe">
                                        <option value="1">AD - Anggaran Dasar Perusahaan</option>
                                        <option value="2">PKWTT - Perjanjian Kerja Waktu Tidak Tertentu</option>
                                        <option value="3">PKWT - Perjanjian Kerja Waktu Tertentu</option>
                                    </select>
                                    @include('alerts.feedback', ['field' => 'tipe'])
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>{{ _('Perpanjangan') }}</label>
                                <div class="form-group {{ $errors->has('ext') ? ' has-danger' : '' }}">
                                    <div class="input-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ext" id="ext1" value="1" disabled>
                                            <label class="form-check-label pl-1" for="ext1">Ya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ext" id="ext0" value="0" disabled checked>
                                            <label class="form-check-label pl-1" for="ext0">Tidak</label>
                                        </div>
                                        @include('alerts.feedback', ['field' => 'ext'])
                                    </div>
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-3">
                                <label>{{ _('Tanggal Melamar Kerja') }}</label>
                                <div class="form-group {{ $errors->has('tglapp') ? ' has-danger' : '' }}">
                                    <input type="date" name="tglapp"
                                        class="form-control{{ $errors->has('tglapp') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('DD/MM/YYYY') }}" 
                                        value="{{ old('tglapp') }}">
                                    @include('alerts.feedback', ['field' => 'tglapp'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Tanggal Mulai') }}</label>
                                <div class="form-group {{ $errors->has('tglmulai') ? ' has-danger' : '' }}">
                                    <input type="date" name="tglmulai"
                                        class="form-control{{ $errors->has('tglmulai') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('DD/MM/YYYY') }}" 
                                        value="{{ old('tglmulai') }}">
                                    @include('alerts.feedback', ['field' => 'tglmulai'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Tanggal Akhir') }}</label>
                                <div class="form-group {{ $errors->has('tglakhir') ? ' has-danger' : '' }}">
                                    <input type="date" name="tglakhir"
                                        class="form-control{{ $errors->has('tglakhir') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('DD/MM/YYYY') }}" 
                                        value="{{ old('tglakhir') }}">
                                    @include('alerts.feedback', ['field' => 'tglakhir'])
                                </div>
                            </div>
                            <div class="w-100"></div>
                            
                            <div class="col-md-3">
                                <div>
                                    <label>{{ _('Unit Kerja') }}</label>
                                    <div class="form-group {{ $errors->has('div') ? ' has-danger' : '' }}">
                                        <input type="text" name="div"
                                            class="form-control{{ $errors->has('div') ? ' is-invalid' : '' }}"
                                            placeholder="{{ _('Unit Kerja') }}"
                                            value="{{ old('div') }}">
                                        @include('alerts.feedback', ['field' => 'div'])
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>{{ _('Job Description') }}</label>
                                <div class="form-group {{ $errors->has('desc') ? ' has-danger' : '' }}">
                                    <textarea name="desc" rows="5" 
                                        class="form-control{{ $errors->has('desc') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('misal: Menganalisa kredit, Melayani transaksi nasabah, dsb') }}"
                                        value="{{ old('desc') }}"></textarea>
                                    @include('alerts.feedback', ['field' => 'desc'])
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="collapse" id="parentPKdiv">
                            <h3>Perjanjian Kerja Induk</h3>
                            <p class="text-info">Diisi hanya untuk perpanjangan PKWT.</p>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{ _('ID') }}</label>
                                    <div class="input-group{{ $errors->has('idPk') ? ' has-danger' : '' }}">
                                        <input type="text" name="idPk" id="idTf" 
                                            class="form-control{{ $errors->has('idPk') ? ' is-invalid' : '' }}"
                                            placeholder="{{ _('ID Perjanjian Kerja') }}"
                                            value="{{ old('idPk') }}"
                                            disabled>
                                        <div class="input-group-append border-0">
                                            <div class="input-group-btn p-0">
                                                <button class="btn m-0" id="searchPkBtn" type="button" data-toggle="modal" data-target="#searchPkModal
                                                " disabled>
                                                    <i class="tim-icons icon-zoom-split"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @include('alerts.feedback', ['field' => 'id'])
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>{{ _('Nomor') }}</label>
                                    <div class="form-group {{ $errors->has('no') ? ' has-danger' : '' }}">
                                        <input type="text" name="no"
                                            class="form-control{{ $errors->has('no') ? ' is-invalid' : '' }}"
                                            placeholder="{{ _('Nomor') }}"
                                            value="{{ old('no') }}">
                                        @include('alerts.feedback', ['field' => 'no'])
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 offset-md-4 text-right">
                                <h6>Created:</h6>
                                <h6>Last Updated:</h6>
                            </div>
                            <div class="col-md-2">
                                <h6 id="createdAt">-</h6>
                                <h6 id="updatedAt">-</h6>
                            </div>
                            <div class="col-md-3 text-right">
                                <button type="submit" class="btn btn-primary" id="submitBtn">SIMPAN</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

    {{-- search user modal --}}
    <div class="modal fade" id="searchPkModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cari Perjanjian Kerja</h4>
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
                                    <div class="input-group-btn p-0">
                                        <button class="btn m-0" type="button">
                                            <i class="tim-icons icon-zoom-split"></i>
                                        </button>
                                    </div>
                                </div>
                                @include('alerts.feedback', ['field' => 'id'])
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class=" text-primary text-center">
                                <th>ID</th>
                                <th>NAMA</th>
                                <th>TIPE</th>
                                <th>TGL MULAI</th>
                                <th>TGL AKHIR</th>
                                <th>STATUS</th>
                            </thead>
                            <tbody class="text-center">
                                @foreach($pks as $p)
                                <tr>
                                    <td>
                                        <button class="btn btn-simple w-100 btn-select-pk" data-id="{{ $p->id }}">
                                            {{ $p->id_pk }}
                                        </button>
                                    </td>
                                    <td class="text-left">{{ $p->nama }}</td>
                                    <td>
                                        @if($p->tipe == 1)
                                        <span class="text-primary">AD</span>
                                        @elseif($p->tipe == 2)
                                        <span class="text-info">PKWTT</span>
                                        @else
                                        <span class="text-danger">PKWT</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->tgl_mulai }}</td>
                                    <td>{{ $p->tgl_akhir}}</td>
                                    <td>
                                        <span class="{{ $p->sts == 'AKTIF' ? 'text-primary' : 'text-danger'  }}">
                                            {{ $p->sts }}
                                        </span>
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
            const oldJkel = '{{ old('jkel') }}';
            const oldStsKerja = '{{ old('stskerja') }}';
            const oldStsPajak = '{{ old('stspajak') }}';
            const oldTanggungan = '{{ old('tanggungan') }}';
            const oldBpjsKes = '{{ old('bpjskes') }}';
            const oldBpjsTk = '{{ old('bpjstk') }}';
            const oldPageAction = '{{ old('pageaction') }}';

            if(oldStsKerja !== '') {
                $("#kryForm select[name='stskerja']").val([oldStsKerja]);
            }
            if(oldJkel !== '') {
                $("#kryForm input[name='jkel']").val([oldJkel]);
            }
            if(oldStsPajak !== '') {
                $("#kryForm input[name='stspajak']").val([oldStsPajak]);
            }
            if(oldTanggungan !== '') {
                $("#kryForm select[name='tanggungan']").val([oldTanggungan]).change();
            }
            if(oldBpjsKes !== '') {
                $("#kryForm input[name='bpjskes']").val([oldBpjsKes]);
            }
            if(oldBpjsTk !== '') {
                $("#kryForm input[name='bpjstk']").val([oldBpjsTk]);
            }
            if(oldPageAction !== '') {
                $("#kryForm select[name='pageaction']").val([oldPageAction]);
                setPageAction(oldPageAction);
            }

            var action = '{{ $action ?? '' }}';
            if(action != ''){
                $("#kryForm select[name='pageaction']").val([action]);
                setPageAction(action);
                $('#pageAction').prop('disabled', false);
            }

            var json = decodeHtml('{{ json_encode($selected ?? '') }}');
            if(json != ''){
                setData(json);
            }
        });

        $('#pkForm input[name="ext"]').on('change', function(){
            var val = $('#pkForm input[name="ext"]:checked').val();
            if(val == 1){
                $('#parentPKdiv').collapse('show');
                $('#pkForm input[name="tglapp"]').prop('readonly', true);
            }
            else{
                $('#parentPKdiv').collapse('hide');
                $('#pkForm input[name="tglapp"]').prop('readonly', false);
                $('#pkForm input[name="tglapp"]').val('');
            }
        });

        $('#pkForm select[name="tipe"]').on('change', function() {
            var val = $('#pkForm select[name="tipe"]').val();
            if(val == 3){
                $('#pkForm input[name="ext"]').prop('disabled', false);
            }
            else{
                $('#pkForm input[name="ext"]').prop('disabled', true);
            }
        });

        $('#pageAction').on('change', function() {
            $("#kryForm input[type='text']").val("");
            $("#kryForm input[type='date']").val("");

            $("#createdAt").html("-");
            $("#updatedAt").html("-");
            $('.has-danger').removeClass('has-danger');
            $('.is-invalid').removeClass('is-invalid');

            setPageAction($(this).val());

            $(this).prop("disabled", false);
        });

        function setPageAction(actionVal){
            if(actionVal == 1){
                $("#pkForm input").prop("disabled", false);
                $("#pkForm button").prop("disabled", false);
                $("#pkForm select").prop("disabled", false);

                $("#pkForm #idTf").prop("disabled", true);
                $("#pkForm #searchPkBtn").prop("disabled", true);

                $("#pkForm").attr('action', "karyawan/tambah");
            }
            else if(actionVal == 2){
                $("#pkForm input").prop("disabled", true);
                $("#pkForm button").prop("disabled", true);
                $("#pkForm select").prop("disabled", true);

                $("#pkForm #searchPkBtn").prop("disabled", false);

                $("#pkForm").attr('action', "karyawan/display");
            }
            else if(actionVal == 3){
                $("#pkForm input").prop("disabled", false);
                $("#pkForm button").prop("disabled", false);
                $("#pkForm select").prop("disabled", false);

                $("#pkForm #idTf").prop("readonly", true);

                $("#pkForm").attr('action', "karyawan/edit");
            }
            else if(actionVal == 4){
                $("#pkForm input").prop("disabled", true);
                $("#pkForm button").prop("disabled", true);
                $("#pkForm select").prop("disabled", true);

                $("#pkForm #searchPkBtn").prop("disabled", false);
                $("#pkForm #submitBtn").prop("disabled", false);

                $("#pkForm").attr('action', "karyawan/hapus");
            }
        }

        $(".btn-select-pk").click(function(){
            var id = $(this).data("id");
            $.ajax({
                type:'GET',
                url:'/<?= $db ?>/pk/' + id,
                data:'_token = <?= csrf_token() ?>',
                success:function(data) {
                    setData(data);
               }
            });
        });

        function setData(data){
            var formID = "#pkForm";
            var k = jQuery.parseJSON(data);
            $(formID + " input:text[name='id']").val(k.id);
            $(formID + " input:text[name='idPk']").val(k.id_pk);
            $(formID + " input:text[name='no']").val(k.no);
            // $(formID + " input:text[name='tmptlahir']").val(k.tempat_lahir);
            // $(formID + " input[name='tgllahir']").val(k.tgl_lahir);
            // $(formID + " input[name='jkel']").val([k.jenis_kelamin]);
            // $(formID + " input:text[name='nik']").val(k.nik);
            // $(formID + " input:text[name='nohp']").val(k.no_hp);
            // $(formID + " input:text[name='alamat']").val(k.alamat);

            // $(formID + " input:text[name='bank']").val(k.rek_bank);
            // $(formID + " input:text[name='rek']").val(k.rek_no);
            // $(formID + " input:text[name='anrek']").val(k.rek_nama);

            // $(formID + " input:text[name='npwp']").val(k.npwp);
            // $(formID + " input[name='stspajak']").val([k.pph_sts]);
            // $(formID + " select[name='tanggungan']").val([k.pph_tg]).change();
            // $(formID + " input[name='bpjskes']").val([k.bpjs_kes]);
            // $(formID + " input[name='bpjstk']").val([k.bpjs_tk]);

            // $(formID + " input[name='tglmulai']").val(k.tgl_mulai);
            // $(formID + " input[name='tglakhir']").val(k.tgl_akhir);
            // $(formID + " select[name='stskerja']").val([k.sts_kerja]).change();
            
            // $(formID + " input:text[name='gp']").val(k.gp);
            // $(formID + " input:text[name='t2masa']").val(k.t2masa);
            // $(formID + " input:text[name='t2jabatan']").val(k.t2jabatan);
            // $(formID + " input:text[name='t3kerapian']").val(k.t3kerapian);
            // $(formID + " input:text[name='t3transport']").val(k.t3transport);
            // $(formID + " input:text[name='t3kehadiran']").val(k.t3kehadiran);
            // $(formID + " input:text[name='honor']").val(k.honor);
            // $(formID + " input:text[name='basebpjskes']").val(k.base_bpjskes);
            // $(formID + " input:text[name='basebpjstk']").val(k.base_bpjstk);

            $("#createdAt").html(k.created_at);
            $("#updatedAt").html(k.updated_at);

            $('#searchPkModal').modal('hide');
        }

        function decodeHtml(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }
    </script>
@endpush
