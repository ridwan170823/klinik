<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name', 'Laravel'))</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;800&display=swap" rel="stylesheet">

  {{-- Styles --}}
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @stack('styles')

  <style>
    :root{ --brand:#4f46e5; --brand-2:#22c55e; }
    body{ font-family:'Nunito',system-ui,-apple-system,Segoe UI,Roboto,sans-serif; }
    .navbar{ border-bottom:1px solid rgba(0,0,0,.05); }
    .navbar-brand{ font-weight:800; letter-spacing:.2px; }

    /* Hero */
    .page-hero{
      background: linear-gradient(140deg,var(--brand) 0%,#7c3aed 50%,var(--brand-2) 100%);
      color:#fff; padding:48px 0 28px; position:relative; overflow:hidden;
    }
    .page-hero .title{ font-weight:800; font-size:1.6rem; margin:0; }
    .page-hero .subtitle{ opacity:.9; margin-top:6px; }
    .crumb a{ color:#fff; opacity:.9; text-decoration:underline; }
    .crumb .sep{ opacity:.6; padding:0 6px; }

    /* Card feel */
    .content-wrap{ margin-top:-24px; }
    .card{ border:0; box-shadow:0 10px 25px rgba(0,0,0,.06); border-radius:16px; }
    .card-header{ background:#fff; border-bottom:1px solid #f1f1f1; border-top-left-radius:16px; border-top-right-radius:16px; }

    /* Footer */
    .app-footer{ border-top:1px solid #f1f1f1; padding:16px 0; color:#6b7280; background:#fff; }

    /* Alerts */
    .fade-out{ animation:fadeOut .6s ease 3s forwards; }
    @keyframes fadeOut{ to{ opacity:0; transform:translateY(-6px);} }

    .img-profile{ width:36px; height:36px; object-fit:cover; }
    @media(min-width:1200px){ .container{ max-width:1140px; } }
  </style>
</head>
<body>
  <div id="app">
    {{-- Topbar --}}
    <nav class="navbar navbar-expand-md navbar-light bg-white fixed-top shadow-sm">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
          <span class="mr-2">🩺</span>{{ config('app.name', 'Laravel') }}
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
          <ul class="navbar-nav mr-auto">
            @yield('nav_left')
          </ul>

          <ul class="navbar-nav ml-auto">
            @guest
              @if (Route::has('login'))
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
              @endif
              @if (Route::has('register'))
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
              @endif
            @else
              @yield('nav_right_before')
              <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                   role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="img-profile rounded-circle mr-2" src="{{ asset('img/undraw_profile.svg') }}">
                  <span>{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  @yield('user_menu_items')
                  <a class="dropdown-item" href="{{ route('logout') }}"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
              </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    {{-- spacer for fixed navbar --}}
    <div style="height:70px;"></div>

    {{-- Page hero (bisa dimatikan per-halaman dengan @section('no_hero', true)) --}}
    @if (!View::hasSection('no_hero'))
      <section class="page-hero">
        <div class="container">
          <h1 class="title">@yield('page_title', 'Dashboard')</h1>
          <div class="subtitle">
            @hasSection('breadcrumb')
              <div class="crumb">@yield('breadcrumb')</div>
            @else
              <div class="crumb">
                <a href="{{ url('/') }}">Home</a> <span class="sep">/</span>
                <span>@yield('page_title', 'Dashboard')</span>
              </div>
            @endif
          </div>
        </div>
      </section>
    @endif

    {{-- Main --}}
    <main class="py-4">
      <div class="container content-wrap">
        @if (session('success'))
          <div class="alert alert-success fade-out">{{ session('success') }}</div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
          </div>
        @endif

        <div class="card">
          @hasSection('card_header')
            <div class="card-header">@yield('card_header')</div>
          @endif
          <div class="card-body">
            @yield('content')
          </div>
        </div>
      </div>
    </main>

    {{-- Footer --}}
    <footer class="app-footer">
      <div class="container d-flex justify-content-between align-items-center">
        <div>© {{ date('Y') }} {{ config('app.name') }} — All rights reserved.</div>
        <div><span class="text-muted">v{{ app()->version() }}</span></div>
      </div>
    </footer>
  </div>

  {{-- Scripts --}}
  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  @stack('scripts')
  <script>
    // fallback auto-hide flash
    setTimeout(()=>{const el=document.querySelector('.alert-success.fade-out'); if(el){el.style.display='none';}},3800);
  </script>
</body>
</html>
