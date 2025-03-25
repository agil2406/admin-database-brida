<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Books extends Model
{
    use HasFactory;
    
    protected $table = 'books';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug_buku';
    }

}
