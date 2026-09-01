<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\DataTrain;
use App\Models\HasilNaive;
use App\Models\Prediksi;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    /**
     * Menampilkan data sumber data kualitas air dan prediksi panen yang tersimpan di Database.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'kualitas_air');
        $searchKualitas = $request->query('search_kualitas');
        $searchPrediksi = $request->query('search_prediksi');

        // Query Sumber Data Kualitas Air (data_train)
        $queryKualitas = DataTrain::query();
        if ($searchKualitas) {
            $queryKualitas->where(function ($q) use ($searchKualitas) {
                $q->where('ket', 'like', "%{$searchKualitas}%")
                  ->orWhere('kesehatan', 'like', "%{$searchKualitas}%")
                  ->orWhere('suhu', 'like', "%{$searchKualitas}%")
                  ->orWhere('ph', 'like', "%{$searchKualitas}%");
            });
        }
        $dataKualitasList = $queryKualitas->orderBy('no_train', 'desc')
            ->paginate(15, ['*'], 'page_kualitas')
            ->withQueryString();

        // Query Sumber Data Prediksi Panen (prediksi)
        $queryPrediksi = Prediksi::with('tambak');
        if ($searchPrediksi) {
            $queryPrediksi->where(function ($q) use ($searchPrediksi) {
                $q->where('prediksi', 'like', "%{$searchPrediksi}%")
                  ->orWhere('id_tambak', 'like', "%{$searchPrediksi}%");
            });
        }
        $dataPrediksiList = $queryPrediksi->orderBy('id_hasil', 'desc')
            ->paginate(15, ['*'], 'page_prediksi')
            ->withQueryString();

        $totalDataKualitas = DataTrain::count();
        $totalDataPrediksi = Prediksi::count();
        $totalHasilNaive = HasilNaive::count();

        return view('petambak.dataset.index', compact(
            'tab',
            'dataKualitasList',
            'dataPrediksiList',
            'totalDataKualitas',
            'totalDataPrediksi',
            'totalHasilNaive',
            'searchKualitas',
            'searchPrediksi'
        ));
    }

    /**
     * Menerima file CSV dari pengguna dan menyimpannya langsung ke database sesuai kategori.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file_dataset' => 'required|file|mimes:csv,txt|max:5120',
            'kategori' => 'required|string|in:kualitas_air,prediksi_panen',
        ], [
            'file_dataset.required' => 'Pilih file dataset CSV/TXT terlebih dahulu.',
            'file_dataset.mimes' => 'Format file harus berupa .csv atau .txt.',
            'kategori.required' => 'Pilih kategori sumber data yang valid.',
        ]);

        $file = $request->file('file_dataset');
        $kategori = $request->input('kategori');

        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');
        $rows = [];
        $count = 0;

        if ($kategori === 'kualitas_air') {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) >= 4) {
                    if (count($data) >= 6) {
                        $rows[] = [
                            'suhu' => trim($data[2]),
                            'ph' => trim($data[3]),
                            'kesehatan' => trim($data[4]),
                            'ket' => trim($data[5]),
                        ];
                    } else {
                        $rows[] = [
                            'suhu' => trim($data[0]),
                            'ph' => trim($data[1]),
                            'kesehatan' => trim($data[2]),
                            'ket' => trim($data[3]),
                        ];
                    }
                    $count++;
                }

                if (count($rows) >= 100) {
                    DataTrain::insert($rows);
                    $rows = [];
                }
            }

            if (!empty($rows)) {
                DataTrain::insert($rows);
            }
        } elseif ($kategori === 'prediksi_panen') {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) >= 2) {
                    $rows[] = [
                        'id_tambak' => trim($data[0]),
                        'prediksi' => trim($data[1]),
                    ];
                    $count++;
                }

                if (count($rows) >= 100) {
                    Prediksi::insert($rows);
                    $rows = [];
                }
            }

            if (!empty($rows)) {
                Prediksi::insert($rows);
            }
        }

        fclose($handle);

        $tab = ($kategori === 'prediksi_panen') ? 'prediksi_panen' : 'kualitas_air';

        return redirect()->route('petambak.dataset.index', ['tab' => $tab])
            ->with('success', "Sebanyak {$count} baris data berhasil diimpor dan disimpan ke database.");
    }
}
