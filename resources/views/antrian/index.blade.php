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
    <h1 class="h3 mb-3 text-gray-800">Daftar Antrian</h1>
    <div class="card shadow mb-4">
      @if (Auth::user()->role == 'pasien')
      <div class="card-header py-3">
        <form method="POST" action="{{ route('antrian.store') }}">
          @csrf
          <div class="form-row">
            <div class="col">
              <select class="form-control" name="dokter_id" id="dokterSelect" required>
                <option value="" disabled selected>Pilih Dokter</option>
                @foreach ($dokters as $dokter)
                <option value="{{ $dokter->id }}" data-jadwals='@json($dokter->jadwals)'>{{ $dokter->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="col">
              <select class="form-control" name="jadwal_id" id="jadwalSelect" required>
                <option value="" disabled selected>Pilih Jadwal</option>
              </select>
            </div>
            <div class="col">
              <button type="submit" class="btn btn-primary font-weight-bold">Ambil Antrian</button>
            </div>
          </div>
        </form>
      </div>
      @endif
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No Antrian</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Jadwal</th>
                <th>Status</th>
                @if (Auth::user()->role == 'admin')
                <th>Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach ($antrians as $antrian)
              <tr>
                <td>{{ $antrian->nomor_antrian }}</td>
                <td>{{ $antrian->user->name }}</td>
                <td>{{ $antrian->dokter->nama }}</td>
                <td>{{ $antrian->jadwal->hari }} {{ $antrian->jadwal->waktu_mulai }}-{{ $antrian->jadwal->waktu_selesai }}</td>
                <td>{{ ucfirst($antrian->status) }}</td>
                @if (Auth::user()->role == 'admin')
                <td>
                  @if ($antrian->status === 'pending')
                  <form action="{{ route('antrian.approve', $antrian->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                  </form>
                  @endif
                  <form action="{{ route('antrian.destroy', $antrian->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                  </form>
                </td>
                @endif
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const dokterSelect = document.getElementById('dokterSelect');
  const jadwalSelect = document.getElementById('jadwalSelect');

  if (!dokterSelect) return;

  function updateJadwals() {
    const selected = dokterSelect.options[dokterSelect.selectedIndex];
    const jadwals = selected ? JSON.parse(selected.getAttribute('data-jadwals')) : [];
    jadwalSelect.innerHTML = '<option value="" disabled selected>Pilih Jadwal</option>';
    jadwals.forEach(j => {
      const opt = document.createElement('option');
      opt.value = j.id;
      opt.text = j.hari + ' ' + j.waktu_mulai + '-' + j.waktu_selesai;
      jadwalSelect.appendChild(opt);
    });
  }

  dokterSelect.addEventListener('change', updateJadwals);
});
</script>
@endsection