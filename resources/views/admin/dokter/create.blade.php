@extends('layouts.main')

@section('content')
<!-- Main Content -->
<div id="content">
  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <form class="form-inline">
      <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
      </button>
    </form>
    <ul class="navbar-nav ml-auto">
      <div class="topbar-divider d-none d-sm-block"></div>
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
          <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('logout') }}"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- End of Topbar -->

  <div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800 font-weight-bold">Tambah Dokter Baru</h1>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h5 class="font-weight-bold text-primary">Biodata Dokter</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('dokter.store') }}" enctype="multipart/form-data">
          @csrf

          <!-- Nama Dokter -->
          <div class="form-group">
            <label for="nama">Nama Dokter</label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
              name="nama" placeholder="Nama Dokter" value="{{ old('nama') }}">
            @error('nama')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Spesialis -->
          <div class="form-group">
            <label for="spesialis">Spesialis</label>
            <input type="text" class="form-control @error('spesialis') is-invalid @enderror" id="spesialis"
              name="spesialis" placeholder="Contoh: Spesialis Bedah Mulut" value="{{ old('spesialis') }}">
            @error('spesialis')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <select name="jadwal_id" id="jadwal_id" class="form-control @error('jadwal_id') is-invalid @enderror">
  @foreach($jadwals as $jadwal)
    <option value="{{ $jadwal->id }}">
      {{ $jadwal->hari }} ({{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai }})
    </option>
  @endforeach
</select>


          <!-- Foto Dokter -->
          <div class="form-group">
            <label for="image">Foto Dokter</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
            @error('image')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <button type="submit" class="btn btn-info">Tambah</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
