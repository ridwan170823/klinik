@extends('layouts.main')

@section('content')
<!-- Main Content -->
<div id="content">
  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
      <i class="fa fa-bars"></i>
    </button>
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

  <!-- Begin Page Content -->
  <div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-3 font-weight-bold text-gray-800">Manajemen Dokter</h1>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h5 class="font-weight-bold text-primary">
          Daftar Dokter
          <span>
            <a href="{{ route('dokter.create') }}" class="btn ml-4 btn-primary font-weight-bold">
              + Tambah Dokter
            </a>
          </span>
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Dokter</th>
                <th>Spesialis</th>
                <th>Jadwal Praktek</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($dokters as $index => $dokter)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  @if($dokter->image)
                    <img src="{{ asset('storage/' . $dokter->image) }}" alt="Foto Dokter" width="50" height="50">
                  @else
                    <img src="{{ asset('img/undraw_profile.svg') }}" alt="Foto Dokter" width="50" height="50">
                  @endif
                </td>
                <td>{{ $dokter->nama }}</td>
                <td>{{ $dokter->spesialis }}</td>
               <td>
  @if($dokter->jadwals->isNotEmpty())
    {{ $dokter->jadwals->map(function($j){
         return $j->hari.' ('.\Carbon\Carbon::parse($j->waktu_mulai)->format('H:i').' - '.\Carbon\Carbon::parse($j->waktu_selesai)->format('H:i').')';
       })->implode(', ') }}
  @else
    -
  @endif
</td>
                <td>
                  <a href="{{ route('dokter.edit', $dokter->id) }}" class="btn btn-warning btn-sm">Edit</a>
                  <form action="{{ route('dokter.destroy', $dokter->id) }}" method="post" class="d-inline">
                    @method('delete')
                    @csrf
                    <button onclick="return confirm('Yakin hapus dokter ini?')" class="btn btn-danger btn-sm" type="submit">Hapus</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->
@endsection
