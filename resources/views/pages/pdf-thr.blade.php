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
        <img class="w-100" style="margin-left: -8px" src="{{public_path('white/img/logo-color.jpg')}}">
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
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Hadir</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>WFH</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>Dinas</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>Sakit (Ket. Dokter)</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>Cuti Pribadi</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>Cuti Bersama</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>Cuti Khusus</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>Sakit (Tanpa Ket. Dokter)</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>Izin</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>Alpa</td>
            <td class="text-right">-</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td></td>
          </tr>
        </table>
      </td>
      <td width="2.5%">{{-- space --}}</td>
      <td width="35%" class="vertical-top" style="height:240px;">
        <div class="div-header">PENDAPATAN</div>
        <table width="100%" border="0">
          <tr>
            <td width="60%">Tunjangan Hari Raya</td>
            <td width="5%">Rp</td>
            <td width="35%" class="text-right">{{\App\Helpers\AppHelper::instance()->mf($p->thr)}}</td>
          </tr>
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
            <td class="div-header text-right" width="35%">{{\App\Helpers\AppHelper::instance()->mf($p->thr)}}</td>
          </tr>
        </table>
      </td>
      <td>{{-- space --}}</td>
      <td class="vertical-top">
        <table width="100%">
          <tr>
            <td class="div-header" width="60%">Total Pemotongan</td>
            <td class="div-header" width="5%">Rp</td>
            <td class="div-header text-right" width="35%">{{\App\Helpers\AppHelper::instance()->mf(0)}}</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td class="div-header border-left">Diterima</td>
            <td class="div-header">Rp</td>
            <td class="div-header border-right text-right">{{\App\Helpers\AppHelper::instance()->mf($p->thr)}}</td>
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