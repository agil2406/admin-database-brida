<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Riset extends Model
{
    use HasFactory;
    
    protected $table = 'risets';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug_riset';
    }

    public function files()
    {
        return $this->hasMany(File::class, 'entitasId');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class,'kategori_id');
    }
    public function instansi()
    {
        return $this->belongsTo(Instansi::class,'instansi_id');
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
