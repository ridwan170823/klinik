<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AntrianController extends Controller
{
    public function create()
    {
        return view('pasien.antrian.create'); // form ambil nomor
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => ['required','date'],
            'layanan_id' => ['nullable','integer'],
            'dokter_id'  => ['nullable','integer'],
        ]);

        $tanggal = $request->date('tanggal');
        $layanan = $request->layanan_id;
        $dokter  = $request->dokter_id;

        $nomor = DB::transaction(function () use ($tanggal,$layanan,$dokter) {
            $max = Antrian::whereDate('tanggal',$tanggal)
                ->when($layanan, fn($q)=>$q->where('layanan_id',$layanan))
                ->when($dokter,  fn($q)=>$q->where('dokter_id',$dokter))
                ->lockForUpdate()->max('nomor');
            return ($max ?? 0) + 1;
        });

        Antrian::create([
            'user_id'    => Auth::id(),
            'tanggal'    => $tanggal,
            'layanan_id' => $layanan,
            'dokter_id'  => $dokter,
            'nomor'      => $nomor,
        ]);

        return redirect()->route('antrian.riwayat')->with('success', "Nomor antrian kamu: {$nomor}");
    }

    public function riwayat()
    {
        $data = Antrian::with(['dokter','layanan'])
            ->where('user_id', Auth::id())
            ->orderByDesc('tanggal')->orderBy('nomor')->get();

        return view('pasien.antrian.riwayat', compact('data'));
    }
}
