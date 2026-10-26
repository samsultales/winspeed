<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];  
    
    use HasFactory;

    protected $fillable = [
        'original_name',
        'generated_name'
    ];
}
