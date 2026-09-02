<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('tambak') && !Schema::hasColumn('tambak', 'luas_lahan')) {
            Schema::table('tambak', function (Blueprint $table) {
                $table->double('luas_lahan', 10, 2)->nullable()->default(800)->after('banyak_benih');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tambak') && Schema::hasColumn('tambak', 'luas_lahan')) {
            Schema::table('tambak', function (Blueprint $table) {
                $table->dropColumn('luas_lahan');
            });
        }
    }
};
