@extends('layouts.main')

@section('content')
<div id="content">
  <div class="container-fluid">
    <h1 class="h3 mb-3 font-weight-bold text-gray-800">Jadwal Dokter</h1>
    <div class="card shadow mb-4">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Hari</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($jadwals as $index => $jadwal)
              <tr class="{{ $jadwal->is_available ? '' : 'table-secondary' }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $jadwal->hari }}</td>
                <td>{{ $jadwal->waktu_mulai }}</td>
                <td>{{ $jadwal->waktu_selesai }}</td>
                <td>
                  @if($jadwal->is_available)
                    <span class="badge badge-success">Tersedia</span>
                  @else
                    <span class="badge badge-danger">Istirahat</span>
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
@endsection