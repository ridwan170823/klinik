@extends('layouts.main')

@section('content')
<div id="content">
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
      <i class="fa fa-bars"></i>
    </button>
    <ul class="navbar-nav ml-auto align-items-center">
      @include('layouts.partials.notification-dropdown')
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

  <div class="container-fluid">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
      <div>
        <h1 class="h3 font-weight-bold text-gray-800">Laporan Operasional</h1>
        <p class="mb-0 text-gray-600">Ringkasan kinerja klinik berdasarkan rentang tanggal terpilih.</p>
      </div>
      <form method="GET" class="form-inline mt-3 mt-md-0">
        <label class="mr-2 text-muted small">Rentang tanggal</label>
        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control mr-2 mb-2 mb-md-0">
        <span class="mx-2 d-none d-md-inline">s/d</span>
        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control mr-2 mb-2 mb-md-0">
        <button class="btn btn-primary" type="submit">Terapkan</button>
      </form>
    </div>

    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-3">
          <div class="card-body">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Antrian</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalQueue }}</div>
            <p class="mb-0 small text-muted">Dalam periode {{ $startDate }} - {{ $endDate }}.</p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-3">
          <div class="card-body">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Disetujui</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $approvedQueue }}</div>
            <p class="mb-0 small text-muted">Antrian dengan status approved.</p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-3">
          <div class="card-body">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $pendingQueue }}</div>
            <p class="mb-0 small text-muted">Antrian dengan status pending.</p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-3">
          <div class="card-body">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pendapatan</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <p class="mb-0 small text-muted">Transaksi berhasil dalam periode.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow h-100">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Statistik Pengguna</h6>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Total Pasien</span>
              <span class="font-weight-bold">{{ $totalPatients }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Total Dokter</span>
              <span class="font-weight-bold">{{ $totalDoctors }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-muted">Total Layanan</span>
              <span class="font-weight-bold">{{ $totalServices }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow h-100">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Layanan Teratas</h6>
          </div>
          <div class="card-body">
            @forelse ($services as $service)
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                <span>{{ $service->layanan->nama ?? 'Layanan tidak diketahui' }}</span>
                <span class="badge badge-primary">{{ $service->total }}</span>
              </div>
            @empty
              <p class="text-center text-muted mb-0">Belum ada data layanan pada periode ini.</p>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-xl-4 col-md-12 mb-4">
        <div class="card shadow h-100">
          <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Jumlah Antrian per Hari</h6>
          </div>
          <div class="card-body">
            @forelse ($dailyQueue as $day)
              <div class="mb-3">
                <div class="d-flex justify-content-between small text-muted">
                  <span>{{ \Carbon\Carbon::parse($day->tanggal)->locale(app()->getLocale())->translatedFormat('d F Y') }}</span>
                  <span>{{ $day->total }} antrian</span>
                </div>
                <div class="progress" style="height: 6px;">
                  <div class="progress-bar bg-info" role="progressbar"
                       style="width: {{ min(100, $totalQueue > 0 ? round(($day->total / max($totalQueue, 1)) * 100, 2) : 0) }}%"></div>
                </div>
              </div>
            @empty
              <p class="text-center text-muted mb-0">Belum ada data antrian pada periode ini.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">10 Antrian Terbaru</h6>
        <a href="{{ route('antrian.index') }}" class="btn btn-sm btn-outline-primary">Kelola Antrian</a>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Nomor</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($latestQueue as $queue)
                <tr>
                  <td>{{ $queue->nomor_antrian ?? '-' }}</td>
                  <td>{{ $queue->user->name ?? '-' }}</td>
                  <td>{{ $queue->dokter->nama ?? '-' }}</td>
                  <td>{{ $queue->layanan->nama ?? '-' }}</td>
                  <td>{{ $queue->tanggal ? \Carbon\Carbon::parse($queue->tanggal)->locale(app()->getLocale())->translatedFormat('d F Y') : '-' }}</td>
                  <td>
                    <span class="badge badge-{{ $queue->status === 'approved' ? 'success' : ($queue->status === 'pending' ? 'warning' : 'secondary') }}">
                      {{ ucfirst($queue->status) }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted">Belum ada data antrian dalam periode ini.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection