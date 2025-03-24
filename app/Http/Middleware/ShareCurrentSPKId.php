<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PengajuanPinjaman;
use Symfony\Component\HttpFoundation\Response;

class ShareCurrentSPKId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    $currentSpkId = PengajuanPinjaman::latest('id')->value('id'); // Contoh mengambil ID terakhir
    view()->share('currentSpkId', $currentSpkId);

    return $next($request);
}

}
