<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Dokter;
use App\Models\Layanan;
use App\Models\Jadwal;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $dokterId = $request->input('dokter_id');
        $jadwalId = $request->input('jadwal_id');

        if (! $startDate || ! $endDate) {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $startDate = $startDate ?: $start->toDateString();
            $endDate = $endDate ?: $end->toDateString();
        }

        if (Carbon::parse($startDate)->greaterThan(Carbon::parse($endDate))) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $baseQuery = Antrian::with(['user', 'dokter', 'jadwal', 'layanan'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($dokterId, function ($query) use ($dokterId) {
                $query->where('dokter_id', $dokterId);
            })
            ->when($jadwalId, function ($query) use ($jadwalId) {
                $query->where('jadwal_id', $jadwalId);
            });

        $totalQueue = (clone $baseQuery)->count();
        $approvedQueue = (clone $baseQuery)->where('status', 'approved')->count();
        $pendingQueue = (clone $baseQuery)->where('status', 'pending')->count();

        $services = (clone $baseQuery)
            ->whereNotNull('layanan_id')
            ->selectRaw('layanan_id, COUNT(*) as total')
            ->groupBy('layanan_id')
            ->with('layanan')
            ->orderByDesc('total')
            ->get();

        $dailyQueue = (clone $baseQuery)
            ->selectRaw('tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $latestQueue = (clone $baseQuery)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $totalPatients = User::where('role', 'pasien')->count();
        $totalDoctors = Dokter::count();
        $totalServices = Layanan::count();
        $dokters = Dokter::orderBy('nama')->get();

        $jadwals = Jadwal::query()
            ->when($dokterId, function ($query) use ($dokterId) {
                $query->whereHas('dokters', function ($relation) use ($dokterId) {
                    $relation->whereKey($dokterId);
                });
            })
            ->orderBy('hari')
            ->orderBy('waktu_mulai')
            ->get();

        $selectedDokter = $dokters->firstWhere('id', (int) $dokterId);
        $selectedJadwal = $jadwals->firstWhere('id', (int) $jadwalId);

        if (! $selectedJadwal && $jadwalId) {
            $selectedJadwal = Jadwal::find($jadwalId);
        }

        $totalRevenue = Payment::whereHas('antrian', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->where('payment_status', 'paid')->sum('amount');

        return view('admin.laporan.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalQueue' => $totalQueue,
            'approvedQueue' => $approvedQueue,
            'pendingQueue' => $pendingQueue,
            'services' => $services,
            'dailyQueue' => $dailyQueue,
            'latestQueue' => $latestQueue,
            'totalPatients' => $totalPatients,
            'totalDoctors' => $totalDoctors,
            'totalServices' => $totalServices,
            'totalRevenue' => $totalRevenue,
            'dokters' => $dokters,
            'jadwals' => $jadwals,
            'selectedDokter' => $selectedDokter,
            'selectedJadwal' => $selectedJadwal,
            'dokterId' => $dokterId,
            'jadwalId' => $jadwalId,
        ]);
    }
}