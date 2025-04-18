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
        Schema::create('inovasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_inovasi')->nullable();
            $table->string('slug_inovasi')->unique();

            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('set null');

            $table->string('tahun_inovasi')->nullable();
            $table->string('fungsi_inovasi')->nullable();
            $table->enum('sertifikat_inovasi', ['ya', 'tidak'])->default('tidak')->nullable();
            $table->enum('desiminasi_inovasi', ['ya', 'tidak'])->default('tidak')->nullable();
            $table->string('harga_inovasi')->nullable();

            $table->string('nama_inovator')->nullable();
            $table->string('kontak_inovator')->nullable();
            $table->string('alamat_inovator')->nullable();
            $table->enum('daerah_inovator', [
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
           
            $table->enum('status_inovasi', ['digital', 'non_digital'])->default('non_digital')->nullable();

            $table->unsignedBigInteger('tipe_id')->nullable();
            $table->foreign('tipe_id')->references('id')->on('tipes')->onDelete('set null');

            $table->longText('spesifikasi_inovasi')->nullable();

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
        Schema::dropIfExists('inovasis');
    }
};
