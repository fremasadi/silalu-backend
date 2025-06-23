<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            Auth::logout();

            return redirect()
                ->route('filament.admin.auth.login') // Pastikan ini sesuai dengan route login Filament kamu
                ->withErrors([
                    'email' => 'Akses ditolak. Anda bukan admin.',
                ]);
        }

        return $next($request);
    }
}
