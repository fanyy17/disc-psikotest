<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tes DISC</title>

  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('css/test.css') }}">
</head>
<body>

<div class="welcome-box">
  <div class="welcome-left">
    <img src="{{ asset('assets/logoewf.png') }}" 
         alt="Equityworld Futures Logo" 
         class="welcome-logo">
  </div>

  <div class="welcome-right">
    <p class="welcome-label">Selamat Datang</p>
    <h2 class="welcome-name">
      {{ $user['nama'] }}
    </h2>
    <p class="welcome-desc">
      Anda akan memulai Tes DISC untuk mengetahui kecenderungan gaya kepribadian Anda.
    </p>
  </div>
</div>

<main class="test-wrapper">
  
  <div class="title-badge">ASSESSMENT PLATFORM</div>

  <h1 class="test-title">
    TEST DISC
    <spann class="subtitle">Personality Style Analysis</spann>
  </h1>

  <section class="instruction-card">
    <h2>Instruksi</h2>
    <p>
      Berikut akan ada <strong>24 kotak pertanyaan</strong>.
      Setiap kotak berisi <strong>empat pernyataan</strong>.
    </p>
    <ul>
      <li>Pilih <strong>MOST (+)</strong> → paling menggambarkan diri Anda</li>
      <li>Pilih <strong>LEAST (-)</strong> → paling tidak menggambarkan diri Anda</li>
    </ul>
    <p class="instruction-note">
      Jawablah dengan membayangkan kondisi pekerjaan terakhir atau situasi di rumah.
      <strong>BEKERJALAH DENGAN CEPAT</strong>.
    </p>
  </section>

  <form method="POST" action="{{ route('disc.store') }}">
    @csrf

    <section id="questionContainer">

    @foreach($questions as $q)
      <div class="question-box">

        <h3>Soal {{ $q['number'] }}</h3>

        <!-- Header Kolom -->
        <div class="question-header">
          <div>Pernyataan</div>
          <div>Most</div>
          <div>Least</div>
        </div>

        @foreach($q['options'] as $option)
          <div class="option-row">

            <!-- Text -->
            <div class="option-text">
              {{ $option['text'] }}
            </div>

            <!-- MOST -->
            <label class="pick most">
              <input type="radio"
                     name="most[{{ $q['number'] }}]"
                     value="{{ $option['score'] }}"
                     required>
              <span>+</span>
            </label>

            <!-- LEAST -->
            <label class="pick least">
              <input type="radio"
                     name="least[{{ $q['number'] }}]"
                     value="{{ $option['score'] }}"
                     required>
              <span>-</span>
            </label>

          </div>
        @endforeach

      </div>
    @endforeach

    </section>

    <div class="navigation">
      <button type="submit" class="btn-primary">Selesai</button>
    </div>

  </form>

</main>

</body>
</html>
