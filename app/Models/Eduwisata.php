<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eduwisata extends Model
{
    use HasFactory;
    
    protected $table = 'eduwisatas';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug_lembaga';
    }
}
