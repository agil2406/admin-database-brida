<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inovasi extends Model
{
    use HasFactory;
    
    protected $table = 'inovasis';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug_inovasi';
    }
}
