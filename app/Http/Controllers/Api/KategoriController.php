<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index () 
    {
        $kategoris = Kategori::all();

        $formattedData = $kategoris->map(function ($kategori) {
            return [
                'nama_kategori' => $kategori->nama_kategori,
                'slug_kategori' => $kategori->slug_kategori,
                'tipe_kategori' => $kategori->tipe_kategori,
            ];
        });

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Daftar Kategori',
            'data' => $formattedData
        ], 200);
    }

    public function tipeKategori ($tipe) 
    {
        // Validasi tipe hanya boleh "inovasi" atau "riset"
        if (!in_array($tipe, ['inovasi', 'riset'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe kategori tidak valid. Hanya diperbolehkan inovasi atau riset.',
            ], 400); // Kode status 400 untuk bad request
        }

        $kategoris = Kategori::where('tipe_kategori', $tipe)->get();


        $formattedData = $kategoris->map(function ($kategori) {
            return [
                'nama_kategori' => $kategori->nama_kategori,
                'slug_kategori' => $kategori->slug_kategori,
            ];
        });

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Daftar Kategori ' . $tipe,
            'data' => $formattedData
        ], 200);
    }
}
