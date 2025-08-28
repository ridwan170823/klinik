<?php

namespace App\Http\Controllers\DokterLayanan;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Layanan;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokterLayananController extends Controller
{
    public function index()
    {
        $dokterLayanans = DB::table('dokter_layanan')
            ->join('dokters', 'dokter_layanan.dokter_id', '=', 'dokters.id')
            ->join('layanans', 'dokter_layanan.layanan_id', '=', 'layanans.id')
            ->leftJoin('jadwals', 'dokter_layanan.jadwal_id', '=', 'jadwals.id')
            ->select(
                'dokter_layanan.id',
                'dokters.nama as dokter_nama',
                'layanans.nama as layanan_nama',
                'jadwals.hari',
                'jadwals.waktu_mulai',
                'jadwals.waktu_selesai'
            )
            ->get();

        return view('admin.dokter_layanan.index', compact('dokterLayanans'));
    }

    public function create()
    {
        $dokters = Dokter::all();
        $layanans = Layanan::all();
        $jadwals = Jadwal::all();

        return view('admin.dokter_layanan.create', compact('dokters', 'layanans', 'jadwals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'layanan_id' => 'required|exists:layanans,id',
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        DB::table('dokter_layanan')->insert([
            'dokter_id' => $validated['dokter_id'],
            'layanan_id' => $validated['layanan_id'],
            'jadwal_id' => $validated['jadwal_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dokter_layanan.index');
    }

    public function edit($id)
    {
        $dokterLayanan = DB::table('dokter_layanan')->where('id', $id)->first();
        $dokters = Dokter::all();
        $layanans = Layanan::all();
        $jadwals = Jadwal::all();

        return view('admin.dokter_layanan.edit', compact('dokterLayanan', 'dokters', 'layanans', 'jadwals'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'layanan_id' => 'required|exists:layanans,id',
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        DB::table('dokter_layanan')
            ->where('id', $id)
            ->update([
                'dokter_id' => $validated['dokter_id'],
                'layanan_id' => $validated['layanan_id'],
                'jadwal_id' => $validated['jadwal_id'],
                'updated_at' => now(),
            ]);

        return redirect()->route('dokter_layanan.index');
    }

    public function destroy($id)
    {
        DB::table('dokter_layanan')->where('id', $id)->delete();

        return redirect()->route('dokter_layanan.index');
    }
}