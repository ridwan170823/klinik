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
        <h5 class="font-weight-bold text-primary">Daftar Dokter Layanan
          <span>
            <a href="{{ route('dokter_layanan.create') }}" class="btn ml-4 btn-primary font-weight-bold">
              + Tambah
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
                <th>Dokter</th>
                <th>Layanan</th>
                <th>Jadwal</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($dokterLayanans as $index => $dl)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $dl->dokter_nama }}</td>
                <td>{{ $dl->layanan_nama }}</td>
                <td>{{ $dl->hari }} {{ $dl->waktu_mulai }} - {{ $dl->waktu_selesai }}</td>
                <td>
                  <a href="{{ route('dokter_layanan.edit', $dl->id) }}" class="btn btn-warning btn-sm">Edit</a>
                  <form action="{{ route('dokter_layanan.destroy', $dl->id) }}" method="post" class="d-inline">
                    @method('delete')
                    @csrf
                    <button onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger btn-sm" type="submit">Hapus</button>
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
</div>
@endsection