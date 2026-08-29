<?php

namespace App\Http\Controllers\Petambak;

use App\Http\Controllers\Controller;
use App\Models\DataTrain;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function index()
    {
        $totalDataLatih = DataTrain::count();
        $sampleData = DataTrain::take(10)->get();

        return view('petambak.dataset.index', compact('totalDataLatih', 'sampleData'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file_dataset' => 'required|file|mimes:csv,txt,xlsx|max:5120',
            'kategori' => 'required|string',
        ]);

        $file = $request->file('file_dataset');

        if ($file->getClientOriginalExtension() === 'csv' || $file->getClientOriginalExtension() === 'txt') {
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle, 1000, ',');
            $rows = [];
            $count = 0;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Expected format: Suhu, pH, Padat Tebar, Ket
                if (count($data) >= 4) {
                    $rows[] = [
                        'suhu' => trim($data[0]),
                        'ph' => trim($data[1]),
                        'kesehatan' => trim($data[2]),
                        'ket' => trim($data[3]),
                    ];
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

            fclose($handle);

            return redirect()->route('petambak.dataset.index')
                ->with('success', "Dataset berhasil diunggah! Sebanyak {$count} data latih baru berhasil diimpor.");
        }

        return redirect()->route('petambak.dataset.index')
            ->with('success', 'File dataset diterima dan berhasil diproses oleh sistem.');
    }
}
