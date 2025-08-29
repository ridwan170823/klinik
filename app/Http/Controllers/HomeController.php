<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Antrian;
use Illuminate\Http\Request;


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
    $data = [
      
      'dokter' => $dokter,
      'antrian' => $antrian,
    ];
    return view('home', $data);
  }
}
