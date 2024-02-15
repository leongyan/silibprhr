<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <style type="text/css">
    @page {
      margin: 8px 16px;
    }
    body {
      margin: 8px 16px;
      font-family: 'Helvetica';
      font-size: 10px;
    }
    .text-right{
      text-align: right;
    }
    .text-center{
      text-align: center;
    }
    .vertical-bottom{
      vertical-align: bottom;
    }
    .vertical-top{
      vertical-align: top;
    }
    .w-100{
      width: 100%;
    }
    .pl-16{
      padding-left: 16px;
    }
    .bold{
      font-weight: bold;
    }
    table{
      border-collapse: collapse;
    }
    td{
      height: 13px;
      padding-left: 4px;
      padding-right: 4px;
    }
    .px-8{
      padding-left: 8px;
      padding-right: 8px;
    }
    .div-header{
      font-weight: bold;
      padding: 4px 4px;
      margin: 4px 0;
      border-top: 1px solid;
      border-bottom: 1px solid;
    }
    .border-left{
      border-left: 1px solid;
    }
    .border-right{
      border-right: 1px solid;
    }
  </style>
</head>
<body>
  <table width="100%" style="margin-top:-8px">
    <tr>
      <td width="8%" rowspan="2">
        <img class="w-100" style="margin-left: -8px" src="{{asset('white/img/logo-color.png')}}">
		<!-- <img class="w-100" style="margin-left: -8px" src="./logo-color.jpg"> -->
      </td>
      <td height="50px" class="vertical-bottom">
        @if($db == 'sgn')
        <b>SILI GADAI NUSANTARA</b>
        @elseif($db == 'scb')
        <b>SILI CORP BANK</b>
        @endif
      </td>
      <td width="25%" class="vertical-bottom text-right">
        <b>PRIVATE & CONFIDENTIAL</b>
      </td>
    </tr>
    <tr>
      @if($db == 'sgn')
      <td class="vertical-top">Jl. Raya Menganti Babatan No. 11A Kav. 5, Surabaya</td>
      @elseif($db == 'scb')
      <td class="vertical-top">Jl. Raya Darmo Permai Selatan A-9, Surabaya</td>
      @endif
    </tr>
  </table>

  <table width="100%" border="0">
    <tr>
      <td width="10%" class="bold px-8">
        Bulan
      </td>
      <td width="15%">
        {{$p->date->format('F')}}
      </td>
      <td width="2.5%">{{-- space --}}</td>
      <td width="10%" class="bold px-8">
        NIK
      </td>
      <td width="25%">
        {{$p->id_prefix . sprintf('%03d', $p->id_kry)}}
      </td>
      <td width="2.5%">{{-- space --}}</td>
      <td width="10%" class="bold px-8">
        Jabatan
      </td>
      <td width="25%">
        {{$p->jabatan}}
      </td>
    </tr>
    <tr>
      <td class="bold px-8">Tahun</td>
      <td>{{$p->date->format('Y')}}</td>
      <td>{{-- space --}}</td>
      <td class="bold px-8">Nama</td>
      <td>{{$p->nama}}</td>
      <td>{{-- space --}}</td>
      <td class="bold px-8">NPWP</td>
      <td>{{\App\Helpers\AppHelper::instance()->npwpf($p->npwp)}}</td>
    </tr>
    <tr>
      
    </tr>
  </table>

  <table width="100%" border="0">
    <tr>
      <td width="25%" class="vertical-top" rowspan="3">
        <div class="div-header">DATA ABSENSI</div>
        <table width="100%" border="0">
          <tr>
            <td width="85%">Hari Kerja</td>
            <td class="text-right">{{$weekdays}}</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Hadir</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att11 ?? 0)}}</td>
          </tr>
          <tr>
            <td>WFH</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att12 ?? 0)}}</td>
          </tr>
          <tr>
            <td>Dinas</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att13 ?? 0)}}</td>
          </tr>
          <tr>
            <td>Sakit (Ket. Dokter)</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att21 ?? 0)}}</td>
          </tr>
          <tr>
            <td>Cuti Pribadi</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att22 ?? 0)}}</td>
          </tr>
          <tr>
            <td>Cuti Bersama</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att23 ?? 0)}}</td>
          </tr>
          <tr>
            <td>Cuti Khusus</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att24 ?? 0)}}</td>
          </tr>
          <tr>
            <td>Sakit (Tanpa Ket. Dokter)</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att31 ?? 0)}}</td>
          </tr>
          <tr>
            <td>Izin</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att32 ?? 0)}}</td>
          </tr>
          <tr>
            <td>Alpa</td>
            <td class="text-right">{{\App\Helpers\AppHelper::instance()->quantityf($att->att33 ?? 0)}}</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          @if(!str_contains($p->jabatan, 'Komisaris'))
          <tr>
            <td>Jatah cuti tahun ini</td>
            <td class="text-right">{{$p->jatah_cuti}}</td><
          </tr>
          <tr>
            <td>Cuti bersama tahun ini</td>
            <td class="text-right">{{$p->cuti_bersama}}</td>
          </tr>
          <tr>
            <td>Cuti pribadi dipakai</td>
            <td class="text-right">{{$p->cuti_terpakai}}</td>
          </tr>
          <tr>
            <td>Sisa Cuti</td>
            <td class="text-right">{{$p->sisa_cuti}}</td>
          </tr>
          @endif
        </table>
      </td>
      <td width="2.5%">{{-- space --}}</td>
      <td width="35%" class="vertical-top" style="height:240px;">
        <div class="div-header">PENDAPATAN</div>
        <table width="100%" border="0">
          @if($p->in_gp != 0)
          <tr>
            <td width="60%">Gaji Pokok</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_gp)}}</td>
          </tr>
          @endif
          @if($p->in_honor != 0)
          <tr>
            <td width="60%">Honorarium</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_honor)}}</td>
          </tr>
          @endif
          @if($p->in_t2jabatan != 0)
          <tr>
            <td width="60%">T. Jabatan</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_t2jabatan)}}</td>
          </tr>
          @endif
          @if($p->in_t2masa != 0)
          <tr>
            <td width="60%">T. Masa Kerja</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_t2masa)}}</td>
          </tr>
          @endif
          @if($p->in_t3kerapian != 0)
          <tr>
            <td width="60%">T. Kerapian</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_t3kerapian)}}</td>
          </tr>
          @endif
          @if($p->in_t3transport != 0)
          <tr>
            <td width="60%">T. Transport</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_t3transport)}}</td>
          </tr>
          @endif
          @if($p->in_t3kehadiran != 0)
          <tr>
            <td width="60%">T. Kehadiran</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_t3kehadiran)}}</td>
          </tr>
          @endif
          {{-- @if($p->in_pph21 != 0)
          <tr>
            <td width="60%">T. PPh 21</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_pph21)}}</td>
          </tr>
          @endif --}}
          @if($p->in_bpjskes != 0)
          <tr>
            <td width="60%">BPJS Kesehatan Prs</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjskes)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjht != 0)
          <tr>
            <td width="60%">BPJS JHT Prs</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjht)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjkk != 0)
          <tr>
            <td width="60%">BPJS JKK</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjkk)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjkm != 0)
          <tr>
            <td width="60%">BPJS JKM</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjkm)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjkp != 0)
          <tr>
            <td width="60%">BPJS JKM</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjkp)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjp != 0)
          <tr>
            <td width="60%">BPJS JP Prs</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjp)}}</td>
          </tr>
          @endif
          @if($p->in_lain != 0)
          <tr>
            <td width="60%">Lainnya</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_lain)}}</td>
          </tr>
          @endif
          <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
          </tr>
        </table>
      </td>
      <td width="2.5%">{{-- space --}}</td>
      <td width="35%" class="vertical-top">
        <div class="div-header">PEMOTONGAN</div>
        <table width="100%" border="0">
          {{-- @if($p->in_pph21 != 0)
          <tr>
            <td width="60%">T. PPh 21</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_pph21)}}</td>
          </tr>
          @endif --}}
          @if($p->in_bpjskes != 0)
          <tr>
            <td width="60%">BPJS Kesehatan Prs</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjskes)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjht != 0)
          <tr>
            <td width="60%">BPJS JHT Prs</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjht)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjkk != 0)
          <tr>
            <td width="60%">BPJS JKK</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjkk)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjkm != 0)
          <tr>
            <td width="60%">BPJS JKM</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjkm)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjkp != 0)
          <tr>
            <td width="60%">BPJS JKM</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjkp)}}</td>
          </tr>
          @endif
          @if($p->in_bpjsjp != 0)
          <tr>
            <td width="60%">BPJS JP Prs</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in_bpjsjp)}}</td>
          </tr>
          @endif
          @if($p->out_bpjskes != 0)
          <tr>
            <td width="60%">BPJS Kesehatan Kry</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->out_bpjskes)}}</td>
          </tr>
          @endif
          @if($p->out_bpjsjht != 0)
          <tr>
            <td width="60%">BPJS JHT Kry</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->out_bpjsjht)}}</td>
          </tr>
          @endif
          @if($p->out_bpjsjp != 0)
          <tr>
            <td width="60%">BPJS JP Kry</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->out_bpjsjp)}}</td>
          </tr>
          @endif
          @if($p->out_lain != 0)
          <tr>
            <td width="60%">Lainnya</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->out_lain)}}</td>
          </tr>
          @endif
          <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>{{-- space --}}</td>
      <td class="vertical-top">
        <table width="100%">
          <tr>
            <td class="div-header" width="60%">Total Pendapatan</td>
            <td class="div-header" width="5%">Rp</td>
            <td class="div-header text-right" width="35%">{{\App\Helpers\AppHelper::instance()->mf($p->in)}}</td>
          </tr>
        </table>
      </td>
      <td>{{-- space --}}</td>
      <td class="vertical-top">
        <table width="100%">
          <tr>
            <td class="div-header" width="60%">Total Pemotongan</td>
            <td class="div-header" width="5%">Rp</td>
            <td class="div-header text-right" width="35%">{{\App\Helpers\AppHelper::instance()->mf($p->out)}}</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td class="div-header border-left">Take Home Pay</td>
            <td class="div-header">Rp</td>
            <td class="div-header border-right text-right">{{\App\Helpers\AppHelper::instance()->mf($p->in - $p->out)}}</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>{{-- space --}}</td>
      <td class="vertical-top">
        <div>Ditransfer ke:</div>
        <div>{{ $p->rek_bank . ' ' . $p->rek_no }}</div>
        <div>a/n {{ $p->rek_nama }}</div>
      </td>
      <td>{{-- space --}}</td>
      <td class="vertical-top text-center">
        <div>Surabaya, {{$p->date->format('d F Y')}}</div>
        <div>Diterima oleh,</div>
        <div style="height:48px"></div>
        <div>{{$p->nama}}</div>
      </td>
    </tr>
  </table>
  
</body>
</html>