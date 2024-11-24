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
            $table->string('kontak_peneliti')->nullable();
            $table->enum('daerah_peneliti', [
                'kota_mataram',
                'kab_lombok_barat',
                'kab_lombok_timur',
                'kab_lombok_utara',
                'kab_lombok_tengah',
                'kab_sumbawa',
                'kab_sumbawa_barat',
                'kab_bima',
                'kota_bima',
                'kab_dompu',
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
