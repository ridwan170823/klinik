<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user->role === 'pasien') {
            $required = ['name', 'email', 'alamat', 'no_telp'];
            foreach ($required as $field) {
                if (empty($user->{$field})) {
                    return redirect()->route('profile.edit')
                        ->with('error', 'Lengkapi profil Anda sebelum membuat antrian.');
                }
            }
        }
        return $next($request);
    }
}