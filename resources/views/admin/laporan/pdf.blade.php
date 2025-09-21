<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Operasional Klinik</title>
  <style>
    * {
      font-family: 'DejaVu Sans', sans-serif;
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 24px;
      font-size: 12px;
      color: #1a202c;
    }

    h1, h2, h3, h4 {
      margin: 0;
    }

    .header {
      display: flex;
      justify-content: space-between;
      border-bottom: 2px solid #4a5568;
      padding-bottom: 12px;
      margin-bottom: 20px;
    }

    .clinic-info {
      max-width: 60%;
    }

    .report-meta {
      text-align: right;
      font-size: 11px;
    }

    .meta-label {
      color: #718096;
    }

    .metrics {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .metrics td {
      padding: 10px 14px;
      border: 1px solid #e2e8f0;
      vertical-align: top;
    }

    .metrics .label {
      font-size: 11px;
      color: #4a5568;
      text-transform: uppercase;
      letter-spacing: .05em;
      margin-bottom: 4px;
      display: block;
    }

    .metrics .value {
      font-size: 20px;
      font-weight: bold;
      color: #2d3748;
    }

    .metrics .caption {
      font-size: 11px;
      color: #718096;
    }

    .section-title {
      font-size: 14px;
      font-weight: bold;
      color: #2b6cb0;
      margin: 24px 0 12px;
      text-transform: uppercase;
      letter-spacing: .08em;
    }

    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
    }

    table.data-table th,
    table.data-table td {
      border: 1px solid #e2e8f0;
      padding: 8px;
      font-size: 11px;
    }

    table.data-table th {
      background-color: #ebf8ff;
      color: #2b6cb0;
      text-transform: uppercase;
      letter-spacing: .05em;
    }

    .text-muted {
      color: #718096;
    }

    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 12px;
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: .05em;
    }

    .badge-success {
      background-color: #c6f6d5;
      color: #276749;
    }

    .badge-warning {
      background-color: #fefcbf;
      color: #b7791f;
    }

    .badge-info {
      background-color: #bee3f8;
      color: #2c5282;
    }

    .summary-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 24px;
    }

    .summary-card {
      border: 1px solid #e2e8f0;
      border-radius: 4px;
      padding: 12px;
    }

    .summary-card .label {
      font-size: 11px;
      color: #4a5568;
      text-transform: uppercase;
      letter-spacing: .05em;
      margin-bottom: 4px;
    }

    .summary-card .value {
      font-size: 16px;
      font-weight: bold;
      color: #1a202c;
    }
  </style>
</head>
<body>
@php
    $generatedAt = $generatedAt ?? \Carbon\Carbon::now();
    $periodLabel = \Carbon\Carbon::parse($startDate)->locale(app()->getLocale())->translatedFormat('d F Y') .
        ' - ' . \Carbon\Carbon::parse($endDate)->locale(app()->getLocale())->translatedFormat('d F Y');
    $selectedDokterName = $selectedDokter->nama ?? 'Semua Dokter';
    $selectedJadwalLabel = null;
    if ($selectedJadwal) {
        $selectedJadwalLabel = sprintf(
            '%s (%s - %s)',
            $selectedJadwal->hari,
            \Carbon\Carbon::parse($selectedJadwal->waktu_mulai)->format('H:i'),
            \Carbon\Carbon::parse($selectedJadwal->waktu_selesai)->format('H:i')
        );
    }
@endphp
  <div class="header">
    <div class="clinic-info">
      <h1>{{ $clinicName }}</h1>
      <p class="text-muted">Laporan Operasional Klinik</p>
      <p class="text-muted">Periode: {{ $periodLabel }}</p>
      <p class="text-muted">Filter Dokter: {{ $selectedDokterName }}</p>
      <p class="text-muted">Filter Jadwal: {{ $selectedJadwalLabel ?? 'Semua Jadwal' }}</p>
    </div>
    <div class="report-meta">
      <p><span class="meta-label">Dicetak pada:</span><br>{{ $generatedAt->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</p>
      <p><span class="meta-label">Total Pasien:</span> {{ $totalPatients }}</p>
      <p><span class="meta-label">Total Dokter:</span> {{ $totalDoctors }}</p>
      <p><span class="meta-label">Total Layanan:</span> {{ $totalServices }}</p>
    </div>
  </div>

  <table class="metrics">
    <tr>
      <td>
        <span class="label">Total Antrian</span>
        <span class="value">{{ $totalQueue }}</span>
        <span class="caption">Seluruh antrian pada periode laporan.</span>
      </td>
      <td>
        <span class="label">Disetujui</span>
        <span class="value">{{ $approvedQueue }}</span>
        <span class="caption">Antrian berstatus approved.</span>
      </td>
      <td>
        <span class="label">Menunggu</span>
        <span class="value">{{ $pendingQueue }}</span>
        <span class="caption">Antrian menunggu konfirmasi.</span>
      </td>
      <td>
        <span class="label">Pendapatan</span>
        <span class="value">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</span>
        <span class="caption">Pembayaran sukses dalam periode.</span>
      </td>
    </tr>
  </table>

  <h2 class="section-title">Ringkasan Harian</h2>
  <table class="data-table">
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Total Antrian</th>
      </tr>
    </thead>
    <tbody>
    @forelse ($dailyQueue as $day)
      <tr>
        <td>{{ \Carbon\Carbon::parse($day->tanggal)->locale(app()->getLocale())->translatedFormat('d F Y') }}</td>
        <td>{{ $day->total }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="2" class="text-muted">Tidak ada data harian pada periode ini.</td>
      </tr>
    @endforelse
    </tbody>
  </table>

  <h2 class="section-title">Layanan Teratas</h2>
  <table class="data-table">
    <thead>
      <tr>
        <th>Layanan</th>
        <th>Jumlah Antrian</th>
      </tr>
    </thead>
    <tbody>
    @forelse ($services as $service)
      <tr>
        <td>{{ data_get($service, 'layanan.nama', 'Layanan tidak diketahui') }}</td>
        <td>{{ $service->total }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="2" class="text-muted">Belum ada data layanan untuk periode ini.</td>
      </tr>
    @endforelse
    </tbody>
  </table>

  <h2 class="section-title">10 Antrian Terbaru</h2>
  <table class="data-table">
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Nomor</th>
        <th>Pasien</th>
        <th>Dokter</th>
        <th>Layanan</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
    @forelse ($latestQueue as $queue)
      <tr>
        <td>
          {{ optional($queue->tanggal ? \Carbon\Carbon::parse($queue->tanggal) : null)->format('d/m/Y') ?? '-' }}
        </td>
        <td>{{ $queue->nomor ?? $queue->nomor_antrian ?? '-' }}</td>
        <td>{{ optional($queue->user)->name ?? 'Tidak diketahui' }}</td>
        <td>{{ optional($queue->dokter)->nama ?? 'Belum ditentukan' }}</td>
        <td>{{ optional($queue->layanan)->nama ?? 'Belum dipilih' }}</td>
        <td>
          @php
            $status = strtolower($queue->status ?? 'pending');
            $badgeClass = $status === 'approved' ? 'badge-success' : ($status === 'pending' ? 'badge-warning' : 'badge-info');
          @endphp
          <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="6" class="text-muted">Belum ada antrian terbaru pada periode ini.</td>
      </tr>
    @endforelse
    </tbody>
  </table>
</body>
</html>