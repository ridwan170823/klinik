@extends('layouts.main')

@section('content')
<div id="content">
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
      <i class="fa fa-bars"></i>
    </button>
    <ul class="navbar-nav ml-auto">
      <div class="topbar-divider d-none d-sm-block"></div>
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
          <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>

  <div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800 font-weight-bold">Detail Dokter</h1>

    <div class="card shadow mb-4">
      <div class="card-body">
        @if($dokter->image)
          <div class="mb-3">
            <img src="{{ asset('storage/'.$dokter->image) }}" alt="Foto {{ $dokter->nama }}" style="max-height: 150px;">
          </div>
        @endif
        <p><strong>Nama:</strong> {{ $dokter->nama }}</p>
        <p><strong>Spesialis:</strong> {{ $dokter->spesialis }}</p>
        <p><strong>Telepon:</strong> {{ $dokter->telepon ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $dokter->alamat ?? '-' }}</p>
        <p><strong>Biografi:</strong> {{ $dokter->biografi ?? '-' }}</p>
        <p><strong>Jadwal Praktek:</strong>
          @if($dokter->jadwals->isNotEmpty())
            {{ $dokter->jadwals->map(function($j){ return $j->hari.' ('.\Carbon\Carbon::parse($j->waktu_mulai)->format('H:i').' - '.\Carbon\Carbon::parse($j->waktu_selesai)->format('H:i').')'; })->implode(', ') }}
          @else
            -
          @endif
        </p>
        <a href="{{ route('dokter.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>
</div>
@endsection