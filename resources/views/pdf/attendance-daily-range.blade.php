<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 10px;
    color: #1a1a1a;
    background: #fff;
  }
  .header {
    text-align: center;
    border-bottom: 2.5px solid #1e3a5f;
    padding-bottom: 8px;
    margin-bottom: 12px;
  }
  .header .school-name {
    font-size: 15px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #1e3a5f;
  }
  .header .doc-title {
    font-size: 12px;
    font-weight: bold;
    margin-top: 4px;
    text-transform: uppercase;
    color: #2c5282;
  }
  .header .doc-sub {
    font-size: 9px;
    color: #4a5568;
    margin-top: 2px;
  }
  .info-table {
    width: 100%;
    margin-bottom: 10px;
    border-collapse: collapse;
  }
  .info-table td {
    padding: 2px 6px 2px 0;
    font-size: 9px;
    vertical-align: top;
    width: 33%;
  }
  .info-table .label { color: #4a5568; }
  .info-table .value { font-weight: bold; }
  table.att-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 12px;
    font-size: 9px;
  }
  table.att-table th {
    background-color: #1e3a5f;
    color: #fff;
    padding: 5px 4px;
    text-align: center;
    border: 0.5px solid #2d5986;
  }
  table.att-table th.col-name { text-align: left; padding-left: 6px; }
  table.att-table td {
    padding: 4px 3px;
    border: 0.5px solid #cbd5e0;
    text-align: center;
  }
  table.att-table td.col-no   { width: 24px; }
  table.att-table td.col-nis  { width: 50px; }
  table.att-table td.col-name { text-align: left; padding-left: 6px; }
  table.att-table tr:nth-child(even) td { background-color: #f7fafc; }
  .s-hadir { color: #276749; font-weight: bold; }
  .s-telat { color: #d69e2e; font-weight: bold; }
  .s-izin  { color: #744210; font-weight: bold; }
  .s-sakit { color: #2b6cb0; font-weight: bold; }
  .s-alpha { color: #c53030; font-weight: bold; }
  .day-header {
    font-size: 11px;
    font-weight: bold;
    color: #1e3a5f;
    margin-bottom: 6px;
    margin-top: 4px;
  }
  .recap-wrap {
    display: flex;
    gap: 12px;
    margin-bottom: 14px;
    justify-content: center;
  }
  .recap-box {
    border: 1px solid #bee3f8;
    border-radius: 4px;
    padding: 6px 12px;
    background: #ebf8ff;
    min-width: 80px;
    text-align: center;
  }
  .recap-box .rb-label { font-size: 8px; color: #4a5568; }
  .recap-box .rb-val   { font-size: 16px; font-weight: bold; color: #2b6cb0; }
  .recap-box.hadir .rb-val { color: #276749; }
  .recap-box.telat .rb-val { color: #d69e2e; }
  .recap-box.izin  .rb-val { color: #744210; }
  .recap-box.sakit .rb-val { color: #2b6cb0; }
  .recap-box.alpha .rb-val { color: #c53030; }
  .footer {
    margin-top: 16px;
    display: flex;
    justify-content: space-between;
    font-size: 9px;
  }
  .ttd-box { text-align: center; }
  .ttd-box .ttd-role  { font-weight: bold; margin-bottom: 42px; }
  .ttd-box .ttd-line  { border-top: 1px solid #1a1a1a; width: 140px; margin: 0 auto; }
  .ttd-box .ttd-name  { font-weight: bold; margin-top: 2px; }
  .ttd-box .ttd-nip   { font-size: 8px; color: #4a5568; }
  .page-break { page-break-after: always; }
  .generated { font-size: 7px; color: #a0aec0; text-align: right; margin-top: 6px; }
  .legend { font-size: 8px; color: #4a5568; margin-bottom: 8px; }
  .legend span { margin-right: 12px; }
</style>
</head>
<body>

@foreach($days as $day)
  @php
    $date     = $day['date'];
    $rows     = $day['rows'];
    $summary  = $day['summary'];
  @endphp

  <div class="header">
    <div class="school-name">SMK COMPUTER SCIENCE</div>
    <div class="doc-title">Absensi Harian Siswa</div>
    <div class="doc-sub">{{ $date->isoFormat('dddd, D MMMM Y') }}</div>
  </div>

  <table class="info-table">
    <tr>
      <td><span class="label">Kelas</span> : <span class="value">{{ $classroom->label }}</span></td>
      <td><span class="label">Wali Kelas</span> : <span class="value">{{ $classroom->waliKelas?->user->name ?? '-' }}</span></td>
      <td><span class="label">Jumlah Siswa</span> : <span class="value">{{ $rows->count() }} orang</span></td>
    </tr>
    <tr>
      <td><span class="label">Jurusan</span> : <span class="value">{{ $classroom->major->major_name }}</span></td>
      <td><span class="label">Tahun Ajaran</span> : <span class="value">{{ $classroom->academic_year }}</span></td>
      <td><span class="label">Cetak</span> : <span class="value">{{ $generatedAt->isoFormat('D MMMM Y, HH:mm') }}</span></td>
    </tr>
  </table>

  <table class="att-table">
    <thead>
      <tr>
        <th>No</th>
        <th>NIS</th>
        <th class="col-name">Nama Siswa</th>
        <th>Jam Masuk</th>
        <th>Jam Pulang</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $row)
      <tr>
        <td class="col-no">{{ $row['no'] }}</td>
        <td class="col-nis">{{ $row['nis'] }}</td>
        <td class="col-name">{{ $row['name'] }}</td>
        <td>{{ $row['scan_in'] }}</td>
        <td>{{ $row['scan_out'] }}</td>
        <td>
          @if($row['late'])
            <span class="s-telat">Terlambat</span>
          @elseif($row['status'] === 'hadir')
            <span class="s-hadir">Hadir</span>
          @elseif($row['status'] === 'telat')
            <span class="s-telat">Telat</span>
          @elseif($row['status'] === 'izin')
            <span class="s-izin">Izin</span>
          @elseif($row['status'] === 'sakit')
            <span class="s-sakit">Sakit</span>
          @else
            <span class="s-alpha">Alpha</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="recap-wrap">
    <div class="recap-box hadir">
      <div class="rb-label">Hadir</div>
      <div class="rb-val">{{ $summary['hadir'] }}</div>
    </div>
    <div class="recap-box telat">
      <div class="rb-label">Telat</div>
      <div class="rb-val">{{ $summary['telat'] }}</div>
    </div>
    <div class="recap-box izin">
      <div class="rb-label">Izin</div>
      <div class="rb-val">{{ $summary['izin'] }}</div>
    </div>
    <div class="recap-box sakit">
      <div class="rb-label">Sakit</div>
      <div class="rb-val">{{ $summary['sakit'] }}</div>
    </div>
    <div class="recap-box alpha">
      <div class="rb-label">Alpha</div>
      <div class="rb-val">{{ $summary['alpha'] }}</div>
    </div>
  </div>

  @if(!$loop->last)
  <div class="page-break"></div>
  @endif
@endforeach

<table style="width:100%; border-collapse:collapse; margin-top:8px;">
  <tr>
    <td style="width:50%; text-align:center; vertical-align:top; font-size:9px;">
      <div>Mengetahui,<br>Kepala Sekolah</div>
      <div style="margin-top:40px; border-top:1px solid #1a1a1a; width:150px; margin-left:auto; margin-right:auto;"></div>
      <div style="font-weight:bold; margin-top:3px;">______________________</div>
    </td>
    <td style="width:50%; text-align:center; vertical-align:top; font-size:9px;">
      <div>Wali Kelas {{ $classroom->label }}</div>
      <div style="margin-top:40px; border-top:1px solid #1a1a1a; width:150px; margin-left:auto; margin-right:auto;"></div>
      <div style="font-weight:bold; margin-top:3px;">{{ $classroom->waliKelas?->user->name ?? '______________________' }}</div>
      @if($classroom->waliKelas?->nip)
      <div style="font-size:8px; color:#4a5568;">NIP. {{ $classroom->waliKelas->nip }}</div>
      @endif
    </td>
  </tr>
</table>

<div class="generated">
  Dicetak oleh sistem EduNexa &bull; {{ $generatedAt->isoFormat('D MMMM Y, HH:mm') }} WIB
</div>

</body>
</html>
