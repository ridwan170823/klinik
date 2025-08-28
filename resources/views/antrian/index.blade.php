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
              <select class="form-control" name="layanan_id" id="layananSelect" required>
                <option value="" disabled selected>Pilih Layanan</option>
                @foreach ($layanans as $layanan)
               <option value="{{ $layanan->id }}">{{ $layanan->nama }} - Rp{{ number_format($layanan->harga) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col">
              <select class="form-control" name="dokter_id" id="dokterSelect" required disabled>
                <option value="" disabled selected>Pilih Dokter</option>
              </select>
            </div>
            <div class="col">
              <select class="form-control" name="jadwal_id" id="jadwalSelect" required disabled>
                <option value="" disabled selected>Pilih Jadwal</option>
              </select>
            </div>
            <div class="col">
              <input type="date" class="form-control" name="tanggal" id="tanggalInput" required
                     min="{{ now()->toDateString() }}"
                     max="{{ now()->addDays(config('antrian.max_days_ahead'))->toDateString() }}"
                     value="{{ old('tanggal') }}">
              @error('tanggal')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col">
              <button type="submit" class="btn btn-primary font-weight-bold">Ambil Antrian</button>
            </div>
          </div>
        </form>
      </div>
      @endif
      <div class="card-body">
        <form method="GET" action="{{ route('antrian.index') }}" class="form-inline mb-3">
          <select name="hari" class="form-control mr-2">
            <option value="">Semua Hari</option>
            @foreach ($haris as $hari)
            <option value="{{ $hari }}" {{ $selectedHari == $hari ? 'selected' : '' }}>{{ $hari }}</option>
            @endforeach
          </select>
          <select name="dokter_id" class="form-control mr-2">
            <option value="">Semua Dokter</option>
            @foreach ($dokters as $dokter)
            <option value="{{ $dokter->id }}" {{ $selectedDokter == $dokter->id ? 'selected' : '' }}>{{ $dokter->nama }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No Antrian</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Jadwal</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($antrians as $antrian)
              <tr>
                <td>{{ $antrian->status === 'approved' ? $antrian->nomor_antrian : 'Menunggu' }}</td>
                <td>{{ $antrian->user->name }}</td>
                <td>{{ $antrian->dokter->nama }}</td>
                <td>{{ $antrian->jadwal->hari }} {{ $antrian->jadwal->waktu_mulai }}-{{ $antrian->jadwal->waktu_selesai }}</td>
                <td>{{ ucfirst($antrian->status) }}</td>             
                <td>
                  @if ($antrian->status === 'pending' && Auth::user()->role == 'pasien')
                  <a href="{{ route('payments.create', $antrian->id) }}" class="btn btn-primary btn-sm">Bayar</a>
                  @endif

                  @if (Auth::user()->role == 'admin')
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
                  @endif
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
<script>
document.addEventListener('DOMContentLoaded', () => {
  const layananSelect = document.getElementById('layananSelect');
  const dokterSelect  = document.getElementById('dokterSelect');
  const jadwalSelect  = document.getElementById('jadwalSelect');

  if (!layananSelect || !dokterSelect || !jadwalSelect) return;

  // Helpers
  const setPlaceholder = (select, text) => {
    select.innerHTML = '';
    const opt = document.createElement('option');
    opt.value = '';
    opt.disabled = true;
    opt.selected = true;
    opt.textContent = text;
    select.appendChild(opt);
  };

  const fillOptions = (select, items, labelFn) => {
    items.forEach(item => {
      const opt = document.createElement('option');
      opt.value = item.id;
      opt.textContent = labelFn(item);
      select.appendChild(opt);
    });
  };

  const fetchJson = async (url) => {
    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error(`${res.status} ${res.statusText}`);
    return res.json();
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

    try {
      const dokters = await fetchJson(`/layanans/${encodeURIComponent(layananId)}/dokters`);
      const uniqueDokters = dokters.filter((d, i, arr) =>
        arr.findIndex(x => x.id === d.id) === i
      );

      setPlaceholder(dokterSelect, uniqueDokters.length ? 'Pilih Dokter' : 'Dokter tidak tersedia');
      if (uniqueDokters.length) {
        fillOptions(dokterSelect, uniqueDokters, d => `${d.nama} — ${d.spesialis || 'Umum'}`);
        dokterSelect.disabled = false;
      } else {
        dokterSelect.disabled = true;
      }
    } catch (err) {
      setPlaceholder(dokterSelect, 'Gagal memuat dokter');
      dokterSelect.disabled = true;
      console.error(err);
    }
  });

  // Ketika pilih Dokter
  dokterSelect.addEventListener('change', async function () {
    const dokterId  = this.value;
    const layananId = layananSelect.value;

    setPlaceholder(jadwalSelect, 'Memuat jadwal…');
    jadwalSelect.disabled = true;

    try {
      const jadwals = await fetchJson(`/dokters/${encodeURIComponent(dokterId)}/layanans/${encodeURIComponent(layananId)}/jadwals`);

      setPlaceholder(jadwalSelect, jadwals.length ? 'Pilih Jadwal' : 'Jadwal tidak tersedia');
      if (jadwals.length) {
        fillOptions(jadwalSelect, jadwals, j => `${j.hari} (${j.waktu_mulai} - ${j.waktu_selesai})`);
        jadwalSelect.disabled = false;
      } else {
        jadwalSelect.disabled = true;
      }
    } catch (err) {
      setPlaceholder(jadwalSelect, 'Gagal memuat jadwal');
      jadwalSelect.disabled = true;
      console.error(err);
    }
  });
});
</script>

  @endsection