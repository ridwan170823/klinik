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
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
        Selamat Datang, {{ Auth::user()->name }}!
      </h1>
      {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>
    <!-- Content Row -->
    <div class="row">
      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Jumlah Dokter</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dokter }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Jumlah Antrian
                </div>
                @if (Auth::user()->role == 'dokter' && $perjanjians->count())
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ $perjanjians->count() }}
                </div>
                @elseif (Auth::user()->role == 'admin')
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ $antrian }}
                </div>
                @else
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ $antrian }}
                </div>
                @endif
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Earnings (Monthly) Card Example
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jenis Obat
                </div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                   
                  </div>
                  <div class="col">

                  </div>
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-2x fa-prescription-bottle-alt"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <!-- Content Row -->
    <div class="row">
      <!-- Area Chart -->
      <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
          @if (Auth::user()->role == 'dokter' && $perjanjians->count())
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Perjanjian dengan pasien</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Nama Pasien</th>
                    <th>Nama Dokter</th>
                    <th>Spesialisasi Dokter</th>
                    <th>Waktu Perjanjian</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Nama Pasien</th>
                    <th>Nama Dokter</th>
                    <th>Spesialisasi Dokter</th>
                    <th>Waktu Perjanjian</th>
                  </tr>
                </tfoot>
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
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @if (Auth::user()->role == 'pasien')
    <div class="row">
      <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Dokter &amp; Jadwal Praktik</h6>
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
                      <img src="{{ $dokterImage }}" alt="Foto {{ $dokterItem->nama }}"
                        class="rounded shadow-sm"
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
<!-- End of Main Content -->
@endsection