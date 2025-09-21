<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Pivot;
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
      $dokterSchedules = Dokter::with([
        'layananJadwals' => function ($query) {
          $query
            ->where('is_available', true)
            ->orderBy('hari')
            ->orderBy('waktu_mulai');
        },
        'jadwals' => function ($query) {
          $query
            ->where('is_available', true)
            ->orderBy('hari')
            ->orderBy('waktu_mulai');
        },
      ])
        ->get()
        ->map(function (Dokter $dokter) {
          $layananJadwals = $dokter->layananJadwals->keyBy('id');

          $dokter->jadwals
            ->reject(function ($jadwal) use ($layananJadwals) {
              return $layananJadwals->has($jadwal->id);
            })
            ->each(function ($jadwal) use ($dokter, $layananJadwals) {
              $pivotAttributes = [
                'dokter_id' => $dokter->id,
                'jadwal_id' => $jadwal->id,
                'layanan_id' => null,
              ];

              if (isset($jadwal->pivot?->created_at)) {
                $pivotAttributes['created_at'] = $jadwal->pivot->created_at;
              }

              if (isset($jadwal->pivot?->updated_at)) {
                $pivotAttributes['updated_at'] = $jadwal->pivot->updated_at;
              }

              $pivot = Pivot::fromAttributes($dokter, $pivotAttributes, 'dokter_layanan');
              $pivot->pivotRelated = $jadwal;

              $jadwal->setRelation('pivot', $pivot);

              $layananJadwals->put($jadwal->id, $jadwal);
            });

          $orderedJadwals = $layananJadwals
            ->sortBy(function ($jadwal) {
              return sprintf('%s-%s', $jadwal->hari, $jadwal->waktu_mulai);
            })
            ->values();

          $dokter->setRelation('layananJadwals', $orderedJadwals);

          return $dokter;
        });
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
