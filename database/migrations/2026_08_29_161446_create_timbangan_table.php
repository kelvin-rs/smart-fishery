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
        if (!Schema::hasTable('timbangan')) {
            Schema::create('timbangan', function (Blueprint $table) {
                $table->id();
                $table->string('id_tambak', 50)->nullable();
                $table->date('tanggal_panen')->nullable();
                $table->integer('banyak_panen')->nullable();
                $table->string('jenis_ikan', 50)->nullable();
                $table->integer('total')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timbangan');
    }
};
