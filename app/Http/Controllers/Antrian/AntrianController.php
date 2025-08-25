<?php

namespace App\Http\Controllers\Antrian;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    public function index()
    {
        $antrians = Antrian::with(['user', 'dokter', 'jadwal'])
            ->orderBy('nomor_antrian')
            ->get();

        $dokters = null;
        if (Auth::user()->role === 'pasien') {
            $dokters = Dokter::with('jadwals')->get();
        }

        return view('antrian.index', [
            'antrians' => $antrians,
            'dokters' => $dokters,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $dokter = Dokter::find($data['dokter_id']);
        if (!$dokter->jadwals()->where('jadwals.id', $data['jadwal_id'])->exists()) {
            return back()->withErrors(['jadwal_id' => 'Jadwal tidak tersedia untuk dokter ini']);
        }

        $last = Antrian::max('nomor_antrian') ?? 0;
        $number = $last + 1;

        Antrian::create([
            'user_id' => Auth::id(),
            'nomor_antrian' => $number,
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
}