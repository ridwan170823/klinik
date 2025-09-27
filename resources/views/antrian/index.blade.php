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
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
          <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}" alt="Foto Profil">
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
     @if (Auth::user()->role === 'pasien')
      <div class="card-header py-3">
        <form method="POST" action="{{ route('antrian.store') }}">
          @csrf
          <div class="form-row">
            
            <div class="col-12 col-xl-3 mb-2 mb-xl-0">
              <select class="form-control" name="layanan_id" id="layananSelect" required data-old-value="{{ old('layanan_id') }}">
                <option value="" disabled {{ old('layanan_id') ? '' : 'selected' }}>Pilih Layanan</option>
                @foreach ($layanans as $layanan)
              <option value="{{ $layanan->id }}" {{ (string) old('layanan_id') === (string) $layanan->id ? 'selected' : '' }}>
                    {{ $layanan->nama }} - Rp{{ number_format($layanan->harga) }}
                  </option>
                @endforeach
              </select>
              @error('layanan_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12 col-xl-3 mb-2 mb-xl-0">
              <select class="form-control" name="dokter_id" id="dokterSelect" required disabled data-old-value="{{ old('dokter_id') }}">
                <option value="" disabled selected>Pilih Dokter</option>
              </select>
               @error('dokter_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12 col-xl-2 mb-2 mb-xl-0">
              <select class="form-control" name="jadwal_id" id="jadwalSelect" required disabled data-old-value="{{ old('jadwal_id') }}">
                <option value="" disabled selected>Pilih Jadwal</option>
              </select>
              @error('jadwal_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12 col-xl-2 mb-2 mb-xl-0">
              <input type="date" class="form-control" name="tanggal" id="tanggalInput" required
                     min="{{ now()->toDateString() }}"
                     max="{{ now()->addDays(config('antrian.max_days_ahead'))->toDateString() }}"
                     value="{{ old('tanggal') }}">
              @error('tanggal')
            <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12 col-xl-2">
              <button type="submit" class="btn btn-primary font-weight-bold btn-block">Ambil Antrian</button>
            </div>
          </div>
        </form>
      </div>
      @endif
      <div class="card-body">
       @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        <form method="GET" action="{{ route('antrian.index') }}" class="form-row align-items-center mb-3">
          <div class="col-12 col-lg-3 mb-2 mb-lg-0">
            <select name="layanan_id" class="form-control w-100">
              <option value="">Semua Layanan</option>
              @foreach ($layanans as $layanan)
                <option value="{{ $layanan->id }}" {{ (string) $selectedLayanan === (string) $layanan->id ? 'selected' : '' }}>
                  {{ $layanan->nama }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-12 col-lg-3 mb-2 mb-lg-0">
            <select name="hari" class="form-control w-100">
              <option value="">Semua Hari</option>
              @foreach ($haris as $hari)
              <option value="{{ $hari }}" {{ $selectedHari == $hari ? 'selected' : '' }}>{{ $hari }}</option>
              @endforeach
            </select>
          </div>
           <div class="col-12 col-lg-3 mb-2 mb-lg-0">
            <select name="dokter_id" class="form-control w-100">
              <option value="">Semua Dokter</option>
              @foreach ($dokters as $dokter)
                <option value="{{ $dokter->id }}" {{ (string) $selectedDokter === (string) $dokter->id ? 'selected' : '' }}>{{ $dokter->nama }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 col-lg-3">
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
          </div>
           </form>
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No Antrian</th>
                <th>Nama Pasien</th>
                <th>Layanan</th>
                <th>Dokter</th>
                <th>Jadwal</th>
                <th>Status</th>
               @if (Auth::user()->role === 'admin')
                  <th>Aksi</th>
                @endif
              </tr>
            </thead>
            <tbody>
               @forelse ($antrians as $antrian)
                <tr>
                  <td>{{ $antrian->status === 'approved' ? $antrian->nomor_antrian : 'Menunggu' }}</td>
                  <td>{{ $antrian->user->name }}</td>
                  <td>{{ optional($antrian->layanan)->nama ?? '-' }}</td>
                  <td>{{ optional($antrian->dokter)->nama ?? '-' }}</td>
                  <td>
                    @if ($antrian->jadwal)
                      {{ $antrian->jadwal->hari }} {{ $antrian->jadwal->waktu_mulai }} - {{ $antrian->jadwal->waktu_selesai }}
                    @else
                      -
                    @endif
                  </td>
                  <td>{{ ucfirst($antrian->status) }}</td>
                  @if (Auth::user()->role === 'admin')
                    <td>
                      @if ($antrian->status === 'pending')
                        <form action="{{ route('antrian.approve', $antrian->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                        </form>
                      @endif
                      <form action="{{ route('antrian.destroy', $antrian->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus antrian ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                      </form>
                    </td>
                  @endif
                </tr>
              @empty
                <tr>
                  <td colspan="{{ Auth::user()->role === 'admin' ? 7 : 6 }}" class="text-center text-muted">Belum ada antrian untuk ditampilkan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const layananSelect = document.getElementById('layananSelect');
  const dokterSelect  = document.getElementById('dokterSelect');
  const jadwalSelect  = document.getElementById('jadwalSelect');
  const tanggalInput  = document.getElementById('tanggalInput');

  if (!layananSelect || !dokterSelect || !jadwalSelect) {
    return;
  }

  const initialValues = {
    layanan: layananSelect.dataset.oldValue || '',
    dokter: dokterSelect.dataset.oldValue || '',
    jadwal: jadwalSelect.dataset.oldValue || '',
  };


  const setPlaceholder = (select, text) => {
    select.innerHTML = '';
    const option = document.createElement('option');
    option.value = '';
    option.disabled = true;
    option.selected = true;
    option.textContent = text;
    select.appendChild(option);
  };

  const fillOptions = (select, items, labelFn) => {
    items.forEach(item => {
      const option = document.createElement('option');
      option.value = item.id;
      option.textContent = labelFn(item);
      select.appendChild(option);
    });
  };

  const fetchJson = async (url) => {
    const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
    if (!response.ok) {
      throw new Error(`${response.status} ${response.statusText}`);
    }

    return response.json();
  };

  const buildJadwalLabel = (jadwal) => {
    const waktu = `${jadwal.waktu_mulai} - ${jadwal.waktu_selesai}`;
    let suffix = '';

    if (typeof jadwal.sisa_kapasitas !== 'undefined' && jadwal.kapasitas > 0) {
      suffix = ` (Sisa ${jadwal.sisa_kapasitas})`;
    }

    return `${jadwal.hari} (${waktu})${suffix}`;
  };

  const loadJadwals = async () => {
    const layananId = layananSelect.value;
    const dokterId = dokterSelect.value;

    if (!layananId || !dokterId) {
      setPlaceholder(jadwalSelect, 'Pilih Jadwal');
      jadwalSelect.disabled = true;
      return;
    }

    setPlaceholder(jadwalSelect, 'Memuat jadwal…');
    jadwalSelect.disabled = true;

    try {
      const params = new URLSearchParams();
      if (tanggalInput && tanggalInput.value) {
        params.set('tanggal', tanggalInput.value);
      }

      const query = params.toString();
      const url = `/dokters/${encodeURIComponent(dokterId)}/layanans/${encodeURIComponent(layananId)}/jadwals${query ? `?${query}` : ''}`;
      const jadwals = await fetchJson(url);

      setPlaceholder(jadwalSelect, jadwals.length ? 'Pilih Jadwal' : 'Jadwal tidak tersedia');

      if (jadwals.length) {
        fillOptions(jadwalSelect, jadwals, buildJadwalLabel);
        jadwalSelect.disabled = false;

        if (initialValues.jadwal) {
          jadwalSelect.value = initialValues.jadwal;
          initialValues.jadwal = '';

          if (!jadwalSelect.value) {
            jadwalSelect.selectedIndex = 0;
          }
        }
      } else {
        jadwalSelect.disabled = true;
      }
    } catch (error) {
      console.error(error);
      setPlaceholder(jadwalSelect, 'Gagal memuat jadwal');
      jadwalSelect.disabled = true;
    }
  };

  // State awal
  setPlaceholder(dokterSelect, 'Pilih Dokter');
  setPlaceholder(jadwalSelect, 'Pilih Jadwal');
  dokterSelect.disabled = true;
  jadwalSelect.disabled = true;

  // Ketika pilih Layanan
  layananSelect.addEventListener('change', async function () {
    const layananId = this.value;

    setPlaceholder(dokterSelect, 'Memuat daftar dokter…');
    setPlaceholder(jadwalSelect, 'Pilih Jadwal');
    dokterSelect.disabled = true;
    jadwalSelect.disabled = true;
    if (!layananId) {
      return;
    }

    try {
      const dokters = await fetchJson(`/layanans/${encodeURIComponent(layananId)}/dokters`);
     const uniqueDokters = dokters.filter((dokter, index, array) => array.findIndex(item => item.id === dokter.id) === index);

      setPlaceholder(dokterSelect, uniqueDokters.length ? 'Pilih Dokter' : 'Dokter tidak tersedia');
      if (uniqueDokters.length) {
       fillOptions(dokterSelect, uniqueDokters, dokter => `${dokter.nama} — ${dokter.spesialis || 'Umum'}`);
        dokterSelect.disabled = false;
         if (initialValues.dokter) {
          dokterSelect.value = initialValues.dokter;
          if (dokterSelect.value) {
            loadJadwals().then(() => {
              initialValues.dokter = '';
            });
          } else {
            initialValues.dokter = '';
          }
        }
      } else {
        dokterSelect.disabled = true;
      }
   } catch (error) {
      console.error(error);
      setPlaceholder(dokterSelect, 'Gagal memuat dokter');
      dokterSelect.disabled = true;
    }
  });

  dokterSelect.addEventListener('change', () => {
    initialValues.jadwal = jadwalSelect.dataset.oldValue || '';
    jadwalSelect.dataset.oldValue = '';
    loadJadwals();
  });

      if (tanggalInput) {
    tanggalInput.addEventListener('change', () => {
      if (layananSelect.value && dokterSelect.value) {
        loadJadwals();
    }
   });
  }

  if (initialValues.layanan) {
    layananSelect.value = initialValues.layanan;
    layananSelect.dispatchEvent(new Event('change'));
    layananSelect.dataset.oldValue = '';
    initialValues.layanan = '';
  }
  });
</script>

  @endsection