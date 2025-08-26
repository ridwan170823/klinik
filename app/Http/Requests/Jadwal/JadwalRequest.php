<?php

namespace App\Http\Requests\Jadwal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JadwalRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $slots = config('jadwal.time_slots');
    $starts = array_column($slots, 'mulai');
    $ends = array_column($slots, 'selesai');
    return [
      'hari' => 'required',
      'waktu_mulai' => ['required', Rule::in($starts)],
      'waktu_selesai' => [
        'required',
        Rule::in($ends),
        function ($attribute, $value, $fail) use ($slots) {
          $start = $this->input('waktu_mulai');
          $valid = collect($slots)->contains(function ($slot) use ($start, $value) {
            return $slot['mulai'] === $start && $slot['selesai'] === $value;
          });
          if (! $valid) {
            $fail('Waktu mulai dan selesai tidak sesuai dengan slot yang diizinkan.');
          }
        }
      ],
    ];
  }
}
