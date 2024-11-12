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
        Schema::create('risets', function (Blueprint $table) {
            $table->id();
            $table->string('judul_riset')->nullable();
            $table->string('slug_riset')->unique();
            $table->longText('deskripsi_riset')->nullable();
            $table->string('tahun_riset')->nullable();

            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('set null');

            $table->string('nama_peneliti')->nullable();
            $table->string('alamat_peneliti')->nullable();
            $table->enum('daerah_peneliti', [
                'kota mataram',
                'kab. lombok barat',
                'kab. lombok timur',
                'kab. lombok utara',
                'kab. lombok tengah',
                'kab. sumbawa',
                'kab. sumbawa barat',
                'kab. bima',
                'kota bima',
                'kab. dompu'
            ])->nullable();
            $table->enum('desiminasi_riset', ['ya', 'tidak'])->default('tidak')->nullable();
            
            $table->unsignedBigInteger('instansi_id')->nullable();
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('set null');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risets');
    }
};
