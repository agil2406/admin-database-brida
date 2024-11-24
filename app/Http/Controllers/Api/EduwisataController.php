<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EduwisataController extends Controller
{
    public function index()
    {
        // Daftar wilayah NTB
        $ntbDaerahs = [
            'kota_mataram', 'kab_lombok_barat', 'kab_lombok_timur', 'kab_lombok_utara', 
            'kab_lombok_tengah', 'kab_sumbawa', 'kab_sumbawa_barat', 'kab_bima', 
            'kota_bima', 'kab_dompu'
        ];

        // Menghitung total peserta di NTB
        $totalPesertaNTB = DB::table('eduwisatas')
            ->whereIn('daerah_lembaga', $ntbDaerahs)  // Filter untuk NTB
            ->sum('jumlah_peserta');

        // Menghitung total peserta di luar NTB
        $totalPesertaLuarNTB = DB::table('eduwisatas')
            ->whereNotIn('daerah_lembaga', $ntbDaerahs)  // Filter untuk luar NTB
            ->sum('jumlah_peserta');

        // Menghitung total lembaga di NTB
        $totalLembagaNTB = DB::table('eduwisatas')
            ->whereIn('daerah_lembaga', $ntbDaerahs)  // Filter untuk NTB
            ->count('id');

        // Menghitung total lembaga di luar NTB
        $totalLembagaLuarNTB = DB::table('eduwisatas')
            ->whereNotIn('daerah_lembaga', $ntbDaerahs)  // Filter untuk luar NTB
            ->count('id');

        // Format respons
        $response = [
            'total_peserta_ntb' => $totalPesertaNTB,
            'total_peserta_luar_ntb' => $totalPesertaLuarNTB,
            'total_lembaga_ntb' => $totalLembagaNTB,
            'total_lembaga_luar_ntb' => $totalLembagaLuarNTB,
        ];

        // Kembalikan data detail dalam JSON
        return response()->json([
            'success' => true,
            'message' => 'Data Eduwisata berdasarkan Daerah NTB dan Luar NTB',
            'data' => $response
        ], 200);
    }

    public function asalLembaga ()
    {
        // Daftar kategori
        $categories = [
            'paud' => 'PAUD (Pendidikan Anak Usia Dini)',
            'tk' => 'TK (Taman Kanak-Kanak)',
            'sd' => 'SD (Sekolah Dasar)',
            'smp' => 'SMP (Sekolah Menengah Pertama)',
            'mts' => 'MTS (Madrasah Tsanawiyah)',
            'sma' => 'SMA (Sekolah Menengah Atas)',
            'ma' => 'MA (Madrasah Aliyah)',
            'smk' => 'SMK (Sekolah Menengah Kejuruan)',
            'slb' => 'SLB (Sekolah Luar Biasa)',
            'perguruan_tinggi' => 'Perguruan Tinggi (Universitas/Politeknik)',
            'instansi' => 'Instansi (Lembaga Pemerintahan atau Swasta)',
            'lainnya' => 'Lainnya',
        ];

        // Menghitung jumlah berdasarkan kategori di tabel `eduwisatas`
        $counts = DB::table('eduwisatas')
            ->select('asal_lembaga', DB::raw('SUM(jumlah_peserta) as total'))
            ->groupBy('asal_lembaga')
            ->pluck('total', 'asal_lembaga');

        // Format respons
        $response = [];
        foreach ($categories as $key => $label) {
            $response[] = [
                'kategori' => $key,
                'label' => $label,
                'total' => $counts[$key] ?? 0,
            ];
        }

         // Kembalikan data detail dalam JSON
         return response()->json([
            'success' => true,
            'message' => 'Data Eduwisata Sesuai Asal Lembaga',
            'data' => $response
        ], 200);
    }

    public function daerahLembaga()
    {
        // Daerah lembaga di NTB
        $daerahsNTB = [
            'kota_mataram' => 'Kota Mataram',
            'kab_lombok_barat' => 'Kab. Lombok Barat',
            'kab_lombok_timur' => 'Kab. Lombok Timur',
            'kab_lombok_utara' => 'Kab. Lombok Utara',
            'kab_lombok_tengah' => 'Kab. Lombok Tengah',
            'kab_sumbawa' => 'Kab. Sumbawa',
            'kab_sumbawa_barat' => 'Kab. Sumbawa Barat',
            'kab_bima' => 'Kab. Bima',
            'kota_bima' => 'Kota Bima',
            'kab_dompu' => 'Kab. Dompu',
        ];
    
        // Daerah di luar NTB
        $selainNTB = [
            'aceh' => 'Aceh',
            'bali' => 'Bali',
            'banten' => 'Banten',
            'bengkulu' => 'Bengkulu',
            'yogyakarta' => 'Daerah Istimewa Yogyakarta',
            'jakarta' => 'Daerah Khusus Ibukota Jakarta',
            'gorontalo' => 'Gorontalo',
            'jambi' => 'Jambi',
            'jawa_barat' => 'Jawa Barat',
            'jawa_tengah' => 'Jawa Tengah',
            'jawa_timur' => 'Jawa Timur',
            'kalimantan_barat' => 'Kalimantan Barat',
            'kalimantan_selatan' => 'Kalimantan Selatan',
            'kalimantan_tengah' => 'Kalimantan Tengah',
            'kalimantan_timur' => 'Kalimantan Timur',
            'kalimantan_utara' => 'Kalimantan Utara',
            'bangka_belitung' => 'Kepulauan Bangka Belitung',
            'riau' => 'Kepulauan Riau',
            'lampung' => 'Lampung',
            'maluku' => 'Maluku',
            'maluku_utara' => 'Maluku Utara',
            'ntt' => 'Nusa Tenggara Timur',
            'papua' => 'Papua',
            'papua_barat' => 'Papua Barat',
            'papua_barat_daya' => 'Papua Barat Daya',
            'papua_pegunungan' => 'Papua Pegunungan',
            'papua_selatan' => 'Papua Selatan',
            'papua_tengah' => 'Papua Tengah',
            'riau' => 'Riau',
            'sulawesi_barat' => 'Sulawesi Barat',
            'sulawesi_selatan' => 'Sulawesi Selatan',
            'sulawesi_tengah' => 'Sulawesi Tengah',
            'sulawesi_tenggara' => 'Sulawesi Tenggara',
            'sulawesi_utara' => 'Sulawesi Utara',
            'sumatera_barat' => 'Sumatera Barat',
            'sumatera_selatan' => 'Sumatera Selatan',
            'sumatera_utara' => 'Sumatera Utara',
        ];
    
        // Ambil total jumlah peserta berdasarkan daerah lembaga
        $countsNTB = DB::table('eduwisatas')
            ->select('daerah_lembaga', DB::raw('SUM(jumlah_peserta) as total'))
            ->whereIn('daerah_lembaga', array_keys($daerahsNTB)) // Filter untuk NTB
            ->groupBy('daerah_lembaga')
            ->pluck('total', 'daerah_lembaga');
    
        $countsSelainNTB = DB::table('eduwisatas')
            ->select('daerah_lembaga', DB::raw('SUM(jumlah_peserta) as total'))
            ->whereIn('daerah_lembaga', array_keys($selainNTB)) // Filter untuk selain NTB
            ->groupBy('daerah_lembaga')
            ->pluck('total', 'daerah_lembaga');
    
        // Format respons untuk NTB
        $responseNTB = [];
        foreach ($daerahsNTB as $key => $label) {
            $responseNTB[] = [
                'kategori' => $key,
                'label' => $label,
                'total' => $countsNTB[$key] ?? 0, // Jika tidak ada data, tampilkan 0
            ];
        }
    
        // Format respons untuk selain NTB
        $responseSelainNTB = [];
        foreach ($selainNTB as $key => $label) {
            $responseSelainNTB[] = [
                'kategori' => $key,
                'label' => $label,
                'total' => $countsSelainNTB[$key] ?? 0, // Jika tidak ada data, tampilkan 0
            ];
        }
    
        // Kembalikan data dalam format JSON, dengan dua response terpisah
        return response()->json([
            'success' => true,
            'message' => 'Data Eduwisata Sesuai Daerah Lembaga',
            'data' => [
                'ntb' => $responseNTB,
                'selain_ntb' => $responseSelainNTB,
            ],
        ], 200);
    }
    
    
    public function detailDaerahLembaga ($label)
    {

        // Ambil data berdasarkan daerah tertentu
        $details = DB::table('eduwisatas')
            ->where('daerah_lembaga', $label) // Filter berdasarkan label daerah
            ->get(['nama_lembaga', 'asal_lembaga', 'jumlah_peserta','jadwal_kunjungan']); // Pilih kolom yang relevan

        // Format tanggal jadwal kunjungan
        $formattedDetails = $details->map(function ($item) {
            return [
                'nama_lembaga' => $item->nama_lembaga,
                'asal_lembaga' => $item->asal_lembaga,
                'jumlah_peserta' => $item->jumlah_peserta,
                'jadwal_kunjungan' => Carbon::parse($item->jadwal_kunjungan)->translatedFormat('j F Y'),
            ];
        });

        // Hitung total peserta untuk daerah tersebut
        $total = DB::table('eduwisatas')
            ->where('daerah_lembaga', $label)
            ->sum('jumlah_peserta');

        // Format respons
        $response = [
            'daerah' => $label,
            'total_peserta' => $total,
            'details' => $formattedDetails,
        ];

         // Kembalikan data detail dalam JSON
         return response()->json([
            'success' => true,
            'message' => 'Data Eduwisata Detail Daerah Lembaga',
            'data' => $response
        ], 200);
    }

    public function detailAsalLembaga ($label)
    {

       // Ambil data berdasarkan asal tertentu
       $details = DB::table('eduwisatas')
       ->where('asal_lembaga', $label) // Filter berdasarkan label asal
       ->get(['nama_lembaga', 'daerah_lembaga', 'jumlah_peserta','jadwal_kunjungan']); // Pilih kolom yang relevan

        // Format tanggal jadwal kunjungan
        $formattedDetails = $details->map(function ($item) {
            return [
                'nama_lembaga' => $item->nama_lembaga,
                'daerah_lembaga' => $item->daerah_lembaga,
                'jumlah_peserta' => $item->jumlah_peserta,
                'jadwal_kunjungan' => Carbon::parse($item->jadwal_kunjungan)->translatedFormat('j F Y'),
            ];
        });

        // Hitung total peserta untuk asal tersebut
        $total = DB::table('eduwisatas')
            ->where('asal_lembaga', $label)
            ->sum('jumlah_peserta');

         // Format respons
         $response = [
            'asal_lembaga' => $label,
            'total_peserta' => $total,
            'details' => $formattedDetails,
        ];

         // Kembalikan data detail dalam JSON
         return response()->json([
            'success' => true,
            'message' => 'Data Eduwisata Detail Asal Lembaga',
            'data' => $response
        ], 200);
    }
}
