<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interval extends Model
{
    // mst_interval_sound

    use HasFactory;
    protected $table = 'mst_interval_sound';
    protected $guarded = [];
    protected $primaryKey = "key_id";
    protected $fillable = [ 
        'interval',
        'updated_at'
    ];
}
