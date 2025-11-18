<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // HAPUS kolom yang tidak dipakai jika ada
            if (Schema::hasColumn('users', 'profile_photo')) {
                $table->dropColumn('profile_photo');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }

            // Atau jika mau TAMBAH kolom yang diperlukan (tanpa after())
            // $table->string('nama_kolom_baru')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Jika perlu rollback, tambahkan kembali kolom yang dihapus
            // $table->string('profile_photo')->nullable();
            // $table->string('phone')->nullable();
        });
    }
};
