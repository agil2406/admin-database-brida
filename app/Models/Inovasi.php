<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Inovasi extends Model
{
    use HasFactory;
    
    protected $table = 'inovasis';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug_inovasi';
    }

    public function file()
    {
        return $this->hasOne(File::class, 'entitasId');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class,'kategori_id');
    }
    public function instansi()
    {
        return $this->belongsTo(Instansi::class,'instansi_id');
    }
    public function tipe()
    {
        return $this->belongsTo(Tipe::class,'tipe_id');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            // Menetapkan user_id berdasarkan ID pengguna yang sedang login
            $model->user_id = Auth::id(); // Atau bisa menggunakan $model->user_id = Auth::user()->id;
        });

        // Updating event: Delete old file before updating with new file
        static::updating(function ($model) {
            if ($model->isDirty('file')) { // Check if the 'file' attribute has been updated
                $oldFile = $model->getOriginal('file'); // Get the original file before update
                if ($oldFile) {
                    // Delete the old file from storage
                    Storage::disk('public')->delete($oldFile->path_file);
                    $oldFile->delete(); // Delete the old file record from the database
                }
            }
        });

    }
}
