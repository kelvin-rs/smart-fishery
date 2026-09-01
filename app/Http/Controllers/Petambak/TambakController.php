<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\Tambak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TambakController extends Controller
{
    /**
     * Menampilkan daftar tambak milik akun petambak yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Tampilkan tambak yang terdaftar untuk user yang login
        $tambaks = Tambak::where('user_id', $user->id)
            ->orWhere('id', $user->id_tambak)
            ->orderBy('id', 'asc')
            ->get();

        return view('petambak.tambak.index', compact('user', 'tambaks'));
    }

    /**
     * Mendaftarkan unit kolam tambak baru untuk petambak.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'alamat' => 'required|string|max:100',
            'banyak_benih' => 'required|integer|min:1',
            'jenis_ikan' => 'required|string|in:Bandeng,Vaname,Windu',
            'nomor' => 'required|integer|min:1',
        ], [
            'alamat.required' => 'Alamat lokasi tambak wajib diisi.',
            'banyak_benih.required' => 'Jumlah benih ikan wajib diisi.',
            'jenis_ikan.required' => 'Pilih jenis komoditas ikan/udang.',
            'nomor.required' => 'Nomor kolam/tambak wajib diisi.',
        ]);

        $tambak = Tambak::create([
            'user_id' => $user->id,
            'alamat' => $request->alamat,
            'banyak_benih' => $request->banyak_benih,
            'jenis_ikan' => $request->jenis_ikan,
            'nomor' => $request->nomor,
        ]);

        // Hubungkan tambak ke user jika user belum memiliki id_tambak
        if (!$user->id_tambak) {
            $user->id_tambak = $tambak->id;
            $user->save();
        }

        return redirect()->route('petambak.tambak.index')
            ->with('success', 'Data tambak baru berhasil didaftarkan ke sistem.');
    }

    /**
     * Menghapus unit tambak milik petambak yang sedang login.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $tambak = Tambak::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('id', $user->id_tambak);
            })
            ->firstOrFail();

        $tambakNomor = $tambak->nomor ?? $tambak->id;
        $tambak->delete();

        // Jika tambak yang dihapus adalah id_tambak aktif di user, alihkan ke tambak lain atau null
        if ($user->id_tambak == $id) {
            $nextTambak = Tambak::where('user_id', $user->id)->first();
            $user->id_tambak = $nextTambak->id ?? null;
            $user->save();
        }

        return redirect()->route('petambak.tambak.index')
            ->with('success', "Tambak #{$tambakNomor} berhasil dihapus dari daftar tambak Anda.");
    }
}
