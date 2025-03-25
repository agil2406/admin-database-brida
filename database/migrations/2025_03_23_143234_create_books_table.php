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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->unique();
            $table->string('slug_buku')->unique();
            $table->text('sinopsis')->nullable();
            $table->string('penulis')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('tanggal_terbit')->nullable();
            $table->string('isbn')->nullable();
            $table->string('cover')->nullable();
            $table->string('negara')->nullable();
            $table->string('link_buku')->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
