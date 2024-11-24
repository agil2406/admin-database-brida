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
}
