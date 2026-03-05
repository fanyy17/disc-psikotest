<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hasil Tes DISC</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/result.css') }}">
</head>
<body>

<main class="result-page">
<section class="result-card">

  <!-- HEADER -->
  <div class="pdf-header">
    <img src="{{ asset('assets/logoewf.png') }}" alt="Logo" class="pdf-logo">
    <div class="pdf-header-right">
      <p class="pdf-label">Laporan Hasil Tes DISC</p>
      <h2 class="pdf-name">{{ $user['nama'] ?? '-' }}</h2>
      <p class="pdf-date">Tanggal: {{ now()->translatedFormat('d F Y') }}</p>
    </div>
  </div>

  <!-- TITLE -->
  <h1 class="title">HASIL TES DISC</h1>
  <div class="title-line"></div>
  <p class="subtitle">Grafik berikut menunjukkan kecenderungan perilaku kerja berdasarkan metode DISC</p>

  <!-- TABEL -->
  <div class="score-table">
    <table>
      <thead>
        <tr>
          <th>Type</th><th>MOST</th><th>LEAST</th><th>CHANGE</th>
        </tr>
      </thead>
      <tbody>
        @foreach(['D','I','S','C','*'] as $type)
        <tr>
          <td>{{ $type }}</td>
          <td>{{ $most[$type] ?? 0 }}</td>
          <td>{{ $least[$type] ?? 0 }}</td>
          <td>{{ $self[$type] ?? 0 }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- DOMINANT -->
  <div class="dominant-box" id="dominantBox"></div>

  

  <!-- CHARTS -->
  <div class="charts">

    <div class="chart-box">
      <div class="chart-legend">
        <span class="legend-bar" style="background:#16a34a"></span>
        <span style="color:#16a34a">MOST (Mask)</span>
      </div>
      <h3>Mask <span class="chart-sub">(MOST)</span></h3>
      <div class="norm-grid" id="mostGrid"></div>
      <div class="disc-footer"><span></span><span>D</span><span>I</span><span>S</span><span>C</span></div>
    </div>

    <div class="chart-box">
      <div class="chart-legend">
        <span class="legend-bar" style="background:#16a34a"></span>
        <span style="color:#16a34a">LEAST (Pressure)</span>
      </div>
      <h3>Pressure <span class="chart-sub">(LEAST)</span></h3>
      <div class="norm-grid" id="leastGrid"></div>
      <div class="disc-footer"><span></span><span>D</span><span>I</span><span>S</span><span>C</span></div>
    </div>

    <div class="chart-box">
      <div class="chart-legend">
        <span class="legend-bar" style="background:#16a34a"></span>
        <span style="color:#16a34a">SELF (Change)</span>
      </div>
      <h3>Self <span class="chart-sub">/ Change</span></h3>
      <div class="norm-grid" id="selfGrid"></div>
      <div class="disc-footer"><span></span><span>D</span><span>I</span><span>S</span><span>C</span></div>
    </div>

  </div>

</section>
</main>

<!-- TOMBOL EXPORT -->
<div class="export-bar">
  <button class="btn-export" id="btnExport" onclick="exportPDF()">
    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
      <polyline points="7 10 12 15 17 10"/>
      <line x1="12" y1="15" x2="12" y2="3"/>
    </svg>
    Export ke PDF
  </button>
</div>

<script>
/* =================================================================
   STRATEGI GARIS SVG YANG AKURAT DI PRINT:

   Masalah: getBoundingClientRect() mengembalikan koordinat PIKSEL
   yang bergantung pada ukuran viewport. Saat print, ukuran berubah
   → koordinat lama salah → garis meleset.

   Solusi: Simpan koordinat sebagai INDEKS BARIS & KOLOM (posisi
   struktural dalam grid), bukan piksel. Saat drawSVG dipanggil,
   hitung piksel BARU dari posisi DOM aktual saat itu.
   Ini selalu akurat karena tidak bergantung ukuran viewport.
================================================================= */

const COLS      = ["D","I","S","C"];
const ANOM_CLR  = '#dc2626';

/* Simpan referensi elemen per wrap, bukan koordinat piksel */
const wrapMeta  = new WeakMap();

function drawSVG(wrap) {
  /* Hapus SVG lama */
  const old = wrap.querySelector('.ng-svg');
  if (old) old.remove();

  const meta = wrapMeta.get(wrap);
  if (!meta) return;

  const { pointEls, color } = meta;

  /* Hitung koordinat piksel SEKARANG berdasarkan DOM aktual */
  const wRect = wrap.getBoundingClientRect();
  if (wRect.width === 0 || wRect.height === 0) return; /* belum render */

  const pts = pointEls.map(({ el, anom, val }) => {
    const r = el.getBoundingClientRect();
    return {
      x:    r.left - wRect.left + r.width  / 2,
      y:    r.top  - wRect.top  + r.height / 2,
      anom, val
    };
  });

  if (pts.length < 2) return;

  const ns  = 'http://www.w3.org/2000/svg';
  const svg = document.createElementNS(ns, 'svg');
  svg.classList.add('ng-svg');
  svg.setAttribute('width',  wRect.width);
  svg.setAttribute('height', wRect.height);

  /* Bezier path */
  let d = `M ${pts[0].x} ${pts[0].y}`;
  for (let i = 1; i < pts.length; i++) {
    const p = pts[i-1], c = pts[i], mx = (p.x + c.x) / 2;
    d += ` C ${mx} ${p.y}, ${mx} ${c.y}, ${c.x} ${c.y}`;
  }
  const path = document.createElementNS(ns, 'path');
  path.setAttribute('d', d);
  path.setAttribute('stroke', color);
  path.setAttribute('stroke-width', '2.5');
  path.setAttribute('fill', 'none');
  path.setAttribute('stroke-linecap', 'round');
  svg.appendChild(path);

  /* Marker */
  pts.forEach(p => {
    if (p.anom) {
      const c = document.createElementNS(ns, 'circle');
      c.setAttribute('cx', p.x); c.setAttribute('cy', p.y);
      c.setAttribute('r', '13'); c.setAttribute('fill', ANOM_CLR);
      svg.appendChild(c);
      const t = document.createElementNS(ns, 'text');
      t.setAttribute('x', p.x); t.setAttribute('y', p.y + 4);
      t.setAttribute('text-anchor', 'middle');
      t.setAttribute('font-size', '10'); t.setAttribute('font-weight', '700');
      t.setAttribute('fill', '#fff'); t.textContent = p.val;
      svg.appendChild(t);
    } else {
      const r = document.createElementNS(ns, 'circle');
      r.setAttribute('cx', p.x); r.setAttribute('cy', p.y);
      r.setAttribute('r', '13'); r.setAttribute('fill', 'none');
      r.setAttribute('stroke', color); r.setAttribute('stroke-width', '1.5');
      r.setAttribute('opacity', '0.3');
      svg.appendChild(r);
    }
  });

  wrap.appendChild(svg);
}

/*
  beforeprint: dipanggil SYNCHRONOUSLY oleh browser.
  Tidak ada setTimeout, tidak ada async — harus selesai sebelum return.
  getBoundingClientRect() di sini membaca layout PRINT yang sudah dihitung
  browser karena kita berada di dalam beforeprint callback.
*/
window.addEventListener('beforeprint', function () {
  document.querySelectorAll('.ng-wrap').forEach(wrap => {
    if (wrapMeta.has(wrap)) drawSVG(wrap);
  });
});

/* ── Main logic ─────────────────────────────────────────────────── */
document.addEventListener("DOMContentLoaded", function () {

  const data = {
    most:  @json($most),
    least: @json($least),
    self:  @json($self)
  };

  const normTable = {
    most: [
      [[20,16,15],[17,10,9],[19,13],[14,10,0]],
      [[14,13],[7],[11,10],[8,7]],
      [[12,11,10],[6,5],[9,8,7],[6]],
      [[9,8,7],[4],[6,5],[5,4]],
      [[6,5,4],[3],[4,3],[3]],
      [[3],[2],[2],[2]],
      [[2],[1,0],[1,0],[1,0]],
      [[0],[],[],[]],
    ],
    least: [
      [[],[0],[0,1,2],[0,1]],
      [[1,2],[],[],[2]],
      [[3],[2,3],[3,4],[3,4]],
      [[4,5],[4],[5,6],[5,6]],
      [[6,7,8],[5],[7,8],[7]],
      [[9,10,11],[7],[9],[9,10]],
      [[12,13,14],[8,9],[10,11,12],[11,12]],
      [[15,16,18,21],[10,15,19],[13,18,19],[13,15]],
    ],
    self: [
      [[20,16,15,14],[17,15,8],[19,15,10],[14,7,5]],
      [[13,12,10],[7,6,5,4],[9,8,7],[4,3]],
      [[9,8,7],[3,2],[5,4,3,2],[1,0]],
      [[5,3,1],[1,0],[1,0],[-1,-2]],
      [[0,-2,-3,-4],[-1,-2],[-1,-2,-3,-4],[-3,-4]],
      [[-6,-7,-9],[-3,-4,-5],[-6,-7],[-5,-6,-7]],
      [[-10,-11,-12],[-6,-7,-8],[-8,-10],[-8,-9,-10]],
      [[-15,-20,-21],[-9,-10,-19],[-12,-19],[-13,-15]],
    ]
  };

  const ranges = {
    most: {
      D:[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20],
      I:[0,1,2,3,4,5,6,7,9,10,17],
      S:[0,1,2,3,4,5,6,7,8,9,10,11,13,19],
      C:[0,1,2,3,4,5,6,7,8,10,14]
    },
    least: {
      D:[21,18,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1,0],
      I:[19,15,10,9,8,7,5,4,3,2,1,0],
      S:[19,18,13,12,11,10,9,8,7,6,5,4,3,2,1],
      C:[15,13,12,11,10,9,8,7,6,5,4,3,2,1,0]
    },
    self: {
      D:[-21,-20,-15,-12,-11,-10,-9,-7,-6,-4,-3,-2,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20],
      I:[-19,-10,-9,-8,-7,-6,-5,-4,-2,-1,0,1,2,3,4,5,6,7,8,15,17],
      S:[-19,-12,-10,-8,-7,-6,-4,-3,-2,-1,0,1,2,3,4,5,6,7,8,9,10,15,19],
      C:[-15,-13,-10,-9,-8,-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,7,14]
    }
  };

  function isAbnormal(dim, col, val) {
    return ranges[dim]?.[col] ? !ranges[dim][col].includes(val) : false;
  }

  function findNearestRowIndex(dim, colIndex, targetVal) {
    let bestRow = 0, bestDiff = Infinity;
    normTable[dim].forEach((row, ri) => {
      (row[colIndex] || []).forEach(v => {
        const d = Math.abs(v - targetVal);
        if (d < bestDiff) { bestDiff = d; bestRow = ri; }
      });
    });
    return bestRow;
  }

  /* Dominant */
  let dominant = "D";
  COLS.forEach(l => { if ((data.self[l]??0) > (data.self[dominant]??0)) dominant = l; });
  const interp = {
    D: "Dominance (D): Tegas, fokus pada hasil, kompetitif, suka tantangan.",
    I: "Influence (I): Komunikatif, persuasif, optimis, mudah membangun relasi.",
    S: "Steadiness (S): Stabil, sabar, pendukung, menyukai keharmonisan.",
    C: "Compliance (C): Teliti, sistematis, analitis, berorientasi pada kualitas."
  };
  document.getElementById("dominantBox").innerHTML =
    `<strong>Tipe Dominan Anda:</strong><br>${interp[dominant]}`;

  

  const lineColor = { most:'#16a34a', least:'#16a34a', self:'#16a34a' };

  function buildGrid(dim, containerId) {
    const container = document.getElementById(containerId);
    const userVals  = data[dim];
    const color     = lineColor[dim];

    const wrap  = document.createElement('div'); wrap.className = 'ng-wrap';
    const table = document.createElement('div'); table.className = 'ng-table';
    const rowEls = [];

    /* Referensi elemen DOM (bukan koordinat piksel) */
    const circleEls  = {}; /* col → span.ng-circled */
    const anomCellEl = {}; /* col → td cell (untuk anomali) */

    normTable[dim].forEach((row, ri) => {
      const tr = document.createElement('div'); tr.className = 'ng-row'; rowEls.push(tr);
      const sp = document.createElement('div'); sp.className = 'ng-cell ng-idx'; tr.appendChild(sp);
      COLS.forEach((col, ci) => {
        const td = document.createElement('div'); td.className = 'ng-cell';
        const vals = row[ci] || [], uv = userVals[col] ?? null;
        vals.forEach(v => {
          const s = document.createElement('span');
          s.textContent = v;
          if (v === uv) {
            s.classList.add('ng-circled');
            s.style.background = color;
            circleEls[col] = s; /* simpan referensi elemen, bukan piksel */
          }
          td.appendChild(s);
        });
        tr.appendChild(td);
      });
      table.appendChild(tr);
    });

    /* Untuk anomali: simpan referensi sel grid terdekat */
    COLS.forEach((col, ci) => {
      if (circleEls[col]) return; /* sudah punya lingkaran normal */
      const uv = userVals[col] ?? null;
      if (uv === null) return;
      const nr    = findNearestRowIndex(dim, ci, uv);
      const cells = rowEls[nr].querySelectorAll('.ng-cell');
      const cell  = cells[ci + 1]; /* +1 karena kolom 0 adalah ng-idx */
      if (cell) anomCellEl[col] = { el: cell, val: uv };
    });

    wrap.appendChild(table);
    container.appendChild(wrap);

    /* Susun array pointEls: referensi elemen DOM per kolom D,I,S,C */
    const pointEls = COLS.map(col => {
      if (circleEls[col])  return { el: circleEls[col],       anom: false, val: userVals[col] };
      if (anomCellEl[col]) return { el: anomCellEl[col].el,   anom: true,  val: anomCellEl[col].val };
      return null;
    }).filter(Boolean);

    /* Simpan ke WeakMap — referensi elemen, bukan piksel */
    wrapMeta.set(wrap, { pointEls, color });

    /* Gambar pertama kali setelah render */
    requestAnimationFrame(() => requestAnimationFrame(() => drawSVG(wrap)));
  }

  buildGrid('most',  'mostGrid');
  buildGrid('least', 'leastGrid');
  buildGrid('self',  'selfGrid');

}); /* end DOMContentLoaded */

function exportPDF() {
  window.print();
}
</script>

</body>
</html>