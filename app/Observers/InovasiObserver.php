<?php

namespace App\Observers;

use App\Models\Inovasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InovasiObserver
{
    /**
     * Handle the Inovasi "created" event.
     */
    public function created(Inovasi $inovasi): void
    {
        // Anda dapat menambahkan logika jika perlu saat inovasi baru dibuat
    }

    /**
     * Handle the Inovasi "updated" event.
     */
    public function updated(Inovasi $inovasi): void
    {
            // // Ambil relasi file dari model Inovasi
            // $file = $inovasi->file;

            // if ($file) {
            //     $oldFilePath = $file->getOriginal('path_file'); // Path file lama
            //     $newFilePath = $file->path_file; // Path file baru

            //     // Periksa apakah file lama berbeda dengan file baru
            //     if ($oldFilePath !== $newFilePath) {
            //         if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
            //             // Hapus file lama jika ada
            //             Storage::disk('public')->delete($oldFilePath);
            //             Log::info("File lama berhasil dihapus: {$oldFilePath}");
            //         } else {
            //             Log::warning("File lama tidak ditemukan: {$oldFilePath}");
            //         }
            //     }
            // }
    }

    /**
     * Handle the Inovasi "deleted" event.
     */
    public function deleted(Inovasi $inovasi): void
    {
        // // Menghapus file yang terkait dengan inovasi yang dihapus
        // $file = $inovasi->file;  // Mendapatkan file terkait dengan inovasi
        //  // Dump dan die untuk memeriksa apakah relasi file ter-load dengan benar
        // //  dd($file,Storage::disk('public'));

        // if ($file) {
        //     // Pastikan file path ada dan file masih ada di storage
        //     $filePath = $file->path_file;
        //     Log::info("Mengecek file dengan path: " . $file->path_file);
        //     if ($filePath && Storage::disk('public')->exists($filePath)) {
        //         Log::info("File ditemukan, menghapus file: " . $filePath);
        //         Storage::disk('public')->delete($filePath);
        //         Log::info("File terkait inovasi berhasil dihapus: " . $filePath);
        //     } else {
        //         Log::warning("File terkait inovasi tidak ditemukan di storage: " . $filePath);
        //     }
            
        // }
    }

    /**
     * Handle the Inovasi "restored" event.
     */
    public function restored(Inovasi $inovasi): void
    {
        // Anda bisa menambahkan logika pemulihan file di sini jika diperlukan
    }

    /**
     * Handle the Inovasi "force deleted" event.
     */
    public function forceDeleted(Inovasi $inovasi): void
    {
        // Menghapus file terkait inovasi saat dihapus secara permanen
        $file = $inovasi->file;  // Mendapatkan file terkait dengan inovasi
        if ($file) {
            // Pastikan file path ada dan file masih ada di storage
            $filePath = $file->path_file;
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                // Menghapus file jika ditemukan di storage
                Storage::disk('public')->delete($filePath);
                Log::info("File terkait inovasi berhasil dihapus secara permanen: " . $filePath);
            } else {
                Log::warning("File terkait inovasi tidak ditemukan di storage untuk dihapus secara permanen: " . $filePath);
            }
        }
    }
}
