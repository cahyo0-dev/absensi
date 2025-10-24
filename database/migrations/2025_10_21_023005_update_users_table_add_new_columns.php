<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom baru
            
            // Hapus kolom email_verified_at jika tidak diperlukan
            // $table->dropColumn('email_verified_at');
            
            // Ubah kolom name jika perlu
            $table->string('name')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'jabatan', 'unit_kerja', 'provinsi', 'role']);
            // Kembalikan kolom name jika diubah
            $table->string('name')->change();
        });
    }
};