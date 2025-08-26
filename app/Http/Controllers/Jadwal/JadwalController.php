<?php

namespace App\Http\Controllers\Jadwal;

use App\Models\Jadwal;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Jadwal\JadwalRequest;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil jadwal beserta data dokter
        $jadwals = Jadwal::with('dokters')->get();

        $data = [
            'jadwals' => $jadwals
        ];

        if (Auth::user()->role == 'admin') {
            return view('admin.jadwal.index', $data);
        }

        return view('jadwal.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ambil semua dokter untuk dropdown
        $dokters = Dokter::all();
        $timeSlots = config('jadwal.time_slots');

        return view('admin.jadwal.create', compact('dokters', 'timeSlots'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JadwalRequest $request)
    {
        $validatedData = $request->all();
        Jadwal::create($validatedData);

        return redirect()->route('jadwal.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jadwal  $jadwal
     * @return \Illuminate\Http\Response
     */
    public function edit(Jadwal $jadwal)
    {
        $dokters = Dokter::all();
        $timeSlots = config('jadwal.time_slots');

        
        return view('admin.jadwal.edit', compact('jadwal', 'dokters', 'timeSlots'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jadwal  $jadwal
     * @return \Illuminate\Http\Response
     */
    public function update(JadwalRequest $request, Jadwal $jadwal)
    {
        $validatedData = $request->all();
        $jadwal->update($validatedData);

        return redirect()->route('jadwal.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jadwal  $jadwal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index');
    }
}
