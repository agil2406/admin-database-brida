<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Eduwisata extends Model
{
    use HasFactory;
    
    protected $table = 'eduwisatas';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug_lembaga';
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
