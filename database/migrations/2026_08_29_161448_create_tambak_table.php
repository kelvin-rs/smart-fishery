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
        Schema::create('tambak', function (Blueprint $table) {
            $table->id();
            $table->string('id_tambak', 50)->nullable();
            $table->string('alamat', 100)->nullable();
            $table->integer('banyak_benih')->nullable();
            $table->string('jenis_ikan', 50)->nullable();
            $table->integer('nomer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambak');
    }
};
