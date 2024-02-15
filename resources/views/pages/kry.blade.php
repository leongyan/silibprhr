@extends('layouts.app', ['page' => __($db . ' / Karyawan'), 'pageSlug' => $db.'-karyawan'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form id="kryForm" method="post" action="karyawan/tambah" autocomplete="off" data-msg="Proses data?">
                    <input type="text" name="id" value="{{ old('id') }}" hidden>
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 form-inline">
                                <h4 class="title mr-1">{{ strtoupper($db) }}</h4><h5>Karyawan</h5>
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
                        <h3>Data Pribadi</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ _('ID') }}</label>
                                <div class="input-group{{ $errors->has('idKry') ? ' has-danger' : '' }}">
                                    <input type="text" name="idKry" id="idTf" 
                                        class="form-control{{ $errors->has('idKry') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('ID Karyawan') }}"
                                        value="{{ old('idKry') }}"
                                        disabled>
                                    <div class="input-group-append border-0">
                                        <button class="btn btn-sm m-0" id="searchKryBtn" type="button" data-toggle="modal" data-target="#searchKryModal
                                            " disabled>
                                                <i class="tim-icons icon-zoom-split"></i>
                                            </button>
                                    </div>
                                    @include('alerts.feedback', ['field' => 'id'])
                                </div>
                            </div>
                            <div class="col-md-6">
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
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ _('Tempat Kelahiran') }}</label>
                                <div class="form-group {{ $errors->has('tmptlahir') ? ' has-danger' : '' }}">
                                    <input type="text" name="tmptlahir"
                                        class="form-control{{ $errors->has('tmptlahir') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('Tempat Kelahiran') }}"
                                        value="{{ old('tmptlahir') }}">
                                    @include('alerts.feedback', ['field' => 'tmptlahir'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Tanggal Lahir') }}</label>
                                <div class="form-group {{ $errors->has('tgllahir') ? ' has-danger' : '' }}">
                                    <input type="date" name="tgllahir"
                                        class="form-control{{ $errors->has('tgllahir') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('DD/MM/YYYY') }}" 
                                        value="{{ old('tgllahir') }}">
                                    @include('alerts.feedback', ['field' => 'tgllahir'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Jenis Kelamin') }}</label>
                                <div class="form-group {{ $errors->has('jkel') ? ' has-danger' : '' }}">
                                    <div class="input-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jkel" id="jkel1" value="1" checked>
                                            <label class="form-check-label pl-1" for="jkel1">Pria</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jkel" id="jkel0" value="0">
                                            <label class="form-check-label pl-1" for="jkel0">Wanita</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ _('Nomor Induk Kependudukan') }}</label>
                                <div class="form-group {{ $errors->has('nik') ? ' has-danger' : '' }}">
                                    <input type="text" name="nik"
                                        class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('NIK') }}"
                                        value="{{ old('nik') }}">
                                    @include('alerts.feedback', ['field' => 'nik'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('No. Handphone') }}</label>
                                <div class="form-group {{ $errors->has('nohp') ? ' has-danger' : '' }}">
                                    <input type="text" name="nohp"
                                        class="form-control{{ $errors->has('nohp') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('No. Handphone') }}"
                                        value="{{ old('nohp') }}">
                                        @include('alerts.feedback', ['field' => 'nohp'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <label>{{ _('Alamat') }}</label>
                                <div class="form-group {{ $errors->has('alamat') ? ' has-danger' : '' }}">
                                    <input type="text" name="alamat"
                                        class="form-control{{ $errors->has('alamat') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('Alamat') }}"
                                        value="{{ old('alamat') }}">
                                    @include('alerts.feedback', ['field' => 'alamat'])
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h3>Status Kerja</h3>
                        <div class="row">
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
                                <label>{{ _('Tanggal Berhenti') }}</label>
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
                                <label>{{ _('Status Kerja') }}</label>
                                <div class="form-group {{ $errors->has('stskerja') ? ' has-danger' : '' }}">
                                    <select class="form-control" name="stskerja">
                                        <option value="0">0 - Pending</option>
                                        <option value="1">1 - Karyawan Tetap</option>
                                        <option value="2">2 - Karyawan Kontrak</option>
                                        <option value="3">3 - Nonaktif</option>
                                    </select>
                                    @include('alerts.feedback', ['field' => 'stskerja'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Golongan') }}</label>
                                <div class="form-group {{ $errors->has('golongan') ? ' has-danger' : '' }}">
                                    <select class="form-control" name="golongan">
                                        @foreach($golongan as $g)
                                        <option value="{{ $g->id }}">{{ $g->golongan . ' (' . $g->workday .' hari)'}}</option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'golongan'])
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h3>Data Bank</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ _('Nama Bank') }}</label>
                                <div class="form-group {{ $errors->has('bank') ? ' has-danger' : '' }}">
                                    <input type="text" name="bank"
                                        class="form-control{{ $errors->has('bank') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('Nama Bank') }}"
                                        value="{{ old('bank') }}">
                                    @include('alerts.feedback', ['field' => 'bank'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('No. Rekening') }}</label>
                                <div class="form-group {{ $errors->has('rek') ? ' has-danger' : '' }}">
                                    <input type="text" name="rek"
                                        class="form-control{{ $errors->has('rek') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('No. Rekening') }}"
                                        value="{{ old('rek') }}">
                                    @include('alerts.feedback', ['field' => 'rek'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Atas Nama') }}</label>
                                <div class="form-group {{ $errors->has('anrek') ? ' has-danger' : '' }}">
                                    <input type="text" name="anrek"
                                        class="form-control{{ $errors->has('anrek') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('Atas Nama') }}"
                                        value="{{ old('anrek') }}">
                                    @include('alerts.feedback', ['field' => 'anrek'])
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h3>Pajak dan BPJS</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ _('NPWP') }}</label>
                                <div class="form-group {{ $errors->has('npwp') ? ' has-danger' : '' }}">
                                    <input type="text" name="npwp"
                                        class="form-control{{ $errors->has('npwp') ? ' is-invalid' : '' }}"
                                        placeholder="{{ _('NPWP') }}"
                                        value="{{ old('npwp') }}">
                                    @include('alerts.feedback', ['field' => 'npwp'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Status') }}</label>
                                <div class="form-group {{ $errors->has('stspajak') ? ' has-danger' : '' }}">
                                    <div class="input-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="stspajak" id="stspajak0" value="0" checked>
                                            <label class="form-check-label pl-1" for="stspajak0">Tidak Kawin</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="stspajak" id="stspajak1" value="1">
                                            <label class="form-check-label pl-1" for="stspajak1">Kawin</label>
                                        </div>
                                        @include('alerts.feedback', ['field' => 'stspajak'])
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Tanggungan') }}</label>
                                <div class="form-group {{ $errors->has('tanggungan') ? ' has-danger' : '' }}">
                                    <select class="form-control" name="tanggungan">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                    @include('alerts.feedback', ['field' => 'tanggungan'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ _('BPJS Kesehatan') }}</label>
                                <div class="form-group {{ $errors->has('bpjskes') ? ' has-danger' : '' }}">
                                    <div class="input-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bpjskes" id="bpjskes1" value="1">
                                            <label class="form-check-label pl-1" for="bpjskes0">Ya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bpjskes" id="bpjskes0" value="0"checked>
                                            <label class="form-check-label pl-1" for="bpjskes1">Tidak</label>
                                        </div>
                                        @include('alerts.feedback', ['field' => 'bpjskes'])
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('BPJS Tenaga Kerja') }}</label>
                                <div class="form-group {{ $errors->has('bpjstk') ? ' has-danger' : '' }}">
                                    <div class="input-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bpjstk" id="bpjstk1" value="1" >
                                            <label class="form-check-label pl-1" for="bpjstk0">Ya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="bpjstk" id="bpjstk0" value="0" checked>
                                            <label class="form-check-label pl-1" for="bpjstk1">Tidak</label>
                                        </div>
                                        @include('alerts.feedback', ['field' => 'bpjstk'])
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h3>Gaji</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ _('Gaji Pokok') }}</label>
                                <div class="form-group {{ $errors->has('gp') ? ' has-danger' : '' }}">
                                    <input type="text" name="gp"
                                        class="form-control{{ $errors->has('gp') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('Gaji Pokok') }}" 
                                        value="{{ old('gp') ?? 0}}">
                                    @include('alerts.feedback', ['field' => 'gp'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('T. Jabatan') }}</label>
                                <div class="form-group {{ $errors->has('t2jabatan') ? ' has-danger' : '' }}">
                                    <input type="text" name="t2jabatan"
                                        class="form-control{{ $errors->has('t2jabatan') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('T. Jabatan') }}" 
                                        value="{{ old('t2jabatan') ?? 0 }}">
                                    @include('alerts.feedback', ['field' => 't2jabatan'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('T. Masa Kerja') }}</label>
                                <div class="form-group {{ $errors->has('t2masa') ? ' has-danger' : '' }}">
                                    <input type="text" name="t2masa"
                                        class="form-control{{ $errors->has('t2masa') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('T. Masa Kerja') }}" 
                                        value="{{ old('t2masa') ?? 0 }}">
                                    @include('alerts.feedback', ['field' => 't3kerapian'])
                                </div>
                            </div>
                             <div class="w-100"></div>
                             <div class="col-md-3">
                                <label>{{ _('T. Kerapian') }}</label>
                                <div class="form-group {{ $errors->has('t3kerapian') ? ' has-danger' : '' }}">
                                    <input type="text" name="t3kerapian"
                                        class="form-control{{ $errors->has('t3kerapian') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('T. Kerapian') }}" 
                                        value="{{ old('t3kerapian') ?? 0 }}">
                                    @include('alerts.feedback', ['field' => 't3kerapian'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('T. Transport') }}</label>
                                <div class="form-group {{ $errors->has('t3transport') ? ' has-danger' : '' }}">
                                    <input type="text" name="t3transport"
                                        class="form-control{{ $errors->has('t3transport') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('T. Transport') }}" 
                                        value="{{ old('t3transport') ?? 0 }}">
                                    @include('alerts.feedback', ['field' => 't3transport'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('T. Kehadiran') }}</label>
                                <div class="form-group {{ $errors->has('t3kehadiran') ? ' has-danger' : '' }}">
                                    <input type="text" name="t3kehadiran"
                                        class="form-control{{ $errors->has('t3kehadiran') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('T. Kehadiran') }}" 
                                        value="{{ old('t3kehadiran') ?? 0 }}">
                                    @include('alerts.feedback', ['field' => 't3kehadiran'])
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-3">
                                <label>{{ _('Honorarium') }}</label>
                                <div class="form-group {{ $errors->has('honor') ? ' has-danger' : '' }}">
                                    <input type="text" name="honor"
                                        class="form-control{{ $errors->has('honor') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('Honorarium') }}" 
                                        value="{{ old('honor') ?? 0 }}">
                                    @include('alerts.feedback', ['field' => 'honor'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Dasar Upah BPJS Kesehatan') }}</label>
                                <div class="form-group {{ $errors->has('basebpjskes') ? ' has-danger' : '' }}">
                                    <input type="text" name="basebpjskes"
                                        class="form-control{{ $errors->has('basebpjskes') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('Dasar Upah BPJS Kesehatan') }}" 
                                        value="{{ old('basebpjskes') ?? 0 }}">
                                    @include('alerts.feedback', ['field' => 'basebpjskes'])
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>{{ _('Dasar Upah BPJS Tenaga Kerja') }}</label>
                                <div class="form-group {{ $errors->has('basebpjstk') ? ' has-danger' : '' }}">
                                    <input type="text" name="basebpjstk"
                                        class="form-control{{ $errors->has('basebpjstk') ? ' is-invalid' : '' }}" 
                                        placeholder="{{ _('Dasar Upah BPJS Tenaga Kerja') }}" 
                                        value="{{ old('basebpjstk') ?? 0 }}">
                                    @include('alerts.feedback', ['field' => 'basebpjstk'])
                                </div>
                            </div>
                        </div>
                        <hr>
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
                $("#kryForm input").prop("disabled", false);
                $("#kryForm button").prop("disabled", false);
                $("#kryForm select").prop("disabled", false);

                $("#kryForm #idTf").prop("disabled", true);
                $("#kryForm #searchKryBtn").prop("disabled", true);

                $("#kryForm").attr('action', "karyawan/tambah");
            }
            else if(actionVal == 2){
                $("#kryForm input").prop("disabled", true);
                $("#kryForm button").prop("disabled", true);
                $("#kryForm select").prop("disabled", true);

                $("#kryForm #searchKryBtn").prop("disabled", false);

                $("#kryForm").attr('action', "karyawan/display");
            }
            else if(actionVal == 3){
                $("#kryForm input").prop("disabled", false);
                $("#kryForm button").prop("disabled", false);
                $("#kryForm select").prop("disabled", false);

                $("#kryForm #idTf").prop("readonly", true);

                $("#kryForm").attr('action', "karyawan/edit");
            }
            else if(actionVal == 4){
                $("#kryForm input").prop("disabled", true);
                $("#kryForm button").prop("disabled", true);
                $("#kryForm select").prop("disabled", true);

                $("#kryForm #searchKryBtn").prop("disabled", false);
                $("#kryForm #submitBtn").prop("disabled", false);

                $("#kryForm").attr('action', "karyawan/hapus");
            }
        }

        $(".btn-select-kry").click(function(){
            var id = $(this).data("id");
            $.ajax({
                type:'GET',
                url:'/<?= $db ?>/karyawan/' + id,
                data:'_token = <?= csrf_token() ?>',
                success:function(data) {
                    setData(data);
               }
            });
        });

        function setData(data){
            var formID = "#kryForm";
            var k = jQuery.parseJSON(data);
            $(formID + " input:text[name='id']").val(k.id);
            $(formID + " input:text[name='idKry']").val(k.id_kry);
            $(formID + " input:text[name='nama']").val(k.nama);
            $(formID + " input:text[name='tmptlahir']").val(k.tempat_lahir);
            $(formID + " input[name='tgllahir']").val(k.tgl_lahir);
            $(formID + " input[name='jkel']").val([k.jenis_kelamin]);
            $(formID + " input:text[name='nik']").val(k.nik);
            $(formID + " input:text[name='nohp']").val(k.no_hp);
            $(formID + " input:text[name='alamat']").val(k.alamat);

            $(formID + " input:text[name='bank']").val(k.rek_bank);
            $(formID + " input:text[name='rek']").val(k.rek_no);
            $(formID + " input:text[name='anrek']").val(k.rek_nama);

            $(formID + " input:text[name='npwp']").val(k.npwp);
            $(formID + " input[name='stspajak']").val([k.pph_sts]);
            $(formID + " select[name='tanggungan']").val([k.pph_tg]).change();
            $(formID + " input[name='bpjskes']").val([k.bpjs_kes]);
            $(formID + " input[name='bpjstk']").val([k.bpjs_tk]);

            $(formID + " input[name='tglmulai']").val(k.tgl_mulai);
            $(formID + " input[name='tglakhir']").val(k.tgl_akhir);
            $(formID + " select[name='stskerja']").val([k.sts_kerja]).change();
            $(formID + " select[name='golongan']").val([k.gol]).change();
            
            $(formID + " input:text[name='gp']").val(k.gp);
            $(formID + " input:text[name='t2masa']").val(k.t2masa);
            $(formID + " input:text[name='t2jabatan']").val(k.t2jabatan);
            $(formID + " input:text[name='t3kerapian']").val(k.t3kerapian);
            $(formID + " input:text[name='t3transport']").val(k.t3transport);
            $(formID + " input:text[name='t3kehadiran']").val(k.t3kehadiran);
            $(formID + " input:text[name='honor']").val(k.honor);
            $(formID + " input:text[name='basebpjskes']").val(k.base_bpjskes);
            $(formID + " input:text[name='basebpjstk']").val(k.base_bpjstk);

            $("#createdAt").html(k.created_at);
            $("#updatedAt").html(k.updated_at);

            $('#searchKryModal').modal('hide');
        }

        function decodeHtml(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }
    </script>
@endpush
