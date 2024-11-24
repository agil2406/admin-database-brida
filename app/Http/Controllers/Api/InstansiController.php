<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function index () 
    {
        $instansis = Instansi::all();

        $formattedData = $instansis->map(function ($instansi) {
            return [
                'nama_instansi' => $instansi->nama_instansi,
                'slug_instansi' => $instansi->slug_instansi,
                'alamat_instansi' => $instansi->alamat_instansi,
            ];
        });

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Daftar Instansi',
            'data' => $formattedData
        ], 200);
    }

    public function detail ($slug)
    {
        // Cari instansi berdasarkan slug
        $instansi = Instansi::where('slug_instansi', $slug)->first();

        // Jika tidak ditemukan, kembalikan respons error
        if (!$instansi) {
            return response()->json([
                'success' => false,
                'message' => 'Instansi tidak ditemukan',
            ], 404);
        }

        // Format data detail
        $formattedData = [
            'nama_instansi' => $instansi->nama_instansi,
            'slug_instansi' => $instansi->slug_instansi,
            'alamat_instansi' => $instansi->alamat_instansi,
        ];

        // Kembalikan data detail dalam JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Instansi',
            'data' => $formattedData
        ], 200);
    }
}
