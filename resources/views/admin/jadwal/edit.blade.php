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
    <h1 class="h3 mb-3 text-gray-800">Edit Jadwal</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-body">
        <form method="POST" action="{{ route('jadwal.update', $jadwal->id) }}">
          @method('put')
          @csrf

          <div class="form-group">
            <label for="hari">Hari Praktek</label>
            <input type="text" class="form-control" id="hari" name="hari" placeholder="Contoh: Senin"
              value="{{ old('hari', $jadwal->hari) }}">
          </div>

          <div class="form-group">
            <label for="waktu_mulai">Waktu Mulai</label>
            <select class="form-control" id="waktu_mulai" name="waktu_mulai">
              @foreach($timeSlots as $slot)
                <option value="{{ $slot['mulai'] }}" {{ old('waktu_mulai', $jadwal->waktu_mulai) == $slot['mulai'] ? 'selected' : '' }}>
                  {{ $slot['mulai'] }} - {{ $slot['selesai'] }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="waktu_selesai">Waktu Selesai</label>
            <select class="form-control" id="waktu_selesai" name="waktu_selesai">
              @foreach($timeSlots as $slot)
                <option value="{{ $slot['selesai'] }}" {{ old('waktu_selesai', $jadwal->waktu_selesai) == $slot['selesai'] ? 'selected' : '' }}>
                  {{ $slot['mulai'] }} - {{ $slot['selesai'] }}
                </option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-info">Update</button>
        </form>
      </div>
    </div>
  </div>
  <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->
@endsection
