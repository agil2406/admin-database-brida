<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Books;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Books::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('penulis', 'like', '%' . $search . '%')
                    ->orWhere('penerbit', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->where('tanggal_terbit', $request->tahun);
        }


        $data = $query->paginate(10);

        // Format data untuk response
        $formattedData = $data->map(function ($buku) {
            return [
                'judul' => $buku->judul,
                'slug_buku' => $buku->slug_buku,
                'penulis' => $buku->penulis,
                'penerbit' => $buku->penerbit,
                'tanggal_terbit' => $buku->tanggal_terbit,
            ];
        });

        // Return JSON response dengan metadata pagination
        return response()->json([
            'success' => true,
            'message' => 'Daftar Buku',
            'data' => $formattedData,
            'meta' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
            ],
        ], 200);
    }

    public function detailBuku($slug)
    {

        // Ambil data buku berdasarkan slug dengan relasi terkait
        $buku = Books::where('slug_buku', $slug)->first();

        // Jika data tidak ditemukan
        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        // Format data untuk response
        $formattedData = [
            'judul' => $buku->judul,
            'slug_buku' => $buku->slug_buku,
            'penulis' => $buku->penulis,
            'penerbit' => $buku->penerbit,
            'isbn' => $buku->isbn,
            'tanggal_terbit' => $buku->tanggal_terbit,
            'jumlah_halaman' => $buku->jumlah_halaman,
            'sinopsis' => $buku->sinopsis,
            'negara' => $buku->negara,
            'link_buku' => $buku->link_buku,
            'cover' => $buku->cover ? asset('storage/' . $buku->cover) : null,
        ];

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Detail Buku' . ' ' . $buku->judul,
            'data' => $formattedData,
        ], 200);
    }
}
