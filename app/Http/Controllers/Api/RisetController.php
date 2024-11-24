<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Riset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RisetController extends Controller
{
    public function daerahRiset()
    {
        $daerah = [
            'kota_mataram' => 'Kota Mataram',
            'kab_lombok_barat' => 'Kabupaten Lombok Barat',
            'kab_lombok_timur' => 'Kabupaten Lombok Timur',
            'kab_lombok_utara' => 'Kabupaten Lombok Utara',
            'kab_lombok_tengah' => 'Kabupaten Lombok Tengah',
            'kab_sumbawa' => 'Kabupaten Sumbawa',
            'kab_sumbawa_barat' => 'Kabupaten Sumbawa Barat',
            'kab_bima' => 'Kabupaten Bima',
            'kota_bima' => 'Kota Bima',
            'kab_dompu' => 'Kabupaten Dompu',
        ];

        // Menyesuaikan format data menjadi array dengan value dan label
        $formattedDaerah = array_map(function ($label, $value) {
            return ['value' => $value, 'label' => $label];
        }, $daerah, array_keys($daerah));

        // Return response dengan array daerah inovator yang sudah diformat
        return response()->json([
            'success' => true,
            'data' => $formattedDaerah
        ], 200);
    }

    public function index(Request $request)
    {
        // Ambil query awal untuk model riset
        $query = Riset::with('files', 'kategori', 'instansi');
        // Filtering berdasarkan query params
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('judul_riset', 'like', '%' . $search . '%')
                    ->orWhere('nama_peneliti', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_riset', $request->tahun);
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug_kategori', $request->kategori);
            });
        }

        if ($request->filled('instansi')) {
            $query->whereHas('instansi', function ($q) use ($request) {
                $q->where('slug_instansi', $request->instansi);
            });
        }

        if ($request->filled('daerah')) {
            $query->where('daerah_peneliti', $request->daerah);
        }

        if ($request->filled('desiminasi')) {
            $query->where('desiminasi_riset', $request->desiminasi);
        }

        $risets = $query->paginate(10);


        // Format data untuk response
        $formattedData = $risets->map(function ($riset) {
            return [
                'judul_riset' => $riset->judul_riset,
                'slug_riset' => $riset->slug_riset,
                'nama_kategori' => optional($riset->kategori)->nama_kategori,
                'tahun_riset' => $riset->tahun_riset,
                'nama_peneliti' => $riset->nama_peneliti,
                'daerah_peneliti' => Str::title(str_replace('_', ' ', $riset->daerah_peneliti)),
                'nama_instansi' => optional($riset->instansi)->nama_instansi,
            ];
        });

        // Return JSON response dengan metadata pagination
        return response()->json([
            'success' => true,
            'message' => 'Daftar Riset',
            'data' => $formattedData,
            'meta' => [
                'current_page' => $risets->currentPage(),
                'per_page' => $risets->perPage(),
                'total' => $risets->total(),
                'last_page' => $risets->lastPage(),
            ],
        ], 200);
    }

    public function detailRiset($slug)
    {

        // Ambil data riset berdasarkan slug dengan relasi terkait
        $riset = Riset::with('files', 'kategori', 'instansi')
            ->where('slug_riset', $slug)
            ->first();

        // Jika data tidak ditemukan
        if (!$riset) {
            return response()->json([
                'success' => false,
                'message' => 'riset tidak ditemukan.',
            ], 404);
        }

        // Format data untuk response
        $formattedData = [
            'judul_riset' => $riset->judul_riset,
            'slug_riset' => $riset->slug_riset,
            'nama_kategori' => optional($riset->kategori)->nama_kategori,
            'tahun_riset' => $riset->tahun_riset,
            'deskripsi_riset' => $riset->deskripsi_riset,
            'nama_peneliti' => $riset->nama_peneliti,
            'kontak_peneliti' => $riset->kontak_peneliti,
            'alamat_peneliti' => $riset->alamat_peneliti,
            'daerah_peneliti' => Str::title(str_replace('_', ' ', $riset->daerah_peneliti)),
            'nama_instansi' => optional($riset->instansi)->nama_instansi,
            'desiminasi_riset' => $riset->desiminasi_riset,
            'file' => $riset->files->map(function ($file) {
                return [
                    'nama_file' => $file->nama_file,
                    'path_file' => asset('storage/' . $file->path_file), // Menambahkan storage di awal path
                ];
            }),
        ];

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Detail riset' . ' ' . $riset->nama_riset,
            'data' => $formattedData,
        ], 200);
    }
}
