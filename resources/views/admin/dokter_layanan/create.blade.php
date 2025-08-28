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
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
      </li>
    </ul>
  </nav>

  <div class="container-fluid">
    <h1 class="h3 mb-3 font-weight-bold text-gray-800">Dokter Layanan</h1>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h5 class="font-weight-bold text-primary">Tambah Dokter Layanan</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('dokter_layanan.store') }}">
          @csrf

          <div class="form-group">
            <label for="dokter_id">Dokter</label>
            <select class="form-control @error('dokter_id') is-invalid @enderror" name="dokter_id" id="dokter_id">
              <option value="">-- Pilih Dokter --</option>
              @foreach($dokters as $dokter)
                <option value="{{ $dokter->id }}" {{ old('dokter_id') == $dokter->id ? 'selected' : '' }}>{{ $dokter->nama }}</option>
              @endforeach
            </select>
            @error('dokter_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
          </div>

          <div class="form-group">
            <label for="layanan_id">Layanan</label>
            <select class="form-control @error('layanan_id') is-invalid @enderror" name="layanan_id" id="layanan_id">
              <option value="">-- Pilih Layanan --</option>
              @foreach($layanans as $layanan)
                <option value="{{ $layanan->id }}" {{ old('layanan_id') == $layanan->id ? 'selected' : '' }}>{{ $layanan->nama }}</option>
              @endforeach
            </select>
            @error('layanan_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
          </div>

          <div class="form-group">
            <label for="jadwal_id">Jadwal</label>
            <select class="form-control @error('jadwal_id') is-invalid @enderror" name="jadwal_id" id="jadwal_id">
              <option value="">-- Pilih Jadwal --</option>
              @foreach($jadwals as $jadwal)
                <option value="{{ $jadwal->id }}" {{ old('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                  {{ $jadwal->hari }} {{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai }}
                </option>
              @endforeach
            </select>
            @error('jadwal_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
          </div>

          <button type="submit" class="btn btn-info">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection