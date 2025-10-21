<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspeksi_id')->constrained('inspeksis')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan')->onDelete('cascade');
            $table->enum('jawaban', ['Ya', 'Tidak']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jawabans');
    }
};