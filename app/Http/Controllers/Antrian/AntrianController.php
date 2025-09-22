<?php

namespace App\Http\Controllers\Antrian;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Dokter;
use App\Models\Jadwal;
use App\Models\Layanan;
use App\Models\User;
use App\Notifications\AntrianScheduled;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AntrianController extends Controller
{
    public function index(Request $request)
    {
      $query = Antrian::with(['user', 'dokter', 'jadwal', 'layanan']);
        $currentUser = Auth::user();
        
        if ($currentUser && $currentUser->role === 'pasien') {
            $query->where('user_id', $currentUser->getAuthIdentifier());
        }
        if ($request->filled('layanan_id')) {
            $query->where('layanan_id', $request->integer('layanan_id'));
        }
        if ($request->filled('dokter_id')) {
             $query->where('dokter_id', $request->integer('dokter_id'));
        }

        if ($request->filled('hari')) {
             $query->whereHas('jadwal', function ($relation) use ($request) {
                $relation->where('hari', $request->hari);
            });
        }

        if ($request->filled('dokter_id') || $request->filled('hari')) {
            $query->orderByDesc('created_at');
        } else {
            $query->orderBy('nomor_antrian');
        }

        return view('antrian.index', [
            'antrians' => $query->get(),
            'layanans' => Layanan::orderBy('nama')->get(),
            'dokters' => Dokter::orderBy('nama')->get(),
            'haris' => Jadwal::select('hari')->distinct()->orderBy('hari')->pluck('hari'),
            'selectedDokter' => $request->dokter_id,
            'selectedHari' => $request->hari,
            'selectedLayanan' => $request->layanan_id,
        ]);
    }
public function store(Request $request): RedirectResponse
    {
        $maxDaysAhead = (int) config('antrian.max_days_ahead', 30);
        $today = now()->toDateString();
        $maxDate = now()->addDays($maxDaysAhead)->toDateString();
        $data = $request->validate([
            'layanan_id' => 'required|exists:layanans,id',
            'dokter_id' => 'required|exists:dokters,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'tanggal' => "required|date",
        ]);

        $layanan = Layanan::with(['dokters' => function ($query) use ($data) {
            $query->where('dokters.id', $data['dokter_id']);
        }])->find($data['layanan_id']);


        $dokter = $layanan?->dokters->first();
        if (! $dokter) {
           return back()
                ->withInput()
                ->withErrors(['dokter_id' => 'Dokter tidak tersedia untuk layanan ini.']);
        }

        $availableJadwalIds = $dokter->layanans()
            ->where('layanans.id', $layanan->id)
            ->pluck('dokter_layanan.jadwal_id')
            ->filter()
            ->map(fn ($id) => (int) $id);

        if (! $availableJadwalIds->contains((int) $data['jadwal_id'])) {
            return back()
                ->withInput()
                ->withErrors(['jadwal_id' => 'Jadwal tidak tersedia untuk kombinasi layanan dan dokter yang dipilih.']);
        }

        $jadwal = Jadwal::whereKey($data['jadwal_id'])
            ->where('is_available', true)
            ->first();

        if (! $jadwal) {
            return back()
                ->withInput()
                ->withErrors(['jadwal_id' => 'Jadwal tidak tersedia.']);
        }

        if ($jadwal->kapasitas <= 0) {
            return back()
                ->withInput()
                ->withErrors(['jadwal_id' => 'Kapasitas jadwal sudah penuh.']);
        }

        $tanggal = Carbon::parse($data['tanggal']);
        $expectedDay = $this->translateDayName($tanggal);

        if (strcasecmp($expectedDay, (string) $jadwal->hari) !== 0) {
            return back()
                ->withInput()
                ->withErrors(['tanggal' => 'Tanggal tidak sesuai dengan hari jadwal yang dipilih.']);
        }
            
        $duplicate = Antrian::where('user_id', Auth::id())
            ->where('dokter_id', $data['dokter_id'])
            ->where('jadwal_id', $data['jadwal_id'])
            ->whereDate('tanggal', $data['tanggal'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        if ($duplicate) {
             return back()
                ->withInput()
                ->withErrors([
                    'tanggal' => 'Anda sudah memiliki antrian dengan dokter dan jadwal ini pada tanggal tersebut.',
                ]);
        }

        $existingCount = Antrian::where('dokter_id', $data['dokter_id'])
            ->where('jadwal_id', $data['jadwal_id'])
            ->whereDate('tanggal', $data['tanggal'])
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($existingCount >= $jadwal->kapasitas) {
            return back()
                ->withInput()
                ->withErrors(['jadwal_id' => 'Kapasitas jadwal untuk tanggal tersebut sudah penuh.']);
        }
        $nextNomorAntrian = (Antrian::max('nomor_antrian') ?? 0) + 1;

        $antrian = Antrian::create([
            'user_id' => Auth::id(),
            'layanan_id' => $data['layanan_id'],
            'dokter_id' => $data['dokter_id'],
            'jadwal_id' => $data['jadwal_id'],
            'tanggal' => $tanggal->toDateString(),
            'status' => 'approved',
            'nomor_antrian' => $nextNomorAntrian,
        ]);
           $antrian->loadMissing(['user', 'dokter', 'jadwal', 'layanan']);

        $patient = Auth::user();
        if ($patient) {
           try {
                $patient->notify(new AntrianScheduled($antrian));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
           try {
                Notification::send($admins, new AntrianScheduled($antrian, true));
           } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return redirect()
            ->route('antrian.index')
            ->with('success', 'Antrian berhasil dibuat dengan nomor ' . $antrian->nomor_antrian . '.');
    }

    public function destroy(Antrian $antrian): RedirectResponse
    {
        $antrian->jadwal?->increment('kapasitas');
        $antrian->delete();
        return redirect()
            ->route('antrian.index')
            ->with('success', 'Antrian berhasil dihapus.');
    }

   public function approve(Antrian $antrian): RedirectResponse
    {
        if ($antrian->status === 'approved') {
            return redirect()
                ->route('antrian.index')
                ->with('success', 'Antrian sudah dalam status disetujui.');
        }

        $jadwal = $antrian->jadwal;
        if ($jadwal && $jadwal->kapasitas > 0) {
            $existingCount = Antrian::where('dokter_id', $antrian->dokter_id)
                ->where('jadwal_id', $antrian->jadwal_id)
                ->whereDate('tanggal', $antrian->tanggal)
                ->where('status', 'approved')
                ->count();

            if ($existingCount >= $jadwal->kapasitas) {
                return redirect()
                    ->route('antrian.index')
                    ->with('error', 'Kapasitas jadwal untuk tanggal tersebut sudah penuh.');
            }
            

            }

        $last = Antrian::whereNotNull('nomor_antrian')->max('nomor_antrian') ?? 0;
        $antrian->update([
            'status' => 'approved',
            'nomor_antrian' => $last + 1,
        ]);

        $antrian->loadMissing(['user', 'dokter', 'jadwal', 'layanan']);

        if ($antrian->user) {
            try {
                $antrian->user->notify(new AntrianScheduled($antrian));
            } catch (\Throwable $exception) {
                report($exception);
            }
        }
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            try {
                Notification::send($admins, new AntrianScheduled($antrian, true));
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return redirect()
            ->route('antrian.index')
            ->with('success', 'Antrian berhasil disetujui.');
    }
    public function patientHistory()
    {
        $antrians = Antrian::with(['dokter', 'jadwal'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('antrian.history', [
            'antrians' => $antrians,
        ]);
    }
     public function history()
    {
        $antrians = Antrian::with(['user', 'dokter', 'jadwal'])
            ->whereIn('status', ['pending', 'approved'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.antrian.history', [
            'antrians' => $antrians,
        ]);
    }
    public function dokters(Layanan $layanan)
    {
        $dokters = $layanan->dokters()
            ->select('dokters.id', 'dokters.nama', 'dokters.image', 'dokters.spesialis')
            ->distinct()
            ->orderBy('dokters.nama')
            ->get()
            ->map(function ($dokter) {
                $dokter->image = $dokter->image
                    ? asset('storage/' . $dokter->image)
                    : asset('img/undraw_profile.svg');

                return $dokter;
            });

        return response()->json($dokters);
    }

    public function jadwals(Request $request, Dokter $dokter, Layanan $layanan)
    {
        $tanggal = $request->query('tanggal');

        $jadwals = $dokter->layanans()
            ->where('dokter_layanan.layanan_id', $layanan->id)
            ->join('jadwals', 'dokter_layanan.jadwal_id', '=', 'jadwals.id')
            ->where('jadwals.is_available', true)
            ->select(
                'jadwals.id',
                'jadwals.hari',
                'jadwals.waktu_mulai',
                'jadwals.waktu_selesai',
                'jadwals.kapasitas'
            )
          ->orderBy('jadwals.hari')
            ->orderBy('jadwals.waktu_mulai')
            ->get()
            ->map(function ($jadwal) use ($dokter, $tanggal) {
                $jadwal->kapasitas = (int) $jadwal->kapasitas;

                if ($tanggal) {
                    $booked = Antrian::where('dokter_id', $dokter->id)
                        ->where('jadwal_id', $jadwal->id)
                        ->whereDate('tanggal', $tanggal)
                        ->whereIn('status', ['pending', 'approved'])
                        ->count();

                    $jadwal->sisa_kapasitas = max($jadwal->kapasitas - $booked, 0);
                } else {
                    $jadwal->sisa_kapasitas = $jadwal->kapasitas;
                }

                return $jadwal;
            });

        if ($tanggal) {
            $jadwals = $jadwals->filter(function ($jadwal) {
                return $jadwal->kapasitas === 0 || $jadwal->sisa_kapasitas > 0;
            })->values();
        }

        return response()->json($jadwals);
    }
private function translateDayName(Carbon $date): string
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        return $days[$date->dayOfWeek] ?? $date->locale(app()->getLocale())->isoFormat('dddd');
    }
}