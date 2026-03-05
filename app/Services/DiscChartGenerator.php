<?php
// Lokasi: app/Services/DiscChartGenerator.php
// Fungsi: Menggambar 3 grafik norm grid DISC (Most, Least, Self)
//         menggunakan GD Library (bawaan PHP/Laragon)
//         dan mengembalikan path file PNG yang bisa dilampirkan ke email

namespace App\Services;

class DiscChartGenerator
{
    // ── Tabel norma (sama persis dengan JS di result.blade.php) ──
    private array $normTable = [
        'most' => [
            [[20,16,15],[17,10,9],[19,13],[14,10,0]],
            [[14,13],[7],[11,10],[8,7]],
            [[12,11,10],[6,5],[9,8,7],[6]],
            [[9,8,7],[4],[6,5],[5,4]],
            [[6,5,4],[3],[4,3],[3]],
            [[3],[2],[2],[2]],
            [[2],[1,0],[1,0],[1,0]],
            [[0],[],[],[]],
        ],
        'least' => [
            [[],[0],[0,1,2],[0,1]],
            [[1,2],[],[],[2]],
            [[3],[2,3],[3,4],[3,4]],
            [[4,5],[4],[5,6],[5,6]],
            [[6,7,8],[5],[7,8],[7]],
            [[9,10,11],[7],[9],[9,10]],
            [[12,13,14],[8,9],[10,11,12],[11,12]],
            [[15,16,18,21],[10,15,19],[13,18,19],[13,15]],
        ],
        'self' => [
            [[20,16,15,14],[17,15,8],[19,15,10],[14,7,5]],
            [[13,12,10],[7,6,5,4],[9,8,7],[4,3]],
            [[9,8,7],[3,2],[5,4,3,2],[1,0]],
            [[5,3,1],[1,0],[1,0],[-1,-2]],
            [[0,-2,-3,-4],[-1,-2],[-1,-2,-3,-4],[-3,-4]],
            [[-6,-7,-9],[-3,-4,-5],[-6,-7],[-5,-6,-7]],
            [[-10,-11,-12],[-6,-7,-8],[-8,-10],[-8,-9,-10]],
            [[-15,-20,-21],[-9,-10,-19],[-12,-19],[-13,-15]],
        ],
    ];

    private array $ranges = [
        'most' => [
            'D' => [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20],
            'I' => [0,1,2,3,4,5,6,7,9,10,17],
            'S' => [0,1,2,3,4,5,6,7,8,9,10,11,13,19],
            'C' => [0,1,2,3,4,5,6,7,8,10,14],
        ],
        'least' => [
            'D' => [21,18,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1,0],
            'I' => [19,15,10,9,8,7,5,4,3,2,1,0],
            'S' => [19,18,13,12,11,10,9,8,7,6,5,4,3,2,1],
            'C' => [15,13,12,11,10,9,8,7,6,5,4,3,2,1,0],
        ],
        'self' => [
            'D' => [-21,-20,-15,-12,-11,-10,-9,-7,-6,-4,-3,-2,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,20],
            'I' => [-19,-10,-9,-8,-7,-6,-5,-4,-2,-1,0,1,2,3,4,5,6,7,8,15,17],
            'S' => [-19,-12,-10,-8,-7,-6,-4,-3,-2,-1,0,1,2,3,4,5,6,7,8,9,10,15,19],
            'C' => [-15,-13,-10,-9,-8,-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,7,14],
        ],
    ];

    private array $cols = ['D', 'I', 'S', 'C'];

    // ── Ukuran canvas ──
    private int $cellW    = 68;   // lebar tiap kolom
    private int $cellH    = 52;   // tinggi tiap baris
    private int $colLabel = 24;   // lebar kolom pertama (kosong/indeks)
    private int $paddingT = 40;   // ruang atas untuk judul
    private int $paddingB = 30;   // ruang bawah untuk label D I S C
    private int $paddingX = 16;   // ruang kiri kanan

    /**
     * Generate semua 3 grafik, simpan ke storage/app/temp/
     * Return: array path file PNG ['most'=>..., 'least'=>..., 'self'=>...]
     */
    public function generate(array $most, array $least, array $self): array
    {
        $data = ['most' => $most, 'least' => $least, 'self' => $self];
        $titles = [
            'most'  => 'Mask (MOST)',
            'least' => 'Pressure (LEAST)',
            'self'  => 'Self / Change',
        ];

        // Warna garis — semua hijau sesuai tampilan web
        $lineColorRGB = [22, 163, 74]; // #16a34a hijau

        $paths = [];
        foreach (['most', 'least', 'self'] as $dim) {
            $paths[$dim] = $this->drawGrid(
                dim:          $dim,
                userVals:     $data[$dim],
                title:        $titles[$dim],
                lineColorRGB: $lineColorRGB
            );
        }

        return $paths;
    }

    private function drawGrid(
        string $dim,
        array  $userVals,
        string $title,
        array  $lineColorRGB
    ): string {
        $rows    = $this->normTable[$dim];
        $numRows = count($rows);
        $numCols = 4; // D I S C

        // ── Hitung ukuran canvas ──
        $totalW = $this->paddingX * 2 + $this->colLabel + $numCols * $this->cellW;
        $totalH = $this->paddingT + $numRows * $this->cellH + $this->paddingB;

        // ── Buat canvas ──
        $img = imagecreatetruecolor($totalW, $totalH);
        imagesavealpha($img, true);

        // ── Warna palette ──
        $bgCanvas   = imagecolorallocate($img, 11,  26,  41);   // #0b1a29 background gelap
        $bgCell     = imagecolorallocate($img, 9,   22,  40);   // #091628 cell background
        $borderCell = imagecolorallocate($img, 26,  47,  74);   // #1a2f4a border cell
        $textDim    = imagecolorallocate($img, 71,  85,  105);  // #475569 angka normal
        $textCircle = imagecolorallocate($img, 255, 255, 255);  // #ffffff angka di lingkaran
        $lineColor  = imagecolorallocate($img, $lineColorRGB[0], $lineColorRGB[1], $lineColorRGB[2]);
        $anomColor  = imagecolorallocate($img, 220, 38,  38);   // #dc2626 merah anomali
        $titleColor = imagecolorallocate($img, 226, 232, 240);  // #e2e8f0 judul
        $footerClr  = imagecolorallocate($img, 100, 116, 139);  // #64748b label D I S C
        $ringColor  = imagecolorallocate($img, 22,  163, 74);   // lingkaran outline

        // ── Fill background ──
        imagefill($img, 0, 0, $bgCanvas);

        // ── Judul ──
        $font = 3; // built-in GD font
        $titleX = (int)(($totalW - strlen($title) * imagefontwidth($font)) / 2);
        imagestring($img, $font, $titleX, 10, $title, $titleColor);

        // ── Cari posisi lingkaran (titik yang cocok dengan nilai user) ──
        $circlePos  = []; // col → [cx, cy]
        $anomPos    = []; // col → [cx, cy, val]

        // ── Gambar sel grid ──
        for ($ri = 0; $ri < $numRows; $ri++) {
            $row = $rows[$ri];
            for ($ci = 0; $ci < $numCols; $ci++) {
                $col  = $this->cols[$ci];
                $vals = $row[$ci] ?? [];
                $uv   = $userVals[$col] ?? null;

                // Koordinat sel
                $x1 = $this->paddingX + $this->colLabel + $ci * $this->cellW;
                $y1 = $this->paddingT + $ri * $this->cellH;
                $x2 = $x1 + $this->cellW - 2;
                $y2 = $y1 + $this->cellH - 2;
                $cx = (int)(($x1 + $x2) / 2);
                $cy = (int)(($y1 + $y2) / 2);

                // Gambar background sel
                imagefilledrectangle($img, $x1, $y1, $x2, $y2, $bgCell);
                imagerectangle($img, $x1, $y1, $x2, $y2, $borderCell);

                // Gambar angka-angka di dalam sel
                if (!empty($vals)) {
                    $lineH  = imagefontheight($font) + 2;
                    $startY = $cy - (int)(count($vals) * $lineH / 2);

                    foreach ($vals as $idx => $v) {
                        $vStr = (string)$v;
                        $tw   = strlen($vStr) * imagefontwidth($font);
                        $tx   = $cx - (int)($tw / 2);
                        $ty   = $startY + $idx * $lineH;

                        if ($v === $uv) {
                            // Ini nilai yang cocok — catat posisi untuk digambar lingkaran
                            $circlePos[$col] = [$cx, $cy];
                            // Gambar lingkaran hijau dulu (akan ditimpa teks)
                            imagefilledellipse($img, $cx, $cy, 26, 26, $lineColor);
                            imagestring($img, $font, $tx, $ty + (int)(($cy - $ty - imagefontheight($font)/2)), $vStr, $textCircle);
                        } else {
                            imagestring($img, $font, $tx, $ty, $vStr, $textDim);
                        }
                    }
                }
            }
        }

        // ── Cari posisi anomali (nilai yang tidak ada di tabel norma) ──
        foreach ($this->cols as $ci => $col) {
            if (isset($circlePos[$col])) continue;

            $uv = $userVals[$col] ?? null;
            if ($uv === null) continue;

            if (!in_array($uv, $this->ranges[$dim][$col] ?? [])) {
                // Cari baris terdekat
                $bestRow  = 0;
                $bestDiff = PHP_INT_MAX;
                foreach ($rows as $ri => $row) {
                    foreach (($row[$ci] ?? []) as $v) {
                        $diff = abs($v - $uv);
                        if ($diff < $bestDiff) {
                            $bestDiff = $diff;
                            $bestRow  = $ri;
                        }
                    }
                }
                $x1 = $this->paddingX + $this->colLabel + $ci * $this->cellW;
                $y1 = $this->paddingT + $bestRow * $this->cellH;
                $cx = (int)($x1 + $this->cellW / 2);
                $cy = (int)($y1 + $this->cellH / 2);
                $anomPos[$col] = [$cx, $cy, $uv];
            }
        }

        // ── Gambar garis penghubung (bezier simulasi dengan polyline) ──
        $allPoints = [];
        foreach ($this->cols as $col) {
            if (isset($circlePos[$col])) {
                $allPoints[] = $circlePos[$col];
            } elseif (isset($anomPos[$col])) {
                $allPoints[] = [$anomPos[$col][0], $anomPos[$col][1]];
            }
        }

        if (count($allPoints) >= 2) {
            imagesetthickness($img, 3);
            for ($i = 0; $i < count($allPoints) - 1; $i++) {
                [$x1, $y1] = $allPoints[$i];
                [$x2, $y2] = $allPoints[$i + 1];

                // Simulasi bezier dengan banyak titik tengah
                $steps = 20;
                $mx    = ($x1 + $x2) / 2;
                $px    = $x1;
                $py    = $y1;

                for ($t = 1; $t <= $steps; $t++) {
                    $tt  = $t / $steps;
                    $bx  = (1-$tt)*(1-$tt)*$x1 + 2*(1-$tt)*$tt*$mx + $tt*$tt*$x2;
                    $by  = (1-$tt)*(1-$tt)*$y1 + 2*(1-$tt)*$tt*$y1 + $tt*$tt*$y2;
                    imageline($img, (int)$px, (int)$py, (int)$bx, (int)$by, $lineColor);
                    $px  = $bx;
                    $py  = $by;
                }
            }
            imagesetthickness($img, 1);
        }

        // ── Gambar ulang lingkaran di atas garis ──
        foreach ($circlePos as $col => [$cx, $cy]) {
            $uv   = $userVals[$col];
            $vStr = (string)$uv;
            $tw   = strlen($vStr) * imagefontwidth($font);
            imagefilledellipse($img, $cx, $cy, 28, 28, $lineColor);
            imagestring($img, $font,
                $cx - (int)($tw / 2),
                $cy - (int)(imagefontheight($font) / 2),
                $vStr, $textCircle
            );
        }

        // ── Gambar lingkaran anomali (merah) di atas garis ──
        foreach ($anomPos as $col => [$cx, $cy, $val]) {
            $vStr = (string)$val;
            $tw   = strlen($vStr) * imagefontwidth($font);
            imagefilledellipse($img, $cx, $cy, 28, 28, $anomColor);
            imagestring($img, $font,
                $cx - (int)($tw / 2),
                $cy - (int)(imagefontheight($font) / 2),
                $vStr, $textCircle
            );
        }

        // ── Label D I S C di bawah ──
        foreach ($this->cols as $ci => $col) {
            $x  = $this->paddingX + $this->colLabel + $ci * $this->cellW;
            $cx = (int)($x + $this->cellW / 2 - imagefontwidth(4) / 2);
            $cy = $this->paddingT + $numRows * $this->cellH + 8;
            imagestring($img, 4, $cx, $cy, $col, $footerClr);
        }

        // ── Simpan ke file PNG ──
        $dir  = storage_path('app/temp');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $path = $dir . "/disc_{$dim}_" . time() . ".png";
        imagepng($img, $path);
        imagedestroy($img);

        return $path;
    }

    /**
     * Hapus file temp setelah email terkirim
     */
    public function cleanup(array $paths): void
    {
        foreach ($paths as $path) {
            if (file_exists($path)) unlink($path);
        }
    }
}