<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // TAMBAH kolom yang missing
            $table->string('nip')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('provinsi')->nullable();

            // Ubah kolom name menjadi nullable
            $table->string('name')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            $table->dropColumn(['nip', 'jabatan', 'unit_kerja', 'provinsi']);

            // Kembalikan kolom name ke not nullable
            $table->string('name')->nullable(false)->change();
        });
    }
};
