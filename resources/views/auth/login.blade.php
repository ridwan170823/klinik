<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login • KLINIK NARA DENTAL PRIORITY</title>

  <!-- Fonts -->
  <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

  <!-- SB Admin 2 Core -->
  <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

  <!-- Custom styles just for this page -->
  <style>
    :root {
      --brand: #4f46e5; /* indigo-600 */
      --brand-2: #22d3ee; /* cyan-400 */
      --bg-1: #0f172a; /* slate-900 */
      --bg-2: #111827; /* gray-900 */
      --card-bg: #ffffff;
    }

    body.login-gradient {
      min-height: 100vh;
      background: radial-gradient(1200px 600px at 10% -10%, rgba(79, 70, 229, 0.25), transparent 60%),
                  radial-gradient(800px 500px at 110% 10%, rgba(34, 211, 238, 0.20), transparent 60%),
                  linear-gradient(135deg, #0f172a 0%, #111827 100%);
    }

    .auth-wrapper {
      display: grid;
      grid-template-columns: 1.1fr 0.9fr;
      gap: 0;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.40);
      background: transparent;
    }

    /* Left branding panel */
    .brand-panel {
      position: relative;
      background: radial-gradient(1200px 700px at -10% -10%, rgba(99, 102, 241, 0.45), transparent 60%),
                  radial-gradient(900px 600px at 110% 10%, rgba(34, 211, 238, 0.35), transparent 60%),
                  linear-gradient(135deg, rgba(29, 78, 216, 0.65), rgba(14, 165, 233, 0.45));
      color: #fff;
      padding: 56px 56px 40px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
    }

    .brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 999px;
      padding: 6px 12px;
      font-weight: 600;
      letter-spacing: 0.3px;
    }

    .brand-title {
      font-weight: 800;
      font-size: 40px;
      line-height: 1.1;
      margin: 20px 0 12px;
      letter-spacing: -0.5px;
    }

    .brand-sub {
      color: rgba(255, 255, 255, 0.9);
      font-size: 16px;
    }

    .brand-blobs {
      position: absolute;
      inset: 0;
      pointer-events: none;
      opacity: 0.15;
    }

    .brand-blobs::before,
    .brand-blobs::after {
      content: "";
      position: absolute;
      width: 280px;
      height: 280px;
      border-radius: 999px;
      filter: blur(30px);
    }

    .brand-blobs::before {
      left: -80px;
      top: 40px;
      background: #22d3ee;
      opacity: 0.8;
    }

    .brand-blobs::after {
      right: -60px;
      bottom: -60px;
      background: #818cf8;
      opacity: 0.9;
    }

    /* Right form panel */
    .form-panel {
      background: var(--card-bg);
      padding: 56px 56px 40px;
    }

    .logo-circle {
      width: 56px;
      height: 56px;
      border-radius: 999px;
      display: grid;
      place-items: center;
      background: radial-gradient(circle at 30% 30%, #a5b4fc, #6366f1);
      color: #fff;
      box-shadow: 0 10px 25px rgba(99, 102, 241, 0.45);
    }

    .form-title {
      font-weight: 800;
      letter-spacing: -0.3px;
      color: #0f172a;
    }

    .hint {
      color: #6b7280;
      font-size: 14px;
    }

    .input-group-neo .form-control {
      border-radius: 14px;
      border: 1px solid #e5e7eb;
      padding: 14px 16px 14px 44px;
      background: #fff;
      transition: all .2s ease;
    }

    .input-group-neo .form-control:focus {
      border-color: #a5b4fc;
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    .input-group-neo .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
    }

    .btn-brand {
      border-radius: 14px;
      font-weight: 700;
      letter-spacing: 0.3px;
      padding: 12px 16px;
      background: linear-gradient(90deg, var(--brand), var(--brand-2));
      border: none;
      box-shadow: 0 12px 22px rgba(79, 70, 229, 0.35);
    }

    .btn-brand:hover {
      transform: translateY(-1px);
      box-shadow: 0 16px 28px rgba(79, 70, 229, 0.45);
    }

    .divider {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      align-items: center;
      gap: 12px;
      color: #9ca3af;
      font-size: 12px;
      letter-spacing: .3px;
      margin: 18px 0 8px;
    }

    .divider::before,
    .divider::after {
      content: "";
      height: 1px;
      background: #e5e7eb;
    }

    .link-muted {
      color: #6b7280;
    }

    @media (max-width: 992px) {
      .auth-wrapper {
        grid-template-columns: 1fr;
        border-radius: 18px;
      }
      .brand-panel {
        display: none; /* Hide brand panel on small screens for focus */
      }
      .form-panel {
        padding: 40px 28px;
      }
    }
  </style>
</head>

<body class="login-gradient">
  <div class="container py-5">
    <div class="auth-wrapper mx-auto" style="max-width: 1040px;">

      <!-- Left: Branding -->
      <aside class="brand-panel">
        <div>
          
          <h1 class="brand-title">Selamat Datang 👋<br>KLINIK NARA DENTAL PRIORITY</h1>
          <p class="brand-sub">Kelola antrian, jadwal, dan layanan dengan mudah. Aman, cepat, dan responsif di semua perangkat.</p>
        </div>
        <div>
          <div class="d-flex align-items-center" style="gap: 12px;">
            
            <div>
              <div class="font-weight-bold">Klinik Nara</div>
              <div style="font-size:12px; opacity:.9;">Healthcare Management System</div>
            </div>
          </div>
        </div>
        <div class="brand-blobs"></div>
      </aside>

      <!-- Right: Form -->
      <section class="form-panel">
        <div class="mb-4 d-flex align-items-center" style="gap: 12px;">
          
          <div>
            <h2 class="h4 mb-0 form-title">Masuk ke Akun Anda</h2>
            <div class="hint">Silakan login untuk melanjutkan</div>
          </div>
        </div>

        <form class="user" method="POST" action="{{ route('login') }}" enctype="application/x-www-form-urlencoded" novalidate>
          @csrf

          <!-- Email -->
          <div class="form-group position-relative input-group-neo mb-3">
            <span class="input-icon"><i class="fas fa-envelope"></i></span>
            <input type="email"
                   class="form-control form-control-user @error('email') is-invalid @enderror"
                   name="email"
                   value="{{ old('email') }}"
                   required autocomplete="email"
                   placeholder="Email address">
            @error('email')
              <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Password -->
          <div class="form-group position-relative input-group-neo mb-3">
            <span class="input-icon"><i class="fas fa-lock"></i></span>
            <input type="password"
                   class="form-control form-control-user @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password"
                   placeholder="Password">
            @error('password')
              <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Remember + Forgot -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="custom-control custom-checkbox small">
              <input type="checkbox" name="remember" class="custom-control-input" id="rememberCheck" {{ old('remember') ? 'checked' : '' }}>
              <label class="custom-control-label" for="rememberCheck">Ingat saya</label>
            </div>
            {{-- <a href="{{ route('password.request') }}" class="small">Lupa password?</a> --}}
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary btn-user btn-block btn-brand">
            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
          </button>

          <!-- Divider -->
          <div class="divider mt-4 mb-3">atau</div>

          <!-- Secondary actions -->
          <div class="text-center">
            <span class="hint">Belum punya akun?</span>
            <a class="small" href="{{ route('register') }}">Daftar sekarang</a>
          </div>
        </form>

        <!-- Trust badges / footer -->
        <div class="mt-4 d-flex align-items-center" style="gap:12px;">
          <i class="fas fa-lock" aria-hidden="true"></i>
          <small class="text-muted">Data Anda terlindungi dengan enkripsi & kebijakan privasi yang ketat.</small>
        </div>
      </section>

    </div>
  </div>

  <!-- JS -->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <!-- jQuery Easing -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
  <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>
</html>
