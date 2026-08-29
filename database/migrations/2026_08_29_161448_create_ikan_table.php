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
        if (!Schema::hasTable('ikan')) {
            Schema::create('ikan', function (Blueprint $table) {
                $table->id();
                $table->string('id_tambak', 50)->nullable();
                $table->timestamp('waktu')->nullable();
                $table->decimal('ph', 8, 2)->nullable();
                $table->decimal('suhu', 8, 2)->nullable();
                $table->string('jenis_ikan', 50)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ikan');
    }
};
