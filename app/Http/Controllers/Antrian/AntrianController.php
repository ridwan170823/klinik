<?php

namespace App\Http\Controllers\Antrian;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Dokter;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    public function index(Request $request)
    {
        $antrians = Antrian::with(['user', 'dokter', 'jadwal'])
            ->orderBy('nomor_antrian')
            ->get();

        $dokters = null;
        $layanans = null;
        if (Auth::user()->role === 'pasien') {
            $layanans = Layanan::all();
            $dokters = Dokter::with('jadwals')
                ->when($request->layanan_id, fn ($q, $layanan) => $q->where('layanan_id', $layanan))
                ->get();
        }

        return view('antrian.index', [
            'antrians' => $antrians,
            'dokters' => $dokters,
            'layanans' => $layanans,
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

        $dokter = Dokter::where('layanan_id', $data['layanan_id'])->find($data['dokter_id']);
        if (!$dokter) {
            return back()->withErrors(['dokter_id' => 'Dokter tidak tersedia untuk layanan ini']);
        }

        $last = Antrian::max('nomor_antrian') ?? 0;
        $number = $last + 1;

        Antrian::create([
            'user_id' => Auth::id(),
            'nomor_antrian' => $number,
            'layanan_id' => $data['layanan_id'],
            'dokter_id' => $data['dokter_id'],
            'jadwal_id' => $data['jadwal_id'],
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
        $antrian->update(['status' => 'approved']);
        return redirect()->route('antrian.index');
    }
    public function dokters(Layanan $layanan)
    {
        return response()->json(
            $layanan->dokters()->select('id', 'nama')->get()
        );
    }

    public function jadwals(Dokter $dokter)
    {
        return response()->json(
            $dokter->jadwals()->select('id', 'hari', 'waktu_mulai', 'waktu_selesai')->get()
        );
    }
}