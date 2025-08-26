@extends('layouts.main')

@section('content')
<!-- Main Content -->
<div id="content">
  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
      <i class="fa fa-bars"></i>
    </button>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
      <div class="topbar-divider d-none d-sm-block"></div>
      <!-- Nav Item - User Information -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
          <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
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

  <!-- Begin Page Content -->
  <div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-3 font-weight-bold text-gray-800">Jadwal Dokter</h1>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h5 class="font-weight-bold text-primary">Tambah Jadwal</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('jadwal.store') }}">
          @csrf
          
          <!-- Hari Praktek -->
          <div class="form-group">
            <label for="hari">Hari Praktek</label>
            <select class="form-control @error('hari') is-invalid @enderror" name="hari" id="hari">
              <option value="">-- Pilih Hari --</option>
              <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
              <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
              <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
              <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
              <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
              <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
              <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
            </select>
            @error('hari')
              <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Waktu Mulai -->
          <div class="form-group">
            <label for="waktu_mulai">Waktu Mulai</label>
            <select class="form-control @error('waktu_mulai') is-invalid @enderror" id="waktu_mulai" name="waktu_mulai">
              <option value="">-- Pilih Waktu Mulai --</option>
              @foreach($timeSlots as $slot)
                <option value="{{ $slot['mulai'] }}" {{ old('waktu_mulai') == $slot['mulai'] ? 'selected' : '' }}>
                  {{ $slot['mulai'] }} - {{ $slot['selesai'] }}
                </option>
              @endforeach
            </select>
            @error('waktu_mulai')
              <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <!-- Waktu Selesai -->
          <div class="form-group">
            <label for="waktu_selesai">Waktu Selesai</label>
           <select class="form-control @error('waktu_selesai') is-invalid @enderror" id="waktu_selesai" name="waktu_selesai">
              <option value="">-- Pilih Waktu Selesai --</option>
              @foreach($timeSlots as $slot)
                <option value="{{ $slot['selesai'] }}" {{ old('waktu_selesai') == $slot['selesai'] ? 'selected' : '' }}>
                  {{ $slot['mulai'] }} - {{ $slot['selesai'] }}
                </option>
              @endforeach
            </select>
            @error('waktu_selesai')
              <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <button type="submit" class="btn btn-info">Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->
@endsection
