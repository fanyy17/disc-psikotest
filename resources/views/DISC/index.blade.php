<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSIKOTES DISC | Equityworld Futures</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar transparent">
    <div class="nav-container">
        <div class="logo-text">Equityworld Futures</div>
        <ul class="nav-links">
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#disc">DISC</a></li>
            <li><a href="#mengapa">Mengapa Kami</a></li>
            <li>
                <a href="{{ url('/disc') }}" class="nav-btn">Mulai Tes</a>
            </li>
        </ul>
    </div>
</nav>

<!-- HERO -->
<header class="hero">
    <div class="hero-content">
    <!--LOGO -->
    <img src="{{ asset('assets/logoewf.png') }}" alt="Equityworld Futures Logo" class="hero-logo">
        <h1>Bangun Karier Sesuai Potensi Anda</h1>
        <p>Tes DISC membantu memahami gaya kerja dan karakter profesional Anda</p>
    </div>
    <div class="hero-curve"></div>
</header>

<main>

<section id="tentang" class="container">
    <h2>Tentang Equityworld Futures</h2>
    <p>
        Equityworld Futures adalah perusahaan pialang berjangka resmi
        yang berkomitmen membangun sumber daya manusia profesional
        melalui proses rekrutmen berbasis kompetensi dan psikologi kerja.
    </p>
</section>

<section id="disc" class="container alt">
    <h2>Tentang DISC</h2>
    <p>
        DISC adalah metode asesmen kepribadian yang digunakan untuk
         memahami gaya perilaku dan kecenderungan kerja seseorang.
         Metode ini mengelompokkan perilaku ke dalam empat dimensi utama:
         Dominance (D), Influence (I), Steadiness (S), dan Compliance (C).
    </p>

    <p>
         DISC tidak menilai benar atau salah, melainkan membantu memahami
         bagaimana seseorang berinteraksi, mengambil keputusan, dan
         beradaptasi dalam lingkungan kerja.
    </p>

</section>

<section id="mengapa" class="container">
    <h2>Mengapa Menggunakan DISC?</h2>
    <ul>
        <li>Menempatkan kandidat sesuai dengan karakter dan peran kerja</li>
        <li>Mendukung pengambilan keputusan rekrutmen yang objektif</li>
        <li>Membangun tim kerja yang seimbang dan profesional</li>
        <li>Mendukung pengembangan karyawan secara berkelanjutan</li>
    </ul>
</section>

<section class="cta">
    <div class="cta-box">
        <h2>Siap Memulai Tes DISC?</h2>
        <p>Proses hanya 10–15 menit.</p>
        <a href="{{ url('/disc') }}" class="btn-primary">Mulai Tes DISC</a>
    </div>
</section>

</main>

<footer>
    <p>© Equityworld Futures Surabaya</p>
</footer>

<!-- SCRIPT SCROLL NAVBAR -->
<script>
window.addEventListener("scroll", function() {
    const navbar = document.querySelector(".navbar");

    if (window.scrollY > 50) {
        navbar.classList.remove("transparent");
        navbar.classList.add("scrolled");
    } else {
        navbar.classList.remove("scrolled");
        navbar.classList.add("transparent");
    }
});
</script>

</body>
</html>