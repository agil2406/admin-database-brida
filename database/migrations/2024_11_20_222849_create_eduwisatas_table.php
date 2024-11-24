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
                'kota_mataram',
                'kab_lombok barat',
                'kab_lombok timur',
                'kab_lombok utara',
                'kab_lombok tengah',
                'kab_sumbawa',
                'kab_sumbawa Barat',
                'kab_bima',
                'kota_bima',
                'kab_dompu',
                'aceh',
                'bali',
                'banten',
                'bengkulu' ,
                'yogyakarta',
                'jakarta',
                'gorontalo',
                'jambi',
                'jawa_barat',
                'jawa_tengah' ,
                'jawa_timur' ,
                'kalimantan_barat',
                'kalimantan_selatan',
                'kalimantan_tengah',
                'kalimantan_timur' ,
                'kalimantan_utara' ,
                'bangka_belitung' ,
                'riau' ,
                'lampung' ,
                'maluku',
                'maluku_utara',
                'ntt',
                'papua' ,
                'papua_barat' ,
                'papua_barat_daya' ,
                'papua_pegunungan' ,
                'papua_selatan' ,
                'papua_tengah' ,
                'riau' ,
                'sulawesi_barat',
                'sulawesi_selatan',
                'sulawesi_tengah' ,
                'sulawesi_tenggara' ,
                'sulawesi_utara' ,
                'sumatera_barat',
                'sumatera_selatan' ,
                'sumatera_utara',
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
                'perguruan_tinggi',
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
