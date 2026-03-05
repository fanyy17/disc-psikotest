<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Diri Peserta | Tes DISC</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar scrolled">
    <div class="nav-container">
        <div class="logo-wrapper">
            <img src="{{ asset('assets/logoewf.png') }}" alt="Logo" class="nav-logo">
            <span class="logo-text">Equityworld Futures</span>
        </div>

        <ul class="nav-links">
            <li><a href="{{ url('/') }}#tentang">Tentang</a></li>
            <li><a href="{{ url('/') }}#disc">DISC</a></li>
            <li><a href="{{ url('/') }}#mengapa">Mengapa Kami</a></li>
            <li>
                <a href="{{ url('/disc') }}" class="nav-btn">Mulai Tes</a>
            </li>
        </ul>
    </div>
</nav>

<main class="form-page">

  <div class="disc-container">

    <!-- HEADER -->
    <header class="disc-header">
    <div class="form-badge">PSIKOTES DISC</div>
    <h1>Data Diri Peserta</h1>
    <p class="disc-desc">
    Isi data dengan benar untuk memulai asesmen kepribadian Anda.
    </p>
    </header>
    <div class="disc-divider"></div>

    <!-- FORM -->
    <form method="POST" action="{{ route('disc.start') }}" class="disc-form" novalidate>
      @csrf

      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" required>
      </div>

      <div class="form-group">
        <label for="nama">Nama Lengkap</label>
        <input id="nama" name="nama" type="text" required>
      </div>

      <div class="form-group">
        <label for="pendidikan">Pendidikan</label>
        <input id="pendidikan" name="pendidikan" type="text" required>
      </div>

      <div class="form-group">
        <label for="organisasi">Organisasi</label>
        <input id="organisasi" name="organisasi" type="text">
      </div>

      <div class="form-group">
        <label for="posisi">Posisi yang Dilamar</label>
        <input id="posisi" name="posisi" type="text">
      </div>

      <div class="form-group">
        <label for="pendidikanTerakhir">Pendidikan Terakhir</label>
        <select id="pendidikanTerakhir" name="pendidikanTerakhir" required>
          <option value="">-- Pilih --</option>
          <option>SMA</option>
          <option>SMK</option>
          <option>D3</option>
          <option>S1</option>
          <option>S2</option>
        </select>
      </div>

      <!-- GENDER -->
      <div class="form-group">
        <span class="label">Jenis Kelamin</span>
        <div class="radio-options">
          <label class="radio-item">
            <input type="radio" name="gender" value="Male" required>
            <span>Male</span>
          </label>
          <label class="radio-item">
            <input type="radio" name="gender" value="Female">
            <span>Female</span>
          </label>
        </div>
      </div>

      <button type="submit" class="btn-primary">
        Mulai Tes DISC
      </button>

    </form>

  </div>

</main>

</body>
</html>