<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tipe;
use Illuminate\Http\Request;

class TipeController extends Controller
{
    public function index () 
    {
        $tipe = Tipe::all();

        $formattedData = $tipe->map(function ($tipe) {
            return [
                'nama_tipe' => $tipe->nama_tipe,
                'slug_tipe' => $tipe->slug_tipe,
                'deskripsi_tipe' => $tipe->deskripsi_tipe,
            ];
        });

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Daftar Program',
            'data' => $formattedData
        ], 200);
    }
}
