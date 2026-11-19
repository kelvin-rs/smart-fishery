<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rentang Normal Padat Tebar (ekor per m²)
    |--------------------------------------------------------------------------
    | Kepadatan tambak dihitung dari: banyak_benih / luas_lahan (m²).
    | - Bandeng : 5,6 – 8,4 ekor/m²   (contoh 715 m² → 4.000 – 6.000 ekor)
    | - Vaname  : 100 – 150 ekor/m²   (contoh 50 m²  → 5.000 – 7.500 ekor)
    | - Windu   : 80  – 125 ekor/m²   (contoh 50 m²  → 4.000 – 6.250 ekor)
    | Di luar rentang min–max → "Tidak Normal".
    */
    'padat_tebar_range' => [
        'Bandeng' => [
            'min' => (float) env('PADAT_TEBAR_MIN_BANDENG', 5.6),
            'max' => (float) env('PADAT_TEBAR_MAX_BANDENG', 8.4),
        ],
        'Vaname' => [
            'min' => (float) env('PADAT_TEBAR_MIN_VANAME', 100),
            'max' => (float) env('PADAT_TEBAR_MAX_VANAME', 150),
        ],
        'Windu' => [
            'min' => (float) env('PADAT_TEBAR_MIN_WINDU', 80),
            'max' => (float) env('PADAT_TEBAR_MAX_WINDU', 125),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Minimal Data Hasil Naïve Bayes untuk Prediksi Panen
    |--------------------------------------------------------------------------
    | Prediksi panen suatu tambak hanya boleh dijalankan jika tambak tersebut
    | sudah memiliki minimal sejumlah data hasil uji kualitas air.
    */
    'min_data_naive' => (int) env('MIN_DATA_NAIVE', 15),

];
