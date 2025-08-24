<?php

namespace App\Http\Controllers\Layanan;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Layanan\layananRequest;

class layananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       // ambil data untuk keduanya
    $layanans = Layanan::latest()->paginate(10);

    // kalau route ini dilindungi middleware('auth'), baris Auth::check() opsional
    if (Auth::check() && Auth::user()->role === 'admin') {
        return view('admin.layanan.index', compact('layanans'));
        // atau: return view('admin.layanan.index', ['layanans' => $layanans]);
    }

    return view('layanan.index', compact('layanans'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ambil semua dokter untuk dropdown
        $layanans = Layanan::all();

        return view('admin.layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(layananRequest $request)
    {
        $validatedData = $request->all();
        layanan::create($validatedData);

        return redirect()->route('layanan.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Layanan $layanan
     * @return \Illuminate\Http\Response
     */
    public function edit(Layanan $layanan)
    {
        $layanans = Dokter::all();

        return view('admin.layanan.edit', compact('layanan', 'layanans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Layanan $layanan
     * @return \Illuminate\Http\Response
     */
    public function update(layananRequest $request, Layanan$layanan)
    {
        $validatedData = $request->all();
        $layanan->update($validatedData);

        return redirect()->route('layanan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Layanan $layanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Layanan$layanan)
    {
        $layanan->delete();

        return redirect()->route('layanan.index');
    }
}
