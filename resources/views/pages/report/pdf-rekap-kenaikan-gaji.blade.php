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
    .w-50{
      width: 50%;
    }
    .pl-16{
      padding-left: 16px;
    }
    .p-1{
      padding: 2px;
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
    .border{
      border: 1px solid;
    }
    .grid{
      display: grid;
    }
    .left{
      grid-column: 1;
    }
    .right{
      grid-column: 2;
    }
    .m-0{
      margin: 0;
    }
    .mt-2{
      margin-top: 24px;
    }
    .mb-0{
      margin-bottom: 0;
    }
    ul > li {
      margin-left: -15px;
      padding: 0 6px;
    }
  </style>
</head>
<body>
  @if($db == 'sgn')
  <h4 class="m-0">PT. SILI GADAI NUSANTARA</h4>
  <h4 class="m-0">Jl. Raya Menganti Babatan No. 11A Kav. 5, Surabaya</h4>
  @else
  <h4 class="m-0">PT. SILI CORP BANK</h4>
  <h4 class="m-0">Jl. Raya Darmo Permai Selatan A-9, Surabaya</h4>
  @endif
  <h4 class="border w-100 p-1 text-center">REKAP KENAIKAN GAJI</h4>
  <table class="w-100">
    <tr>
      <td width="10%">ID</td>
      <td width="40%">: {{ $k->id_kry }}
      <td width="10%">NIK</td>
      <td width="40%">: {{ $k->nik }}
    </tr>
    <tr>
      <td>Nama</td>
      <td>: {{ $k->nama }}</td>
      <td>NPWP</td>
      <td>: {{ \App\Helpers\AppHelper::instance()->npwpf($k->npwp) }}</td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td>: {{ $k->alamat }}</td>
      <td>Mulai Kerja</td>
      <td>: {{ $k->tgl_mulai }}</td>
    </tr>
    <tr>
      <td>Golongan</td>
      <td>: {{ $k->golongan . ' (' . $k->workday . ' hari kerja)' }}</td>
      <td>Lama Kerja</td>
      <td>: {{ $k->lama_kerja }} bulan</td>
    </tr>
  </table>
  <h4 class="border w-100 p-1 mt-2 text-center">PARAMETER GAJI</h4>
  <table class="w-100" border="1">
    <thead>
      <tr>
        <th>NO.</th>
        <th>PARAMETER GAJI</th>
        <th>NOMINAL {{ $currentY }}</th>
        <th>NOMINAL {{ $nextY }}</th>
        <th>KETERANGAN</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-center">1</td>
        <td>Gaji Pokok</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->param_gp) }}</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->gp) }}</td>
        <td>per bulan</td>
      </tr>
      <tr>
        <td class="text-center">2</td>
        <td>Honor</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->param_honor) }}</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->honor) }}</td>
        <td>per bulan</td>
      </tr>
      <tr>
        <td class="text-center">3</td>
        <td>Tunjangan Tetap Jabatan</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->param_t2jabatan) }}</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->t2jabatan) }}</td>
        <td>per bulan</td>
      </tr>
      <tr>
        <td class="text-center">4</td>
        <td>Tunjangan Tetap Masa Kerja</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->param_t2masa) }}</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->t2masa) }}</td>
        <td>per bulan</td>
      </tr>
      <tr>
        <td class="text-center">5</td>
        <td>Tunjangan Tidak Tetap Kehadiran</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->param_t3kehadiran) }}</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->t3kehadiran) }}</td>
        <td>per hari kerja</td>
      </tr>
      <tr>
        <td class="text-center">6</td>
        <td>Tunjangan Tidak Tetap Transport</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->param_t3transport) }}</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->t3transport) }}</td>
        <td>per hari kerja</td>
      </tr>
      <tr>
        <td class="text-center">7</td>
        <td>Tunjangan Tidak Tetap Kerapian</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->param_t3kerapian) }}</td>
        <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($k->t3kerapian) }}</td>
        <td>per hari kerja</td>
      </tr>
    </tbody>
  </table>
  <h4 class="border w-100 p-1 mt-2 mb-0 text-center">PERBANDINGAN GAJI</h4>
  <table class="w-100">
    <tr valign="top">
      <td width="50%">
        <h4 class="text-center mt-0">{{ $currentY }}</h4>
        <table class="w-100" border="1">
          <thead>
            <tr>
              <th>PERIODE</th>
              <th>HARI KERJA</th>
              <th>TOTAL GAJI</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pcCurrent as $p)
            <tr class="text-center">
              <td>{{ $p['per'] }}</td>
              <td>{{ $p['harikerja'] }}</td>
              <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($p['pc']) }}</td>
            </tr>
            @endforeach
            <tr class="text-center">
              <td><b>TOTAL</b></td>
              <td><b>{{ $currentTotalHariKerja }}</b></td>
              <td class="text-right"><b>{{ \App\Helpers\AppHelper::instance()->mf($currentTotalPc) }}</b></td>
            </tr>
          </tbody>
        </table>
        <p>* Menggunakan parameter gaji bulan Desember {{ $currentY }} dan dihitung dengan asumsi kehadiran penuh.</p>
      </td>
      <td width="50%">
        <h4 class="text-center mt-0">{{ $nextY }}</h4>
        <table class="w-100" border="1">
          <thead>
            <tr>
              <th>PERIODE</th>
              <th>HARI KERJA</th>
              <th>TOTAL GAJI</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pcNext as $p)
            <tr class="text-center">
              <td>{{ $p['per'] }}</td>
              <td>{{ $p['harikerja'] }}</td>
              <td class="text-right">{{ \App\Helpers\AppHelper::instance()->mf($p['pc']) }}</td>
            </tr>
            @endforeach
            <tr class="text-center">
              <td><b>TOTAL</b></td>
              <td><b>{{ $nextTotalHariKerja }}</b></td>
              <td class="text-right"><b>{{ \App\Helpers\AppHelper::instance()->mf($nextTotalPc) }}</b></td>
            </tr>
          </tbody>
        </table>
        <p>* Perhitungan hari kerja berdasarkan SKB Libur Nasional dari pemerintah.</p>
      </td>
    </tr>
  </table>
  <table>
    <tr>
      <td width="10%">Kenaikan Gaji Setahun</td>
      <td>= {{ \App\Helpers\AppHelper::instance()->mf($nextTotalPc) }} - {{ \App\Helpers\AppHelper::instance()->mf($currentTotalPc) }}</td>
    </tr>
    <tr>
      <td></td>
      <td>= {{ \App\Helpers\AppHelper::instance()->mf($nextTotalPc - $currentTotalPc) }}</td>
    </tr>
    <tr>
      <td>Persentase Kenaikan</td>
      <td>= {{ \App\Helpers\AppHelper::instance()->mf($nextTotalPc - $currentTotalPc) }} / {{ \App\Helpers\AppHelper::instance()->mf($currentTotalPc) }} x 100%</td>
    </tr>
    <tr>
      <td></td>
      <td><b>= {{ round(($nextTotalPc - $currentTotalPc) / $currentTotalPc * 100, 2) }}%</b></td>
    </tr>
  </table>
  <hr class="mt-2">
  <table>
    <tr valign="top">
      <td width="50%">
        <div>Ketentuan-ketentuan terkait perhitungan gaji:</div>
        <ul>
          <li>Tunjangan Tidak Tetap dihitung berdasarkan absensi pada hari kerja.</li>
          <li>Hadir, Cuti, Cuti Bersama, Cuti Khusus, Sakit dengan Keterangan Dokter, dan Dinas diperhitungkan kepada Tunjangan Tidak Tetap.</li>
          <li>Izin, Sakit tanpa Keterangan Dokter, dan Tanpa Keterangan tidak diperhitungan kepada Tunjangan Tidak Tetap.</li>
          <li>Setiap Izin, Sakit tanpa Keterangan Dokter, dan Tidak Masuk tanpa Keterangan juga akan mengurangi besar Gaji Pokok dan Tunjangan Tetap yang diterima sebesar 1/{{ $k->workday }} perharinya.</li>
          @if($db == 'scb')
          <li>Absensi untuk perhitungan gaji menggunakan absensi tanggal 25 s/d tanggal 24 bulan berikutnya.</li>
          <li>Gaji ditransfer setiap tanggal 25 dan akan ditransfer lebih awal apabila tanggal 25 bertepatan dengan hari libur bank.</li>
          @else
          <li>Absensi untuk perhitungan gaji menggunakan absensi tanggal 1 s/d tanggal 31 pada bulan gajian.</li>
          <li>Gaji ditransfer setiap h-1 akhir bulan dan pada akhir bulan. Apabila tanggal-tanggal tersebut bertepatan dengan hari libur bank, maka akan ditransfer lebih awal.</li>
          @endif
        </ul>
        @if($db == 'scb' && $k->paidleavediv == 25)
        <div>Khusus BPR yang masuk Sabtu:</div>
        <ul>
          <li>Perhitungan dengan menggunakan hari kerja 21 hari hanya untuk pencatatan administratif.</li>
          <li>Perhitungan gaji sebulan sudah mencakup hari kerja yang jatuh di hari Sabtu.</li>
          <li>Setiap Izin, Sakit tanpa Keterangan Dokter, dan Tidak Masuk tanpa Keterangan akan mengurangi besar gaji sebulan yang seharusnya diterima (sesuai dengan jumlah hari kerja pada bulan tersebut) sebesar 1/{{ $k->paidleavediv }}.</li>
        </ul>
        @endif
      </td>
      <td width="50%">
        <div>Ketentuan-ketentuan terkait Cuti Pribadi:</div>
        <ul>
          <li>Jatah cuti dalam setahun adalah 12.</li>
          <li>Jatah cuti pribadi dalam setahun adalah 12 dikurangi jumlah cuti bersama pada tahun tersebut.</li>
          @if($db == 'scb')
          <li>Cuti bersama yang dimaksud adalah sesuai dengan ketentuan pemerintah pada SKB Libur Nasional.</li>
          @else
          <li>Cuti bersama yang dimaksud adalah 1 hari sesudah dan sebelum Tahun Baru Masehi dan 1 hari sesudah dan sebelum Hari Raya Idul Fitri.
          @endif
          <li>Bagi yang akan menggunakan cuti pribadi wajib memberitahukan kepada HRD minimal 1 minggu sebelumnya.</li>
          <li>Jatah cuti yang tidak digunakan akan dikompensasikan pada akhir tahun dengan setiap cuti bernilai 1/{{ $k->paidleavediv }} dari total gaji 1 bulan ({{ $k->workday }} hari kerja)</li>
        </ul>
        @if($db == 'scb' && $k->paidleavediv == 25)
        <div>Khusus BPR yang masuk Sabtu:</div>
        <ul>
          <li>Cuti pribadi yang digunakan pada hari Sabtu akan mengurangi jatah cuti pribadi.</li>
          <li>Cuti bersama BEPI yang jatuh pada hari Sabtu tidak mengurangi jatah cuti pribadi.</li>
        </ul>
        @endif
      </td>
    </tr>
  </table>
  <div style="height: 50px; width: 100px; position: absolute; bottom: 20px; right: 0;" class="border">
    
  </div>
</body>
</html>