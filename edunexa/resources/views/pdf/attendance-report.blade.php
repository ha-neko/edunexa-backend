<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 8.5px;
    color: #1a1a1a;
    background: #fff;
  }

  /* ── HEADER ── */
  .header {
    text-align: center;
    border-bottom: 2.5px solid #1e3a5f;
    padding-bottom: 8px;
    margin-bottom: 10px;
  }
  .header .school-name {
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #1e3a5f;
  }
  .header .doc-title {
    font-size: 11px;
    font-weight: bold;
    margin-top: 3px;
    text-transform: uppercase;
    color: #2c5282;
  }
  .header .doc-sub {
    font-size: 8.5px;
    color: #4a5568;
    margin-top: 2px;
  }

  /* ── INFO BLOCK ── */
  .info-table {
    width: 100%;
    margin-bottom: 10px;
    border-collapse: collapse;
  }
  .info-table td {
    padding: 2px 6px 2px 0;
    font-size: 8.5px;
    vertical-align: top;
    width: 33%;
  }
  .info-table .label { color: #4a5568; }
  .info-table .value { font-weight: bold; color: #1a1a1a; }
  .info-table .colon { width: 8px; color: #4a5568; }

  /* ── ABSENSI TABLE ── */
  .att-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    font-size: 7.8px;
  }
  .att-table th {
    background-color: #1e3a5f;
    color: #fff;
    padding: 4px 3px;
    text-align: center;
    border: 0.5px solid #2d5986;
    font-size: 7.5px;
  }
  .att-table th.col-name { text-align: left; padding-left: 5px; }
  .att-table td {
    padding: 3px 2px;
    border: 0.5px solid #cbd5e0;
    text-align: center;
    vertical-align: middle;
  }
  .att-table td.col-no   { width: 18px; font-size: 7px; }
  .att-table td.col-nis  { width: 42px; font-size: 7.5px; }
  .att-table td.col-name { text-align: left; padding-left: 5px; min-width: 100px; }
  .att-table td.col-sum  { font-weight: bold; font-size: 8px; }

  .att-table tr:nth-child(even) td { background-color: #f7fafc; }
  .att-table tr:hover td { background-color: #ebf4ff; }

  /* ── STATUS BADGES ── */
  .s-hadir { color: #276749; font-weight: bold; }
  .s-izin  { color: #744210; font-weight: bold; }
  .s-sakit { color: #2b6cb0; font-weight: bold; }
  .s-alpha { color: #c53030; font-weight: bold; }
  .s-late  { color: #d69e2e; font-weight: bold; font-style: italic; }
  .s-none  { color: #a0aec0; font-size: 7px; }

  /* ── SUMMARY ROW ── */
  .sum-row td {
    background-color: #ebf8ff !important;
    font-weight: bold;
    border-top: 1.5px solid #2b6cb0;
    font-size: 8px;
  }

  /* ── REKAP BAWAH ── */
  .recap-wrap {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
  }
  .recap-box {
    border: 1px solid #bee3f8;
    border-radius: 4px;
    padding: 6px 10px;
    background: #ebf8ff;
    min-width: 90px;
  }
  .recap-box .rb-label { font-size: 7px; color: #4a5568; }
  .recap-box .rb-val   { font-size: 14px; font-weight: bold; color: #2b6cb0; }
  .recap-box.hadir .rb-val { color: #276749; }
  .recap-box.izin  .rb-val { color: #744210; }
  .recap-box.sakit .rb-val { color: #2b6cb0; }
  .recap-box.alpha .rb-val { color: #c53030; }

  /* ── LEGEND ── */
  .legend {
    font-size: 7.5px;
    color: #4a5568;
    margin-bottom: 8px;
  }
  .legend span { margin-right: 10px; }

  /* ── FOOTER / TTAG ── */
  .footer {
    margin-top: 16px;
    display: flex;
    justify-content: space-between;
    font-size: 8px;
  }
  .ttd-box { text-align: center; }
  .ttd-box .ttd-role  { font-weight: bold; margin-bottom: 42px; }
  .ttd-box .ttd-line  { border-top: 1px solid #1a1a1a; width: 140px; margin: 0 auto; }
  .ttd-box .ttd-name  { font-weight: bold; margin-top: 2px; }
  .ttd-box .ttd-nip   { font-size: 7.5px; color: #4a5568; }

  .page-break { page-break-after: always; }

  .generated { font-size: 7px; color: #a0aec0; text-align: right; margin-top: 6px; }

  /* date header col */
  .th-date { font-size: 6.5px; }
  .th-date-day { display: block; font-size: 6px; color: #bee3f8; }
</style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{--  HEADER                                                        --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div class="header">
  <div class="school-name">SMK COMPUTER SCIENCE</div>
  <div class="doc-title">Rekap Absensi Siswa</div>
  <div class="doc-sub">
    Periode: {{ $dateFrom->isoFormat('D MMMM Y') }} — {{ $dateTo->isoFormat('D MMMM Y') }}
  </div>
</div>

{{-- ── INFO KELAS ──────────────────────────────────────────────── --}}
<table class="info-table">
  <tr>
    <td>
      <span class="label">Kelas</span><span class="colon"> : </span>
      <span class="value">{{ $classroom->label }}</span>
    </td>
    <td>
      <span class="label">Wali Kelas</span><span class="colon"> : </span>
      <span class="value">{{ $classroom->waliKelas?->user->name ?? '-' }}</span>
    </td>
    <td>
      <span class="label">Jumlah Siswa</span><span class="colon"> : </span>
      <span class="value">{{ $rows->count() }} orang</span>
    </td>
  </tr>
  <tr>
    <td>
      <span class="label">Jurusan</span><span class="colon"> : </span>
      <span class="value">{{ $classroom->major->major_name }}</span>
    </td>
    <td>
      <span class="label">Tahun Ajaran</span><span class="colon"> : </span>
      <span class="value">{{ $classroom->academic_year }}</span>
    </td>
    <td>
      <span class="label">Hari Efektif</span><span class="colon"> : </span>
      <span class="value">{{ $dates->count() }} hari</span>
    </td>
  </tr>
</table>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{--  TABEL ABSENSI                                                 --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<table class="att-table">
  <thead>
    <tr>
      <th rowspan="2" style="width:18px">No</th>
      <th rowspan="2" style="width:42px">NIS</th>
      <th rowspan="2" class="col-name">Nama Siswa</th>
      {{-- Kolom tanggal --}}
      @foreach($dates as $date)
      <th class="th-date" style="width:18px">
        {{ $date->format('d') }}
        <span class="th-date-day">{{ $date->isoFormat('dd') }}</span>
      </th>
      @endforeach
      {{-- Rekap --}}
      <th style="width:20px; background:#2d6a4f">H</th>
      <th style="width:20px; background:#7b4f12">I</th>
      <th style="width:20px; background:#1d4e89">S</th>
      <th style="width:20px; background:#8b1a1a">A</th>
    </tr>
    <tr>
      @foreach($dates as $date)
      <th class="th-date" style="font-size:6px; font-weight:normal; background:#2c5282">
        {{ $date->format('M') }}
      </th>
      @endforeach
      <th style="font-size:6.5px; background:#2d6a4f">Hadir</th>
      <th style="font-size:6.5px; background:#7b4f12">Izin</th>
      <th style="font-size:6.5px; background:#1d4e89">Sakit</th>
      <th style="font-size:6.5px; background:#8b1a1a">Alpha</th>
    </tr>
  </thead>
  <tbody>
    @foreach($rows as $row)
    <tr>
      <td class="col-no">{{ $row['no'] }}</td>
      <td class="col-nis">{{ $row['nis'] }}</td>
      <td class="col-name">{{ $row['name'] }}</td>

      @foreach($dates as $date)
        @php
          $key  = $date->toDateString();
          $info = $row['daily'][$key] ?? ['status' => 'alpha', 'scan_in' => null, 'late' => false];
          $st   = $info['status'];
        @endphp
        <td>
          @if($st === 'hadir')
            @if($info['late'])
              <span class="s-late" title="Terlambat {{ $info['scan_in'] }}">T</span>
            @else
              <span class="s-hadir">✓</span>
            @endif
          @elseif($st === 'izin')
            <span class="s-izin">I</span>
          @elseif($st === 'sakit')
            <span class="s-sakit">S</span>
          @else
            <span class="s-alpha">A</span>
          @endif
        </td>
      @endforeach

      <td class="col-sum s-hadir">{{ $row['summary']['hadir'] }}</td>
      <td class="col-sum s-izin">{{ $row['summary']['izin'] }}</td>
      <td class="col-sum s-sakit">{{ $row['summary']['sakit'] }}</td>
      <td class="col-sum s-alpha">{{ $row['summary']['alpha'] }}</td>
    </tr>
    @endforeach

    {{-- ── Baris total kelas ── --}}
    <tr class="sum-row">
      <td colspan="3" class="col-name" style="text-align:left; padding-left:5px">
        TOTAL KELAS
      </td>
      @foreach($dates as $date)
      <td></td>
      @endforeach
      <td class="s-hadir">{{ $classTotal['hadir'] }}</td>
      <td class="s-izin">{{ $classTotal['izin'] }}</td>
      <td class="s-sakit">{{ $classTotal['sakit'] }}</td>
      <td class="s-alpha">{{ $classTotal['alpha'] }}</td>
    </tr>
  </tbody>
</table>

{{-- ── LEGEND ──────────────────────────────────────────────────── --}}
<div class="legend">
  <span><b class="s-hadir">✓</b> = Hadir</span>
  <span><b class="s-late">T</b> = Terlambat</span>
  <span><b class="s-izin">I</b> = Izin</span>
  <span><b class="s-sakit">S</b> = Sakit</span>
  <span><b class="s-alpha">A</b> = Alpha/Tanpa Keterangan</span>
</div>

{{-- ── REKAPITULASI ─────────────────────────────────────────────── --}}
<table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
  <tr>
    <td style="width:25%; padding:4px 8px 4px 0; vertical-align:top">
      <div style="border:1px solid #c6f6d5; border-radius:4px; padding:6px 10px; background:#f0fff4;">
        <div style="font-size:7px; color:#4a5568;">Total Hadir</div>
        <div style="font-size:16px; font-weight:bold; color:#276749;">{{ $classTotal['hadir'] }}</div>
        <div style="font-size:6.5px; color:#68d391;">pertemuan</div>
      </div>
    </td>
    <td style="width:25%; padding:4px 4px 4px 0; vertical-align:top">
      <div style="border:1px solid #feebc8; border-radius:4px; padding:6px 10px; background:#fffaf0;">
        <div style="font-size:7px; color:#4a5568;">Total Izin</div>
        <div style="font-size:16px; font-weight:bold; color:#c05621;">{{ $classTotal['izin'] }}</div>
        <div style="font-size:6.5px; color:#f6ad55;">pertemuan</div>
      </div>
    </td>
    <td style="width:25%; padding:4px 4px 4px 0; vertical-align:top">
      <div style="border:1px solid #bee3f8; border-radius:4px; padding:6px 10px; background:#ebf8ff;">
        <div style="font-size:7px; color:#4a5568;">Total Sakit</div>
        <div style="font-size:16px; font-weight:bold; color:#2b6cb0;">{{ $classTotal['sakit'] }}</div>
        <div style="font-size:6.5px; color:#63b3ed;">pertemuan</div>
      </div>
    </td>
    <td style="width:25%; padding:4px 0; vertical-align:top">
      <div style="border:1px solid #fed7d7; border-radius:4px; padding:6px 10px; background:#fff5f5;">
        <div style="font-size:7px; color:#4a5568;">Total Alpha</div>
        <div style="font-size:16px; font-weight:bold; color:#c53030;">{{ $classTotal['alpha'] }}</div>
        <div style="font-size:6.5px; color:#fc8181;">pertemuan</div>
      </div>
    </td>
  </tr>
</table>

{{-- ── TANDA TANGAN ─────────────────────────────────────────────── --}}
<table style="width:100%; border-collapse:collapse; margin-top:8px;">
  <tr>
    <td style="width:50%; text-align:left; vertical-align:top; font-size:8px; padding-right:20px;">
      <b>Keterangan Tambahan:</b><br>
      <div style="border:1px solid #e2e8f0; min-height:40px; margin-top:4px; padding:4px; border-radius:2px; color:#a0aec0; font-size:7.5px;">
        &nbsp;
      </div>
    </td>
    <td style="width:50%; text-align:center; vertical-align:top; font-size:8px;">
      <div style="margin-bottom: 4px;">
        Mengetahui,<br>
        Wali Kelas {{ $classroom->grade }} {{ $classroom->major->major_code }} {{ $classroom->group_number }}
      </div>
      <div style="margin-top: 40px; border-top: 1px solid #1a1a1a; width:160px; margin-left:auto; margin-right:auto;"></div>
      <div style="font-weight:bold; margin-top:3px;">
        {{ $classroom->waliKelas?->user->name ?? '______________________' }}
      </div>
      @if($classroom->waliKelas?->nip)
      <div style="font-size:7.5px; color:#4a5568;">NIP. {{ $classroom->waliKelas->nip }}</div>
      @endif
    </td>
  </tr>
</table>

<div class="generated">
  Dicetak oleh sistem EduNexa &bull; {{ $generatedAt->isoFormat('D MMMM Y, HH:mm') }} WIB
</div>

</body>
</html>
