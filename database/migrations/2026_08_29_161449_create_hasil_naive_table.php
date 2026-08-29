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
        Schema::create('hasil_naive', function (Blueprint $table) {
            $table->id('id_hasil');
            $table->string('keterangan', 20)->nullable();
            $table->string('ph', 20)->nullable();
            $table->string('suhu', 20)->nullable();
            $table->string('kesehatan', 20)->nullable();
            $table->string('hasil_tidak', 20)->nullable();
            $table->string('hasil_normal', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_naive');
    }
};
