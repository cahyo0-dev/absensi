<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inspeksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengawas_id')->constrained('users')->onDelete('cascade');
            $table->text('tanda_tangan');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspeksis');
    }
};