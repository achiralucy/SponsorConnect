<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVerifikasi
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status_verifikasi !== 'terverifikasi') {
            return redirect()->route('verification.status');
        }

        return $next($request);
    }
}
