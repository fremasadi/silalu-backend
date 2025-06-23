<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Case 1: User belum login
        if (!$user) {
            Log::warning('Unauthorized access attempt to admin area - No user authenticated', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);

            return redirect()
                ->route('filament.admin.auth.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman admin.');
        }

        // Case 2: User login tapi bukan admin
        if ($user->role !== 'admin') {
            Log::warning('Access denied - User is not admin', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('filament.admin.auth.login')
                ->withErrors([
                    'email' => 'Akses ditolak. Anda tidak memiliki hak akses sebagai admin.',
                ])
                ->with('warning', 'Anda telah dikeluarkan dari sistem karena tidak memiliki akses admin.');
        }

        // Case 3: User adalah admin tapi account tidak aktif (opsional, jika ada status field)
        if (isset($user->status) && $user->status !== 'active') {
            Log::warning('Access denied - Admin account is not active', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_status' => $user->status,
                'ip' => $request->ip()
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('filament.admin.auth.login')
                ->withErrors([
                    'email' => 'Akun admin Anda tidak aktif. Hubungi administrator sistem.',
                ])
                ->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        // Log successful admin access (opsional, untuk audit trail)
        Log::info('Admin access granted', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip' => $request->ip(),
            'url' => $request->fullUrl()
        ]);

        return $next($request);
    }
}