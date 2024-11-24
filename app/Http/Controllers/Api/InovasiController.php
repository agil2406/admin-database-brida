<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inovasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InovasiController extends Controller
{
    public function daerahInovasi()
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
        // Ambil query awal untuk model Inovasi
        $query = Inovasi::with('file', 'kategori', 'instansi', 'tipe');

        // Filtering berdasarkan query params
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('nama_inovasi', 'like', '%' . $search . '%')
                    ->orWhere('nama_inovator', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_inovasi', $request->tahun);
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

        if ($request->filled('program')) {
            $query->whereHas('tipe', function ($q) use ($request) {
                $q->where('slug_tipe', $request->program);
            });
        }

        if ($request->filled('status')) {
            $query->where('status_inovasi', $request->status);
        }

        if ($request->filled('daerah')) {
            $query->where('daerah_inovator', $request->daerah);
        }

        if ($request->filled('sertifikasi')) {
            $query->where('sertifikat_inovasi', $request->sertifikasi);
        }

        if ($request->filled('desiminasi')) {
            $query->where('desiminasi_inovasi', $request->desiminasi);
        }

        $inovasis = $query->paginate(10);


        // Format data untuk response
        $formattedData = $inovasis->map(function ($inovasi) {
            return [
                'nama_inovasi' => $inovasi->nama_inovasi,
                'slug_inovasi' => $inovasi->slug_inovasi,
                'nama_kategori' => optional($inovasi->kategori)->nama_kategori,
                'tahun_inovasi' => $inovasi->tahun_inovasi,
                'fungsi_inovasi' => $inovasi->fungsi_inovasi,
                'nama_inovator' => $inovasi->nama_inovator,
                'daerah_inovator' => Str::title(str_replace('_', ' ', $inovasi->daerah_inovator)),
                'status_inovasi' => Str::title(str_replace('_', ' ', $inovasi->status_inovasi)),
                'nama_program' => optional($inovasi->tipe)->nama_tipe,
                'nama_instansi' => optional($inovasi->instansi)->nama_instansi,
            ];
        });

        // Return JSON response dengan metadata pagination
        return response()->json([
            'success' => true,
            'message' => 'Daftar Inovasi',
            'data' => $formattedData,
            'meta' => [
                'current_page' => $inovasis->currentPage(),
                'per_page' => $inovasis->perPage(),
                'total' => $inovasis->total(),
                'last_page' => $inovasis->lastPage(),
            ],
        ], 200);
    }

    public function detailInovasi($slug)
    {

        // Ambil data inovasi berdasarkan slug dengan relasi terkait
        $inovasi = Inovasi::with('file', 'kategori', 'instansi', 'tipe')
            ->where('slug_inovasi', $slug)
            ->first();

        // Jika data tidak ditemukan
        if (!$inovasi) {
            return response()->json([
                'success' => false,
                'message' => 'Inovasi tidak ditemukan.',
            ], 404);
        }

        // Format data untuk response
        $formattedData = [
            'nama_inovasi' => $inovasi->nama_inovasi,
            'slug_inovasi' => $inovasi->slug_inovasi,
            'nama_kategori' => optional($inovasi->kategori)->nama_kategori,
            'tahun_inovasi' => $inovasi->tahun_inovasi,
            'fungsi_inovasi' => $inovasi->fungsi_inovasi,
            'nama_inovator' => $inovasi->nama_inovator,
            'kontak_inovator' => $inovasi->kontak_inovator,
            'alamat_inovator' => $inovasi->alamat_inovator,
            'daerah_inovator' => Str::title(str_replace('_', ' ', $inovasi->daerah_inovator)),
            'status_inovasi' => Str::title(str_replace('_', ' ', $inovasi->status_inovasi)),
            'nama_program' => optional($inovasi->tipe)->nama_tipe,
            'nama_instansi' => optional($inovasi->instansi)->nama_instansi,
            'spesifikasi_inovasi' => $inovasi->spesifikasi_inovasi,
            'sertifikat_inovasi' => $inovasi->sertifikat_inovasi,
            'desiminasi_inovasi' => $inovasi->desiminasi_inovasi,
            'file' => $inovasi->file ? [
                'nama_file' => $inovasi->file->nama_file,
                'path_file' => asset('storage/' . $inovasi->file->path_file),
            ] : null,
        ];

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Detail Inovasi' . ' ' . $inovasi->nama_inovasi,
            'data' => $formattedData,
        ], 200);
    }
}
