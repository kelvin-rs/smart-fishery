<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Tampilkan form Login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Proses Login
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Sanitasi intended URL agar tidak terjadi redirect silang role
            $intended = session()->get('url.intended');
            if ($intended) {
                if ($user->role === 'kud' && str_contains($intended, '/petambak')) {
                    session()->forget('url.intended');
                } elseif ($user->role === 'petambak' && str_contains($intended, '/kud')) {
                    session()->forget('url.intended');
                }
            }

            return redirect()->intended($this->getRedirectRoute($user))
                ->with('success', 'Selamat datang kembali, ' . ($user->username ?? 'User') . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan form Registrasi
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    /**
     * Proses Registrasi
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role'] = $data['role'] ?? 'petambak';

        $user = $this->userRepo->create($data);

        Auth::login($user);

        return redirect($this->getRedirectRoute($user))
            ->with('success', 'Registrasi akun berhasil! Selamat datang di Smart Fishery.');
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Helper redirect rute berdasarkan role
     */
    private function getRedirectRoute($user): string
    {
        if ($user->role === 'kud') {
            return route('kud.dashboard');
        }
        return route('petambak.dashboard');
    }

    private function redirectBasedOnRole($user)
    {
        return redirect($this->getRedirectRoute($user));
    }
}
