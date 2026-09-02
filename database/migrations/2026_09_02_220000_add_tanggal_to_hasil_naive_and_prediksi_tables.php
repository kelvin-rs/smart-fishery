<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('hasil_naive') && !Schema::hasColumn('hasil_naive', 'tanggal')) {
            Schema::table('hasil_naive', function (Blueprint $table) {
                $table->date('tanggal')->nullable()->after('id_tambak');
            });
        }

        if (Schema::hasTable('prediksi') && !Schema::hasColumn('prediksi', 'tanggal')) {
            Schema::table('prediksi', function (Blueprint $table) {
                $table->date('tanggal')->nullable()->after('id_tambak');
            });
        }

        // Backfill existing NULL timestamps and tanggal
        DB::table('hasil_naive')->whereNull('tanggal')->update([
            'tanggal' => DB::raw('COALESCE(DATE(created_at), CURDATE())'),
            'created_at' => DB::raw('COALESCE(created_at, NOW())'),
            'updated_at' => DB::raw('COALESCE(updated_at, NOW())'),
        ]);

        DB::table('prediksi')->whereNull('tanggal')->update([
            'tanggal' => DB::raw('COALESCE(DATE(created_at), CURDATE())'),
            'created_at' => DB::raw('COALESCE(created_at, NOW())'),
            'updated_at' => DB::raw('COALESCE(updated_at, NOW())'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('hasil_naive') && Schema::hasColumn('hasil_naive', 'tanggal')) {
            Schema::table('hasil_naive', function (Blueprint $table) {
                $table->dropColumn('tanggal');
            });
        }

        if (Schema::hasTable('prediksi') && Schema::hasColumn('prediksi', 'tanggal')) {
            Schema::table('prediksi', function (Blueprint $table) {
                $table->dropColumn('tanggal');
            });
        }
    }
};
