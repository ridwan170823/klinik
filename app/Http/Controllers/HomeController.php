<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $dokter = Dokter::count();
    $antrian = Antrian::count();
    $perjanjians = collect();
    $dokterSchedules = collect();

    if (Auth::user()?->role === 'dokter') {
      $perjanjians = Auth::user()
        ->perjanjians()
        ->latest('waktu_perjanjian')
        ->get();
    }

    if (Auth::user()?->role === 'pasien') {
      $dokterSchedules = Dokter::with(['layananJadwals' => function ($query) {
        $query
          ->where('is_available', true)
          ->orderBy('hari')
          ->orderBy('waktu_mulai');
      }])->get();
    }
    $data = [
      
      'dokter' => $dokter,
      'antrian' => $antrian,
      'perjanjians' => $perjanjians,
      'dokterSchedules' => $dokterSchedules,
    ];
    return view('home', $data);
  }
}
