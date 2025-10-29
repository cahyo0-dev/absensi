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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20);
            $table->string('nama', 100);
            $table->string('jabatan', 100);
            $table->string('unit_kerja', 100);
            $table->string('provinsi', 100);
            $table->text('tanda_tangan'); // untuk menyimpan base64 signature
            $table->timestamp('waktu_absen')->useCurrent();
            $table->timestamps();
            
            // Index untuk pencarian yang lebih cepat
            $table->index('nip');
            $table->index('waktu_absen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};