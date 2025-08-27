<?php

namespace App\Http\Controllers\Antrian;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Dokter;
use App\Models\Layanan;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    public function index(Request $request)
    {
        $query = Antrian::with(['user', 'dokter', 'jadwal']);

        
        $layanans = null;
        if (Auth::user()->role === 'pasien') {
            $query->where('user_id', Auth::id());
            $layanans = Layanan::all();
        }
        if ($request->filled('dokter_id')) {
            $query->where('dokter_id', $request->dokter_id);
        }

        if ($request->filled('hari')) {
            $query->whereHas('jadwal', function ($q) use ($request) {
                $q->where('hari', $request->hari);
            });
        }

        if ($request->filled('dokter_id') || $request->filled('hari')) {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('nomor_antrian');
        }

        return view('antrian.index', [
            'antrians' => $query->get(),
            'layanans' => $layanans,
            'dokters' => Dokter::all(),
            'haris' => Jadwal::select('hari')->distinct()->pluck('hari'),
            'selectedDokter' => $request->dokter_id,
            'selectedHari' => $request->hari,
            'selectedLayanan' => $request->layanan_id,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'layanan_id' => 'required|exists:layanans,id',
            'dokter_id' => 'required|exists:dokters,id',
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $limit = (int) config('antrian.max_days_ahead');
        $maxDate = now()->addDays($limit)->toDateString();

        $layanan = Layanan::find($data['layanan_id']);
        $dokter = $layanan?->dokters()->find($data['dokter_id']);
        if (! $dokter) {
            return back()->withErrors(['dokter_id' => 'Dokter tidak tersedia untuk layanan ini']);
        }

        $hasActive = Antrian::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        if ($hasActive) {
            return back()->withErrors(['layanan_id' => 'Anda sudah memiliki antrian aktif']);
        }

        $slotTaken = Antrian::where('dokter_id', $data['dokter_id'])
            ->where('jadwal_id', $data['jadwal_id'])
            // ->whereDate('tanggal', $data['tanggal'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        if ($slotTaken) {
            return back()->withErrors(['jadwal_id' => 'Slot jadwal sudah diambil'])->withInput();
        }
        $kapasitas = Jadwal::find($data['jadwal_id'])->kapasitas ?? 0;
        $current = Antrian::where('jadwal_id', $data['jadwal_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->count();
        if ($current >= $kapasitas) {
            return back()->withErrors(['jadwal_id' => 'Kapasitas jadwal sudah penuh'])->withInput();
        }

        Antrian::create([
            'user_id' => Auth::id(),
            'layanan_id' => $data['layanan_id'],
            'dokter_id' => $data['dokter_id'],
            'jadwal_id' => $data['jadwal_id'],
            // 'tanggal' => $data['tanggal'],
            'status' => 'pending',
        ]);

        return redirect()->route('antrian.index');
    }

    public function destroy(Antrian $antrian)
    {
        $antrian->delete();
        return redirect()->route('antrian.index');
    }

    public function approve(Antrian $antrian)
    {
        if ($antrian->status !== 'approved') {
            $last = Antrian::whereNotNull('nomor_antrian')->max('nomor_antrian') ?? 0;
            $antrian->update([
                'status' => 'approved',
                'nomor_antrian' => $last + 1,
            ]);
        }
        return redirect()->route('antrian.index');
    }
    public function dokters(Layanan $layanan)
    {
        return response()->json(
          $layanan->dokters()
                ->select('dokters.id', 'dokters.nama', 'dokters.image', 'dokters.spesialis')
                ->get()
                ->map(function ($dokter) {
                    $dokter->image = $dokter->image
                        ? asset('storage/'.$dokter->image)
                        : asset('img/undraw_profile.svg');

                    return $dokter;
                })
        );
    }

    public function jadwals(Dokter $dokter)
    {
        $taken = Antrian::where('dokter_id', $dokter->id)
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('jadwal_id');
        return response()->json(
            
            $dokter->jadwals()
            ->where('jadwals.is_available', true)
                ->whereNotIn('jadwals.id', $taken)
                ->select(
                    'jadwals.id',
                    'jadwals.hari',
                    'jadwals.waktu_mulai',
                    'jadwals.waktu_selesai'
                )
                ->get()
        );
    }
}