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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Menetapkan user_id berdasarkan ID pengguna yang sedang login
            $model->user_id = Auth::id(); // Atau bisa menggunakan $model->user_id = Auth::user()->id;
        });


    }
}
