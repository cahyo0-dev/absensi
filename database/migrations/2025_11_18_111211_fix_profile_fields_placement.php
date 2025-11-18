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
            $columnsToDrop = ['profile_photo', 'phone'];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Untuk rollback, tambahkan kembali kolom
            // $table->string('profile_photo')->nullable();
            // $table->string('phone')->nullable();
        });
    }
};
