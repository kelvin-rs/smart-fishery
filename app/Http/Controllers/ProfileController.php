<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman formulir edit profil untuk Petambak.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('petambak.profile.index', compact('user'));
    }

    /**
     * Update data profil petambak.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password_current' => ['nullable', 'required_with:password_new', 'current_password'],
            'password_new' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username telah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email telah digunakan.',
            'password_current.current_password' => 'Password saat ini salah.',
            'password_new.min' => 'Password baru minimal 6 karakter.',
            'password_new.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if (!empty($validated['password_new'])) {
            $user->password = Hash::make($validated['password_new']);
        }

        $user->save();

        return redirect()->route('petambak.profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Tampilkan halaman formulir edit profil untuk KUD.
     */
    public function editKud()
    {
        $user = Auth::user();
        return view('kud.profile.index', compact('user'));
    }

    /**
     * Update data profil KUD.
     */
    public function updateKud(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password_current' => ['nullable', 'required_with:password_new', 'current_password'],
            'password_new' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username telah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email telah digunakan.',
            'password_current.current_password' => 'Password saat ini salah.',
            'password_new.min' => 'Password baru minimal 6 karakter.',
            'password_new.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if (!empty($validated['password_new'])) {
            $user->password = Hash::make($validated['password_new']);
        }

        $user->save();

        return redirect()->route('kud.profile.edit')->with('success', 'Profil KUD Anda berhasil diperbarui.');
    }
}
