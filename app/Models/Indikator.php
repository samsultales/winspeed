<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;
    protected $table = 'mst_indikator_rusak';
    protected $guarded = [];
    protected $primaryKey = "key_id";
    protected $fillable = [ 
        'bts_rusak',
        'satuan'
    ];
}
