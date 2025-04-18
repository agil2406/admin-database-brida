<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;
    
    protected $table = 'instansis';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug_instansi';
    }
}
