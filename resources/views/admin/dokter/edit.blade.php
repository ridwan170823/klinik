@extends('layouts.main')

@section('content')
<!-- Main Content -->
<div id="content">

  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <form class="form-inline">
      <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
      </button>
    </form>


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
    <h1 class="h3 mb-3 text-gray-800">Dokter</h1>
    <p>Edit biodata dokter</p>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-body">
        <form method="POST"
      action="{{ route('dokter.update', $dokter->id) }}"
      enctype="multipart/form-data">
  @csrf
  @method('PUT')

  {{-- Tampilkan error validasi --}}
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- NAMA --}}
  <div class="form-group mb-3">
    <label for="nama">Nama Dokter</label>
    <input type="text" class="form-control" id="nama" name="nama"
           value="{{ old('nama', $dokter->nama) }}" required>
  </div>

  {{-- SPESIALIS --}}
  <div class="form-group mb-3">
    <label for="spesialis">Spesialis</label>
    <input type="text" class="form-control" id="spesialis" name="spesialis"
           value="{{ old('spesialis', $dokter->spesialis) }}" required>
  </div>

  {{-- JADWAL (many-to-many) --}}
  <div class="form-group mb-3">
    <label for="jadwal_id">Pilih Jadwal</label>
    <select id="jadwal_id" name="jadwal_id[]" class="form-control" multiple>
      @php
        // Ambil id jadwal yang sudah ter-attach ke dokter untuk preselect
        $selected = old('jadwal_id', $dokter->jadwals->pluck('id')->toArray());
      @endphp
      @foreach($jadwals as $j)
        <option value="{{ $j->id }}"
          {{ in_array($j->id, $selected ?? []) ? 'selected' : '' }}>
          {{ $j->hari }} ({{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i') }})
        </option>
      @endforeach
    </select>
    <small class="text-muted">Tahan Ctrl/Cmd untuk memilih lebih dari satu.</small>
  </div>

  {{-- IMAGE (preview + upload baru opsional) --}}
  <div class="form-group mb-3">
    <label>Foto Dokter (opsional)</label>
    @if($dokter->image)
      <div class="mb-2">
        <img src="{{ asset('storage/'.$dokter->image) }}" alt="Foto {{ $dokter->nama }}" style="max-height: 120px;">
      </div>
    @endif
    <input type="file" class="form-control" name="image" accept=".jpg,.jpeg,.png">
    <small class="text-muted d-block">Biarkan kosong jika tidak ingin mengganti.</small>
  </div>

  <button type="submit" class="btn btn-info">Update</button>
  <a href="{{ route('dokter.index') }}" class="btn btn-secondary">Batal</a>
</form>

      </div>
    </div>

  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
@endsection