<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Hapus status_absen jika ada
            $table->dropColumn('status_absen');

            // Tambah field untuk absensi masuk dan pulang
            $table->timestamp('waktu_masuk')->nullable()->after('provinsi');
            $table->timestamp('waktu_pulang')->nullable()->after('waktu_masuk');
            $table->text('tanda_tangan_masuk')->nullable()->after('waktu_pulang');
            $table->text('tanda_tangan_pulang')->nullable()->after('tanda_tangan_masuk');
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->enum('status_absen', ['masuk', 'pulang'])->default('masuk');
            $table->dropColumn(['waktu_masuk', 'waktu_pulang', 'tanda_tangan_masuk', 'tanda_tangan_pulang']);
        });
    }
};
