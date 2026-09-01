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
        // Tambahkan user_id pada tabel tambak jika belum ada
        if (Schema::hasTable('tambak') && !Schema::hasColumn('tambak', 'user_id')) {
            Schema::table('tambak', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }

        // Tambahkan user_id pada tabel timbangan jika belum ada
        if (Schema::hasTable('timbangan') && !Schema::hasColumn('timbangan', 'user_id')) {
            Schema::table('timbangan', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }

        // Tambahkan user_id dan id_tambak pada tabel hasil_naive jika belum ada
        if (Schema::hasTable('hasil_naive')) {
            Schema::table('hasil_naive', function (Blueprint $table) {
                if (!Schema::hasColumn('hasil_naive', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('hasil_naive', 'id_tambak')) {
                    $table->string('id_tambak', 50)->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('hasil_naive', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        // Tambahkan parameter lengkap pada tabel prediksi jika belum ada
        if (Schema::hasTable('prediksi')) {
            Schema::table('prediksi', function (Blueprint $table) {
                if (!Schema::hasColumn('prediksi', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id_hasil');
                }
                if (!Schema::hasColumn('prediksi', 'jenis_ikan')) {
                    $table->string('jenis_ikan', 50)->nullable()->after('id_tambak');
                }
                if (!Schema::hasColumn('prediksi', 'bulan')) {
                    $table->integer('bulan')->nullable()->after('jenis_ikan');
                }
                if (!Schema::hasColumn('prediksi', 'keadaan_tambak')) {
                    $table->string('keadaan_tambak', 50)->nullable()->after('bulan');
                }
                if (!Schema::hasColumn('prediksi', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tambak') && Schema::hasColumn('tambak', 'user_id')) {
            Schema::table('tambak', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasTable('timbangan') && Schema::hasColumn('timbangan', 'user_id')) {
            Schema::table('timbangan', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasTable('hasil_naive')) {
            Schema::table('hasil_naive', function (Blueprint $table) {
                if (Schema::hasColumn('hasil_naive', 'user_id')) {
                    $table->dropColumn('user_id');
                }
                if (Schema::hasColumn('hasil_naive', 'id_tambak')) {
                    $table->dropColumn('id_tambak');
                }
            });
        }

        if (Schema::hasTable('prediksi')) {
            Schema::table('prediksi', function (Blueprint $table) {
                if (Schema::hasColumn('prediksi', 'user_id')) {
                    $table->dropColumn('user_id');
                }
                if (Schema::hasColumn('prediksi', 'jenis_ikan')) {
                    $table->dropColumn('jenis_ikan');
                }
                if (Schema::hasColumn('prediksi', 'bulan')) {
                    $table->dropColumn('bulan');
                }
                if (Schema::hasColumn('prediksi', 'keadaan_tambak')) {
                    $table->dropColumn('keadaan_tambak');
                }
            });
        }
    }
};
