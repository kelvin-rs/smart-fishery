<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  Daftar role yang diizinkan (misal: 'petambak', 'kud', 'admin')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Verifikasi Status Autentikasi Pengguna
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sesi tidak terautentikasi. Silakan login kembali.',
                ], 401);
            }

            return redirect()->guest(route('login'))->with('error', 'Sesi Anda telah berakhir atau belum login. Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $userRole = strtolower(trim($user->role ?? ''));

        // 2. Normalisasi Daftar Role yang Diizinkan (mendukung koma atau array variadic)
        $allowedRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $subRole) {
                $trimmed = strtolower(trim($subRole));
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        // 3. Super Admin selalu diizinkan jika memiliki role admin
        if ($userRole === 'admin') {
            return $next($request);
        }

        // 4. Periksa Apakah Role Pengguna Termasuk Dalam Role yang Diizinkan
        if (!in_array($userRole, $allowedRoles, true)) {
            Log::warning("Akses Ditolak [RoleMismatch]: User #{$user->id} ({$user->username} - role: {$userRole}) mencoba mengakses URL: {$request->fullUrl()} yang mensyaratkan role: " . implode(', ', $allowedRoles));

            // Respon khusus jika request berupa AJAX atau API
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'forbidden',
                    'message' => 'Akses ditolak: Anda tidak memiliki wewenang untuk mengakses endpoint ini.',
                ], 403);
            }

            // Tentukan rute dashboard tujuan sesuai hak akses pengguna
            $targetDashboard = match ($userRole) {
                'kud' => 'kud.dashboard',
                'petambak' => 'petambak.dashboard',
                default => 'home',
            };

            $allowedDisplay = strtoupper(implode(' / ', $allowedRoles));

            return redirect()->route($targetDashboard)->with('error', "Akses Dibatasi: Halaman tersebut khusus untuk {$allowedDisplay}. Anda telah dialihkan ke dashboard akun Anda.");
        }

        return $next($request);
    }
}
