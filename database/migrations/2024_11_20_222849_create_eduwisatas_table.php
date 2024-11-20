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
        Schema::create('eduwisatas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lembaga')->nullable();
            $table->string('slug_lembaga')->unique();
            $table->date('jadwal_kunjungan')->nullable();
            $table->integer('jumlah_peserta')->nullable();

            $table->enum('daerah_lembaga', [
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
            $table->enum('asal_lembaga', [
            'paud',
            'tk',
            'sd', 
            'smp',
            'mts',
            'sma',
            'ma',
            'smk',
            'slb',
            'perguruan tinggi',
            'instansi',
            'lainnya',
            ])->nullable();
            


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
        Schema::dropIfExists('eduwisatas');
    }
};
