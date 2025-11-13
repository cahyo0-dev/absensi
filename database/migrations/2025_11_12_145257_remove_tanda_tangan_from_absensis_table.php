<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Hapus kolom tanda_tangan lama
            $table->dropColumn('tanda_tangan');
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Tambah kembali kolom tanda_tangan jika rollback
            $table->text('tanda_tangan')->nullable();
        });
    }
};
