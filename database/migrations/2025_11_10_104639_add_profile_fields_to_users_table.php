<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('provinsi');
            $table->string('phone')->nullable()->after('profile_photo');
            $table->json('settings')->nullable()->after('phone');
            $table->timestamp('last_login_at')->nullable()->after('settings');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo',
                'phone',
                'settings',
                'last_login_at'
            ]);
        });
    }
};