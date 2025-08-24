<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::with('jadwals')->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        $jadwals = Jadwal::all();
        return view('admin.dokter.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'nama' => 'required|string|max:255',
        'spesialis' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->only(['nama', 'spesialis']);

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('dokter', 'public');
    }

    // Simpan data dokter
    $dokter = Dokter::create($data);

    // Simpan relasi ke tabel pivot
    $dokter->jadwals()->sync($request->jadwal_id);

    return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil ditambahkan.');
}


    public function edit(Dokter $dokter)
{
    $jadwals = Jadwal::all();
    $dokter->load('jadwals');
    return view('admin.dokter.edit', compact('dokter', 'jadwals'));
}

    public function update(Request $request, Dokter $dokter)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'spesialis' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'jadwal_id' => 'nullable|array',
        'jadwal_id.*' => 'exists:jadwals,id',
    ]);

    $data = $request->only(['nama','spesialis']);

    if ($request->hasFile('image')) {
        if ($dokter->image) {
            Storage::disk('public')->delete($dokter->image);
        }
        $data['image'] = $request->file('image')->store('dokter', 'public');
    }

    $dokter->update($data);

    $dokter->jadwals()->sync($request->input('jadwal_id', []));

    return redirect()->route('dokter.index')->with('success', 'Dokter berhasil diperbarui.');
}

    public function destroy(Dokter $dokter)
    {
        if ($dokter->image) {
            Storage::disk('public')->delete($dokter->image);
        }

        $dokter->delete();
        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil dihapus');
    }
}
