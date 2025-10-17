<?php
// database/migrations/2024_01_01_000000_create_pertanyaan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->foreignId('kategori_id')->constrained('kategori_inspeksis')->onDelete('cascade');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pertanyaan');
    }
};