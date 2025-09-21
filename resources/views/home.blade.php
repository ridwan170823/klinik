@extends('layouts.main')

@php
    use Carbon\Carbon;
    $today = Carbon::now()->locale('id')->translatedFormat('l, d F Y');
    $perjanjianCount = isset($perjanjians) ? $perjanjians->count() : 0;
    $role = Auth::user()->role ?? null;
    $isDoctor = $role === 'dokter';
    $isPatient = $role === 'pasien';
    $queueCount = $antrian;
    if ($isDoctor && $perjanjianCount) {
        $queueCount = $perjanjianCount;
    }
    
@endphp

@section('content')
<!-- Main Content -->
<div id="content">
  <!-- Topbar -->
  <nav class="navbar navbar-expand topbar modern-topbar shadow-sm">
    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3 d-lg-none">
      <i class="fa fa-bars"></i>
    </button>
    <div class="d-flex align-items-center">
      <div class="d-none d-md-block mr-3 text-muted small">Akun aktif</div>
      <div>
        <h6 class="mb-0 font-weight-bold">{{ Auth::user()->name }}</h6>
        <span class="text-muted small">{{ ucfirst(Auth::user()->role) }}</span>
      </div>
    </div>
    <ul class="navbar-nav ml-auto align-items-center">
       @include('layouts.partials.notification-dropdown')
      <li class="nav-item d-none d-lg-block mr-3 text-muted small">
        {{ $isDoctor ? 'Tetap produktif dan layani pasien terbaik hari ini ✨' : 'Tetap jaga kesehatan dan pantau kunjungan Anda di sini 🌿' }}
      </li>
      <li class="nav-item dropdown no-arrow">
         <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <img class="img-profile rounded-circle mr-2" src="{{ asset('img/undraw_profile.svg') }}" alt="Avatar">
          <span class="font-weight-semibold">{{ Auth::user()->name }}</span>
        </a>
        <!-- Dropdown - User Information -->
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
  <!-- End of Topbar -->
  <!-- Begin Page Content -->
  <div class="container-fluid">
    <div class="content-header">
      <div>
        <h1 class="page-title">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h1>
        <p class="subtitle mb-0">{{ $isDoctor ? 'Pantau performa klinik dan aktivitas terbaru Anda hari ini.' : 'Kelola reservasi dan jadwal kunjungan Anda dengan mudah.' }}</p>
      </div>
      <div class="date-badge">
        {{ ucfirst($today) }}
      </div>
    </div>
       <div class="row stats-grid">
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="stats-card">
          <span class="label">Jumlah Dokter</span>
          <div class="value">{{ $dokter }}</div>
          <p class="mb-0 small">Tim medis aktif yang siap memberikan perawatan terbaik.</p>
          <i class="fas fa-user-md"></i>
        </div>
      </div>
      <div class="col-xl-4 col-md-6 mb-4">

        <div class="stats-card secondary">
          <span class="label">Antrian Aktif</span>
          <div class="value">{{ $queueCount }}</div>
          <p class="mb-0 small">Jumlah layanan yang akan ditangani pada jadwal hari ini.</p>
          <i class="fas fa-calendar-check"></i>
        </div>
      </div>
      <div class="col-xl-4 col-md-12 mb-4">
        <div class="stats-card neutral">
          <span class="label">Jadwal Terjadwal</span>
          <div class="value">{{ $jadwalCount }}</div>
          <p class="mb-0 small">Pertemuan dan jadwal praktik yang telah dikonfirmasi.</p>
          <i class="fas fa-clock"></i>
        </div>
      </div>
    </div>
    <!-- Content Row -->
   @if ($isDoctor)
      <div class="row">
        <div class="col-xl-12 col-lg-12">
          <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Perjanjian dengan Pasien</h6>
              <span class="badge badge-info">Update realtime</span>
            </div>
            <div class="card-body">
              @if ($perjanjianCount)
                <div class="table-responsive">
                  <table class="table" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Nama Pasien</th>
                        <th>Nama Dokter</th>
                        <th>Spesialisasi Dokter</th>
                        <th>Waktu Perjanjian</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($perjanjians as $perjanjian)
                        <tr>
                          <td>{{ $perjanjian->nama_pasien }}</td>
                          <td>{{ $perjanjian->nama_dokter }}</td>
                          <td>{{ $perjanjian->spesialiasi_dokter }}</td>
                          <td>{{ $perjanjian->waktu_perjanjian }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="text-center py-5 text-muted">
                  <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                  <p class="mb-1 font-weight-semibold">Belum ada perjanjian aktif.</p>
                  <p class="mb-0">Semua jadwal akan tampil otomatis ketika pasien melakukan reservasi.</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
 @endif

    @if ($isPatient)
      <div class="row">
      <div class="col-xl-12 col-lg-12">
        <div class="card shadow-sm mb-4">
          <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
              <h6 class="m-0 font-weight-bold text-primary">Dokter &amp; Jadwal Praktik</h6>
              <small class="text-muted">Pilih jadwal terbaik dan lakukan reservasi secara instan.</small>
            </div>
            <span class="badge badge-success mt-3 mt-md-0">Terupdate otomatis</span>
          </div>
          <div class="card-body">
            @forelse ($dokterSchedules as $dokterItem)
             @php
                $dokterImage = $dokterItem->image
                  ? asset('storage/' . $dokterItem->image)
                  : asset('img/undraw_profile.svg');
              @endphp
              <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                  <div class="row align-items-start">
                    <div class="col-md-3 text-center text-md-left mb-3 mb-md-0">
                      <img src="{{ $dokterImage }}" alt="Foto {{ $dokterItem->nama }}" class="rounded shadow-sm"
                        style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                   <div class="col-md-9">
                      <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-3">
                        <div>
                          <h5 class="font-weight-bold text-primary mb-1">{{ $dokterItem->nama }}</h5>
                          <span class="badge badge-pill badge-info text-uppercase">{{ $dokterItem->spesialis }}</span>
                        </div>
                        <div class="text-muted small mt-2 mt-lg-0">
                          Terakhir diperbarui {{ optional($dokterItem->updated_at)->diffForHumans() ?? 'belum tersedia' }}
                        </div>
                      </div>
                      @if ($dokterItem->biografi)
                        <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($dokterItem->biografi, 160) }}</p>
                      @endif
                      @if ($dokterItem->layananJadwals->count())
                        <div class="table-responsive">
                          <table class="table table-sm table-hover table-borderless mb-0 align-middle">
                            <thead class="bg-light text-muted">
                              <tr>
                                <th class="border-0">Hari</th>
                                <th class="border-0">Waktu</th>
                                <th class="border-0 text-center">Sisa Kapasitas</th>
                                <th class="border-0 text-right">Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($dokterItem->layananJadwals as $jadwal)
                                @php
                                  $layananId = optional($jadwal->pivot)->layanan_id;
                                  $isAvailable = $jadwal->kapasitas > 0 && $layananId;
                                @endphp
                                <tr class="{{ $jadwal->kapasitas <= 0 ? 'text-muted bg-light' : '' }}">
                                  <td class="font-weight-bold">{{ $jadwal->hari }}</td>
                                  <td>{{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }}</td>
                                  <td class="text-center">
                                    @if ($jadwal->kapasitas > 0)
                                      <span class="badge badge-success badge-pill">{{ $jadwal->kapasitas }} slot</span>
                                    @else
                                      <span class="badge badge-secondary badge-pill">Penuh</span>
                                    @endif
                                  </td>
                                  <td class="text-right">
                                    @if ($isAvailable)
                                      <a href="{{ route('antrian.index', ['dokter_id' => $dokterItem->id, 'hari' => $jadwal->hari, 'layanan_id' => $layananId]) }}"
                                        class="btn btn-sm btn-primary">Booking</a>
                                    @elseif ($jadwal->kapasitas > 0)
                                      <span class="badge badge-warning px-3 py-2">Layanan belum tersedia</span>
                                    @else
                                      <span class="badge badge-light px-3 py-2">Tidak tersedia</span>
                                    @endif
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      @else
                        <p class="mb-0 text-gray-500">Belum ada jadwal tersedia untuk dokter ini.</p>
                      @endif
                    </div>
                    
                  </div>
                </div>
              </div>
            @empty
            <p class="mb-0 text-gray-500">Belum ada dokter yang tersedia saat ini.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
    @endif
  <!-- /.container-fluid -->
  </div>
  <!-- End of Page Content -->
@endsection