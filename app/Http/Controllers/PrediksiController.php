<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilNaive;
use App\Models\DataTrain;

class PrediksiController extends Controller
{
    /**
     * Fungsi untuk menghitung algoritma Naive Bayes dan menyimpan ke hasil_naive
     */
    public function hitungPrediksi(Request $request)
    {
        // Validasi input dari user
        $request->validate([
            'ph' => 'required',
            'suhu' => 'required',
            'kesehatan' => 'required',
        ]);

        $ph = $request->input('ph');
        $suhu = $request->input('suhu');
        $kesehatan = $request->input('kesehatan');

        // --- MULAI PERHITUNGAN NAIVE BAYES ---
        // 1. Ambil Data Latih
        $dataTrain = DataTrain::all();
        $totalData = $dataTrain->count();

        if ($totalData == 0) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data latih masih kosong. Silakan isi tabel data_train terlebih dahulu.'
            ], 400);
        }

        // 2. Hitung Prior Probability (Probabilitas Kemunculan Setiap Kelas Target)
        $kelasCount = [];
        foreach ($dataTrain as $data) {
            $ket = strtolower($data->ket);
            if (!isset($kelasCount[$ket])) {
                $kelasCount[$ket] = 0;
            }
            $kelasCount[$ket]++;
        }

        $prior = [];
        foreach ($kelasCount as $ket => $count) {
            $prior[$ket] = $count / $totalData;
        }

        // 3. Hitung Likelihood Probability (Probabilitas Kondisional)
        $hasilProbabilitas = [];
        foreach ($kelasCount as $ket => $countKelas) {
            $probPh = $this->hitungLikelihood('ph', $ph, $ket, $countKelas, $dataTrain);
            $probSuhu = $this->hitungLikelihood('suhu', $suhu, $ket, $countKelas, $dataTrain);
            $probKesehatan = $this->hitungLikelihood('kesehatan', $kesehatan, $ket, $countKelas, $dataTrain);

            // Hitung Posterior (Prior * Likelihood)
            $posterior = $prior[$ket] * $probPh * $probSuhu * $probKesehatan;
            $hasilProbabilitas[$ket] = $posterior;
        }

        // 4. Tentukan hasil prediksi tertinggi
        arsort($hasilProbabilitas);
        $kelasPrediksi = array_key_first($hasilProbabilitas);

        $hasilNormal = $hasilProbabilitas['normal'] ?? 0;
        $hasilTidak = $hasilProbabilitas['tidak'] ?? 0;
        // --- SELESAI PERHITUNGAN NAIVE BAYES ---

        // Simpan hasil probabilitas ke tabel hasil_naive
        $hasilNaive = new HasilNaive();
        $hasilNaive->keterangan = $kelasPrediksi;
        $hasilNaive->ph = $ph;
        $hasilNaive->suhu = $suhu;
        $hasilNaive->kesehatan = $kesehatan;
        
        $hasilNaive->hasil_normal = number_format($hasilNormal, 8);
        $hasilNaive->hasil_tidak = number_format($hasilTidak, 8);
        $hasilNaive->save();

        return response()->json([
            'pesan' => 'Prediksi berhasil dihitung dan disimpan!',
            'data_input' => [
                'ph' => $ph,
                'suhu' => $suhu,
                'kesehatan' => $kesehatan
            ],
            'prediksi_akhir' => $kelasPrediksi,
            'detail_probabilitas' => $hasilProbabilitas,
            'id_hasil' => $hasilNaive->id_hasil,
            'hasil_normal' => $hasilNormal,
            'hasil_tidak' => $hasilTidak,
        ]);
    }

    /**
     * Hitung P(Atribut | Kelas) dengan teknik Laplace Smoothing
     */
    private function hitungLikelihood($namaAtribut, $nilaiAtribut, $kelas, $totalKelas, $semuaDataTrain)
    {
        $jumlahCocok = $semuaDataTrain
            ->where($namaAtribut, $nilaiAtribut)
            ->filter(function($item) use ($kelas) {
                return strtolower($item->ket) === $kelas;
            })
            ->count();
        
        // Laplace Smoothing jika nilai 0 agar perkalian tidak menjadi 0
        if ($jumlahCocok == 0) {
            $jumlahVariasiAtributUnik = $semuaDataTrain->unique($namaAtribut)->count();
            return ($jumlahCocok + 1) / ($totalKelas + $jumlahVariasiAtributUnik);
        }

        return $jumlahCocok / $totalKelas;
    }
}
