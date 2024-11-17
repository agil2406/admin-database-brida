<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;
    
    protected $table = 'files';
    protected $guarded = [];

    protected static function booted()
    {
        static::updating(function ($file) {
            $oldFilePath = $file->getOriginal('path_file'); // Path file lama
            $newFilePath = $file->path_file; // Path file baru

            if ($oldFilePath !== $newFilePath && $oldFilePath) {
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            }
        });
        
    }

    public function inovasi()
    {
        return $this->belongsTo(Inovasi::class,'entitasId');
    }

    public function risets()
    {
        return $this->belongsTo(Riset::class,'entitasId');
    }


}
