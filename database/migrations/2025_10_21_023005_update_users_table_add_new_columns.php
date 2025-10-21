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
            $table->string('nip')->unique()->after('id');
            $table->string('jabatan')->after('name');
            $table->string('unit_kerja')->after('jabatan');
            $table->string('provinsi')->after('unit_kerja');
            $table->enum('role', ['admin', 'pengawas'])->default('pengawas')->after('provinsi');
            
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