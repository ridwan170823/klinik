<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="description" content="Klinik Gigi Nara - Solusi kesehatan gigi dan mulut dengan layanan digital modern.">
  <title>Klinik Gigi Nara - Senyum Sehat Masa Kini</title>

 <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">

  <style>
    :root {
      --primary: #4f46e5;
      --primary-dark: #312e81;
      --accent: #22d3ee;
      --text-muted: #6b7280;
      --surface: #f5f7fb;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: radial-gradient(circle at top left, rgba(79, 70, 229, 0.18), transparent 45%),
                  radial-gradient(circle at bottom right, rgba(34, 211, 238, 0.18), transparent 55%),
                  var(--surface);
      color: #0f172a;
      margin: 0;
      min-height: 100vh;
    }

    .landing-nav {
      padding: 1.75rem 0;
    }

    .hero {
      position: relative;
      overflow: hidden;
      padding: 2rem 0 5rem;
    }

    .hero::before,
    .hero::after {
      content: '';
      position: absolute;
      border-radius: 999px;
      filter: blur(80px);
      opacity: 0.7;
    }

    .hero::before {
      width: 380px;
      height: 380px;
      background: rgba(79, 70, 229, 0.45);
      top: -120px;
      right: -160px;
    }

    .hero::after {
      width: 320px;
      height: 320px;
      background: rgba(34, 211, 238, 0.35);
      bottom: -140px;
      left: -120px;
    }

    .hero-illustration {
      position: relative;
      border-radius: 32px;
      background: linear-gradient(135deg, rgba(79, 70, 229, 0.95) 0%, rgba(99, 102, 241, 0.9) 60%, rgba(34, 211, 238, 0.9) 100%);
      box-shadow: 0 40px 120px rgba(79, 70, 229, 0.3);
      padding: 3rem;
      color: #fff;
      overflow: hidden;
    }

    .hero-illustration::after {
      content: '';
      position: absolute;
      width: 160px;
      height: 160px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      bottom: -40px;
      right: -20px;
    }

    .hero-badges span {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.65rem 1.25rem;
      border-radius: 999px;
      background: rgba(79, 70, 229, 0.1);
      color: var(--primary);
      font-weight: 600;
      margin-right: 0.75rem;
    }
  .hero-title {
      font-size: clamp(2.4rem, 3vw, 3.5rem);
      font-weight: 800;
      line-height: 1.15;
      margin-bottom: 1rem;
    }

    .hero-text {
      font-size: 1.05rem;
      color: var(--text-muted);
      max-width: 540px;
    }

    .hero-actions .btn {
      padding: 0.85rem 1.8rem;
      border-radius: 16px;
      font-weight: 600;
    }

    .font-weight-semibold {
      font-weight: 600 !important;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary) 0%, #6366f1 60%, var(--accent) 100%);
      border: none;
      box-shadow: 0 20px 45px rgba(79, 70, 229, 0.35);
    }

    .btn-outline-primary {
      border: 1px solid rgba(79, 70, 229, 0.4);
      color: var(--primary);
      background: rgba(255, 255, 255, 0.75);
      backdrop-filter: blur(6px);
    }

    .btn-outline-primary:hover {
      border-color: var(--primary);
      background: rgba(79, 70, 229, 0.12);
      color: var(--primary);
    }

    .stats {
      display: flex;
      gap: 2rem;
      margin-top: 2.5rem;
      flex-wrap: wrap;
    }

    .stat-card {
      flex: 1;
      min-width: 200px;
      background: rgba(255, 255, 255, 0.8);
      padding: 1.5rem;
      border-radius: 24px;
      box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
      backdrop-filter: blur(10px);
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary-dark);
    }

    .section-title {
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .section-subtitle {
      color: var(--text-muted);
      max-width: 620px;
      margin-bottom: 2.5rem;
    }

    .feature-card {
      background: #fff;
      border-radius: 24px;
      padding: 2rem;
      height: 100%;
      box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
      border: 1px solid rgba(79, 70, 229, 0.08);
    }

    .feature-icon {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      background: rgba(79, 70, 229, 0.1);
      color: var(--primary);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      margin-bottom: 1.25rem;
    }

    .steps {
      background: rgba(255, 255, 255, 0.85);
      border-radius: 32px;
      padding: 2.5rem;
      box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
    }

    .step-item {
      display: flex;
      align-items: flex-start;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .step-index {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: rgba(79, 70, 229, 0.12);
      color: var(--primary);
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .cta {
      text-align: center;
      padding: 4rem 0;
      background: linear-gradient(135deg, rgba(79, 70, 229, 0.95) 0%, rgba(20, 184, 166, 0.9) 100%);
      border-radius: 36px;
      color: #fff;
      margin: 5rem 0 2rem;
      position: relative;
      overflow: hidden;
    }

    .cta::after {
      content: '';
      position: absolute;
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.18);
      top: -60px;
      right: -40px;
    }

    footer {
      padding: 2.5rem 0 3rem;
      color: var(--text-muted);
    }

    @media (max-width: 991px) {
      .hero {
        padding-top: 1rem;
      }
      .hero-illustration {
        margin-top: 2.5rem;
      }
      .stats {
        gap: 1rem;
      }
    }

    @media (max-width: 575px) {
      .hero-actions .btn {
        width: 100%;
        margin-bottom: 0.75rem;
      }
      .hero-actions .btn:last-child {
        margin-bottom: 0;
      }
    }
  </style>



</head>

<body>

  <div class="container">
    <nav class="landing-nav d-flex align-items-center justify-content-between">
      <a class="navbar-brand d-flex align-items-center font-weight-bold" href="/">
        <span class="mr-2">🦷</span>
        Klinik Gigi Nara
      </a>
      <div>
        <a href="{{ route('login') }}" class="btn btn-outline-primary mr-2">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-primary">Daftar Pasien</a>
      </div>
    </nav>
  </div>

      <section class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="hero-badges mb-3">
            <span><i class="fas fa-shield-alt"></i> Klinik Digital Terpercaya</span>
            <span><i class="fas fa-clock"></i> Reservasi 24/7</span>
          </div>
          <h1 class="hero-title">Senyum percaya diri dimulai dari layanan gigi yang modern &amp; humanis.</h1>
          <p class="hero-text mb-4">Nikmati pengalaman perawatan gigi yang lebih personal, terintegrasi, dan transparan. Atur janji temu, pantau antrian, hingga konsultasi dengan dokter favorit Anda dalam satu dashboard canggih.</p>
          <div class="hero-actions d-flex flex-column flex-sm-row align-items-sm-center">
            <a href="{{ route('register') }}" class="btn btn-primary mr-sm-3 mb-3 mb-sm-0">Mulai Daftar Pasien</a>
            <a href="{{ route('login') }}" class="btn btn-outline-primary">Masuk ke Dashboard</a>
          </div>
          <div class="stats">
            <div class="stat-card">
              <div class="stat-value">2K+</div>
              <p class="mb-0 text-muted">Pasien mempercayai layanan kami setiap tahunnya.</p>
            </div>
            <div class="stat-card">
              <div class="stat-value">15+</div>
              <p class="mb-0 text-muted">Dokter ahli dengan spesialisasi terkini.</p>
            </div>
            <div class="stat-card">
              <div class="stat-value">98%</div>
              <p class="mb-0 text-muted">Tingkat kepuasan pasien terhadap pelayanan klinik.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="hero-illustration">
            <h4 class="font-weight-bold">Dashboard cerdas untuk semua pihak</h4>
            <p class="mb-4">Pantau jadwal praktik dokter, kelola antrian secara realtime, dan hadirkan pelayanan yang lebih bersahabat dengan teknologi.</p>
            <div class="row">
              <div class="col-sm-6 mb-3">
                <div class="feature-card h-100">
                  <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                  <h5 class="font-weight-semibold">Atur Antrian</h5>
                  <p class="mb-0">Reservasi mandiri dengan notifikasi otomatis saat jadwal mendekat.</p>
                </div>
              </div>
              <div class="col-sm-6 mb-3">
                <div class="feature-card h-100">
                  <div class="feature-icon"><i class="fas fa-heartbeat"></i></div>
                  <h5 class="font-weight-semibold">Catatan Terpusat</h5>
                  <p class="mb-0">Riwayat perawatan tersimpan rapi untuk memudahkan konsultasi berikutnya.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  <section>
    <section class="py-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <h2 class="section-title">Layanan unggulan yang membuat pasien merasa nyaman.</h2>
          <p class="section-subtitle">Mulai dari pemeriksaan preventif hingga tindakan estetik, kami menghadirkan pengalaman digital yang menyatu dengan empati dokter.</p>
          <div class="steps">
            <div class="step-item">
              <div class="step-index">1</div>
              <div>
                <h5 class="font-weight-semibold mb-1">Daftar &amp; lengkapi profil singkat</h5>
                <p class="mb-0 text-muted">Buat akun pasien hanya dalam hitungan detik dan lengkapi data untuk pengalaman yang lebih personal.</p>
              </div>
            </div>
            <div class="step-item">
              <div class="step-index">2</div>
              <div>
                <h5 class="font-weight-semibold mb-1">Pilih layanan &amp; dokter favorit</h5>
                <p class="mb-0 text-muted">Filter berdasarkan spesialisasi, jadwal praktik, dan ketersediaan slot secara realtime.</p>
              </div>
            </div>
            <div class="step-item mb-0">
              <div class="step-index">3</div>
              <div>
                <h5 class="font-weight-semibold mb-1">Datang tepat waktu tanpa rasa khawatir</h5>
                <p class="mb-0 text-muted">Dapatkan pengingat otomatis serta monitoring antrian dari gawai Anda.</p>
              </div>
            </div>
          </div>
        </div>
       <div class="col-lg-6">
          <div class="row">
            <div class="col-md-6 mb-4">
              <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-tooth"></i></div>
                <h5 class="font-weight-semibold">Perawatan Komprehensif</h5>
                <p class="mb-0 text-muted">Pembersihan, penambalan, orthodonti, hingga estetik gigi.</p>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h5 class="font-weight-semibold">Monitoring Keluarga</h5>
                <p class="mb-0 text-muted">Kelola jadwal seluruh anggota keluarga dalam satu akun.</p>
              </div>
            </div>
            <div class="col-md-6 mb-4 mb-md-0">
              <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <h5 class="font-weight-semibold">Dashboard Responsif</h5>
                <p class="mb-0 text-muted">Tampilan adaptif yang nyaman diakses dari ponsel hingga desktop.</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-hands-helping"></i></div>
                <h5 class="font-weight-semibold">Pendampingan Personal</h5>
                <p class="mb-0 text-muted">Tim support siap membantu setiap langkah perawatan Anda.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </section>

  <div class="container">
    <div class="cta">
      <h2 class="font-weight-bold mb-3">Wujudkan senyum terbaik Anda bersama Klinik Gigi Nara</h2>
      <p class="mb-4">Teknologi modern, dokter profesional, dan pengalaman pasien yang menyenangkan.</p>
      <a href="{{ route('register') }}" class="btn btn-light btn-lg font-weight-semibold">Bergabung Sekarang</a>
    </div>
  </div>

  <div class="container">
    <footer class="d-flex flex-column flex-md-row justify-content-between align-items-center">
      <div>© {{ date('Y') }} Klinik Gigi Nara. All rights reserved.</div>
      <div class="mt-3 mt-md-0">
        <a href="{{ route('login') }}" class="text-muted mr-3">Masuk</a>
        <a href="{{ route('register') }}" class="text-muted">Daftar</a>
      </div>
    </footer>
  </div>
</body>

</html>