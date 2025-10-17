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
        // Migration untuk modifikasi users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->unique()->after('id');
            $table->string('jabatan')->after('name');
            $table->string('unit_kerja')->after('jabatan');
            $table->string('provinsi')->after('unit_kerja');
            $table->enum('role', ['admin', 'pengawas', 'pegawai'])->default('pegawai')->after('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
