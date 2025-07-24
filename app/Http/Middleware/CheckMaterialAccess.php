<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckMaterialAccess
{
    public function handle($request, Closure $next)
    {
        $materialId = $request->route('id'); // Sesuaikan dengan parameter route
        
        // Tambahkan logika pengecekan akses di sini
        // Contoh: cek apakah user memiliki akses ke materi tersebut
        
        return $next($request);
    }
}