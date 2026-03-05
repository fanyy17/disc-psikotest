<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Hasil Tes DISC</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',Arial,sans-serif;background:#f1f5f9;color:#1e293b;padding:32px 16px}
.wrap{max-width:640px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08)}
.hdr{background:#0b1829;padding:32px 36px;text-align:center}
.hdr-lbl{font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:#38bdf8;margin-bottom:8px}
.hdr-name{font-size:24px;font-weight:700;color:#fff;margin-bottom:4px}
.hdr-date{font-size:12px;color:#94a3b8}
.body{padding:32px 36px}
.sec-title{font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#64748b;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid #e2e8f0;margin-top:24px}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:4px}
.info-item{background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px}
.info-lbl{font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#94a3b8;margin-bottom:2px}
.info-val{font-size:13px;font-weight:600;color:#1e293b}
.tbl-wrap{border-radius:10px;overflow:hidden;margin-bottom:4px}
table{width:100%;border-collapse:collapse;font-size:13px}
thead tr{background:#0b1829}
thead th{padding:11px 14px;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#fff;text-align:center}
tbody tr:nth-child(odd) td{background:#fff}
tbody tr:nth-child(even) td{background:#f0f7ff}
tbody tr:last-child td{background:#eff6ff;border-top:2px solid #bfdbfe}
td{padding:10px 14px;text-align:center;font-weight:600;border-bottom:1px solid #e8f0fe;color:#1e293b}
td:first-child{font-size:15px;font-weight:700;color:#0f172a}
td:nth-child(2){color:#1d4ed8}
td:nth-child(3){color:#c2410c}
.bar-row{display:flex;align-items:center;gap:10px;margin-bottom:8px}
.bar-lbl{font-size:12px;font-weight:700;width:18px;flex-shrink:0}
.bar-track{flex:1;height:10px;background:#e2e8f0;border-radius:999px;overflow:hidden}
.bar-fill{height:100%;border-radius:999px}
.bar-num{font-size:12px;font-weight:700;width:28px;text-align:right;flex-shrink:0}
.dom-box{background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:10px;padding:14px 16px}
.dom-lbl{font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#16a34a;margin-bottom:4px}
.dom-val{font-size:14px;font-weight:600;color:#166534;line-height:1.5}
.anom-box{background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #ef4444;border-radius:10px;padding:14px 16px;margin-top:16px}
.anom-title{font-size:11px;font-weight:700;color:#dc2626;text-transform:uppercase;margin-bottom:4px}
.anom-desc{font-size:12px;color:#b91c1c;line-height:1.6}
.charts-row{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:8px}
.chart-item{text-align:center}
.chart-item img{width:100%;border-radius:8px;border:1px solid #e2e8f0}
.chart-caption{font-size:10px;font-weight:700;color:#64748b;margin-top:4px}
.chart-note{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;font-size:11px;color:#166534;text-align:center}
.ftr{background:#f8fafc;border-top:1px solid #e2e8f0;padding:20px 36px;text-align:center}
.ftr-note{font-size:11px;color:#94a3b8;line-height:1.6}
.ftr-brand{font-size:13px;font-weight:700;color:#0284c7;margin-top:6px}
</style>
</head>
<body>
<div class="wrap">

<div class="hdr">
  <div class="hdr-lbl">Laporan Hasil Tes DISC</div>
  <div class="hdr-name">{{ $user['nama'] ?? '-' }}</div>
  <div class="hdr-date">{{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
</div>

<div class="body">

  <div class="sec-title" style="margin-top:0">Informasi Peserta</div>
  <div class="info-grid">
    <div class="info-item">
      <div class="info-lbl">Nama</div>
      <div class="info-val">{{ $user['nama'] ?? '-' }}</div>
    </div>
    <div class="info-item">
      <div class="info-lbl">Email</div>
      <div class="info-val">{{ $user['email'] ?? '-' }}</div>
    </div>
    <div class="info-item">
      <div class="info-lbl">Pendidikan Terakhir</div>
      <div class="info-val">{{ $user['pendidikanTerakhir'] ?? '-' }}</div>
    </div>
    <div class="info-item">
      <div class="info-lbl">Posisi</div>
      <div class="info-val">{{ $user['posisi'] ?? '-' }}</div>
    </div>
    <div class="info-item">
      <div class="info-lbl">Organisasi</div>
      <div class="info-val">{{ $user['organisasi'] ?? '-' }}</div>
    </div>
    <div class="info-item">
      <div class="info-lbl">Jenis Kelamin</div>
      <div class="info-val">{{ $user['gender'] ?? '-' }}</div>
    </div>
  </div>

  <div class="sec-title">Skor DISC</div>
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>MOST</th>
          <th>LEAST</th>
          <th>CHANGE</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>D</td>
          <td>{{ $most['D'] ?? 0 }}</td>
          <td>{{ $least['D'] ?? 0 }}</td>
          <td>{{ $self['D'] ?? 0 }}</td>
        </tr>
        <tr>
          <td>I</td>
          <td>{{ $most['I'] ?? 0 }}</td>
          <td>{{ $least['I'] ?? 0 }}</td>
          <td>{{ $self['I'] ?? 0 }}</td>
        </tr>
        <tr>
          <td>S</td>
          <td>{{ $most['S'] ?? 0 }}</td>
          <td>{{ $least['S'] ?? 0 }}</td>
          <td>{{ $self['S'] ?? 0 }}</td>
        </tr>
        <tr>
          <td>C</td>
          <td>{{ $most['C'] ?? 0 }}</td>
          <td>{{ $least['C'] ?? 0 }}</td>
          <td>{{ $self['C'] ?? 0 }}</td>
        </tr>
        <tr>
          <td>*</td>
          <td>{{ $most['*'] ?? 0 }}</td>
          <td>{{ $least['*'] ?? 0 }}</td>
          <td>{{ $self['*'] ?? 0 }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  @php
    $cols = ['D','I','S','C'];
    $absVals = [];
    foreach ($cols as $t) {
      $absVals[] = abs($self[$t] ?? 0);
    }
    $maxVal = max($absVals) ?: 1;
    $barColors = ['D'=>'#3b82f6','I'=>'#f97316','S'=>'#16a34a','C'=>'#a855f7'];
  @endphp

  <div class="sec-title">Visualisasi Skor CHANGE</div>

  @php $t = 'D'; $val = $self[$t] ?? 0; $pct = min(100, round(abs($val)/$maxVal*100)); @endphp
  <div class="bar-row">
    <div class="bar-lbl" style="color:{{ $barColors[$t] }}">{{ $t }}</div>
    <div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%;background:{{ $barColors[$t] }}"></div></div>
    <div class="bar-num" style="color:{{ $barColors[$t] }}">{{ $val }}</div>
  </div>

  @php $t = 'I'; $val = $self[$t] ?? 0; $pct = min(100, round(abs($val)/$maxVal*100)); @endphp
  <div class="bar-row">
    <div class="bar-lbl" style="color:{{ $barColors[$t] }}">{{ $t }}</div>
    <div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%;background:{{ $barColors[$t] }}"></div></div>
    <div class="bar-num" style="color:{{ $barColors[$t] }}">{{ $val }}</div>
  </div>

  @php $t = 'S'; $val = $self[$t] ?? 0; $pct = min(100, round(abs($val)/$maxVal*100)); @endphp
  <div class="bar-row">
    <div class="bar-lbl" style="color:{{ $barColors[$t] }}">{{ $t }}</div>
    <div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%;background:{{ $barColors[$t] }}"></div></div>
    <div class="bar-num" style="color:{{ $barColors[$t] }}">{{ $val }}</div>
  </div>

  @php $t = 'C'; $val = $self[$t] ?? 0; $pct = min(100, round(abs($val)/$maxVal*100)); @endphp
  <div class="bar-row">
    <div class="bar-lbl" style="color:{{ $barColors[$t] }}">{{ $t }}</div>
    <div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%;background:{{ $barColors[$t] }}"></div></div>
    <div class="bar-num" style="color:{{ $barColors[$t] }}">{{ $val }}</div>
  </div>

  @php
    $dominant = 'D';
    $domVal = $self['D'] ?? 0;
    foreach (['I','S','C'] as $t) {
      if (($self[$t] ?? 0) > $domVal) {
        $dominant = $t;
        $domVal = $self[$t];
      }
    }
    $interp = [
      'D' => 'Dominance (D): Tegas, fokus pada hasil, kompetitif, suka tantangan.',
      'I' => 'Influence (I): Komunikatif, persuasif, optimis, mudah membangun relasi.',
      'S' => 'Steadiness (S): Stabil, sabar, pendukung, menyukai keharmonisan.',
      'C' => 'Compliance (C): Teliti, sistematis, analitis, berorientasi pada kualitas.',
    ];
  @endphp

  <div class="sec-title">Tipe Dominan</div>
  <div class="dom-box">
    <div class="dom-lbl">{{ $dominant }} — Dominan</div>
    <div class="dom-val">{{ $interp[$dominant] }}</div>
  </div>

  @php
    $rangesMost  = ['D'=>[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20],'I'=>[0,1,2,3,4,5,6,7,9,10,17],'S'=>[0,1,2,3,4,5,6,7,8,9,10,11,13,19],'C'=>[0,1,2,3,4,5,6,7,8,10,14]];
    $rangesLeast = ['D'=>[21,18,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1,0],'I'=>[19,15,10,9,8,7,5,4,3,2,1,0],'S'=>[19,18,13,12,11,10,9,8,7,6,5,4,3,2,1],'C'=>[15,13,12,11,10,9,8,7,6,5,4,3,2,1,0]];
    $rangesSelf  = ['D'=>[-21,-20,-15,-12,-11,-10,-9,-7,-6,-4,-3,-2,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20],'I'=>[-19,-10,-9,-8,-7,-6,-5,-4,-2,-1,0,1,2,3,4,5,6,7,8,15,17],'S'=>[-19,-12,-10,-8,-7,-6,-4,-3,-2,-1,0,1,2,3,4,5,6,7,8,9,10,15,19],'C'=>[-15,-13,-10,-9,-8,-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,7,14]];
    $hasAnom = false;
    foreach (['D','I','S','C'] as $t) {
      if (!in_array($most[$t] ?? 0,  $rangesMost[$t]))  { $hasAnom = true; }
      if (!in_array($least[$t] ?? 0, $rangesLeast[$t])) { $hasAnom = true; }
      if (!in_array($self[$t] ?? 0,  $rangesSelf[$t]))  { $hasAnom = true; }
    }
  @endphp

  @if ($hasAnom)
  <div class="anom-box">
    <div class="anom-title">Terdeteksi Indikasi Anomali Psikologi</div>
    <div class="anom-desc">Terdapat nilai di luar norma DISC yang perlu ditinjau lebih lanjut.</div>
  </div>
  @endif

  <div class="sec-title">Grafik Norm Grid</div>
  <div class="charts-row">
    <div class="chart-item">
      <img src="{{ $message->embed($chartMost) }}" alt="Grafik MOST">
      <div class="chart-caption">MASK (MOST)</div>
    </div>
    <div class="chart-item">
      <img src="{{ $message->embed($chartLeast) }}" alt="Grafik LEAST">
      <div class="chart-caption">PRESSURE (LEAST)</div>
    </div>
    <div class="chart-item">
      <img src="{{ $message->embed($chartSelf) }}" alt="Grafik SELF">
      <div class="chart-caption">SELF / CHANGE</div>
    </div>
  </div>
  <div class="chart-note">
    Lingkaran hijau = nilai peserta &nbsp;|&nbsp; Lingkaran merah = nilai anomali
  </div>

</div>

<div class="ftr">
  <div class="ftr-note">
    Email ini dikirim otomatis setelah peserta menyelesaikan tes.<br>
    Grafik tersedia sebagai lampiran PNG di email ini.
  </div>
  <div class="ftr-brand">EWF — Sistem Tes DISC</div>
</div>

</div>
</body>
</html>