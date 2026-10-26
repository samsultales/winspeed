<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    //trn_lokasi_file
    
    use HasFactory;
    protected $table = 'trn_lokasi_file';
    protected $guarded = [];
    protected $primaryKey = "key_id";
    protected $fillable = [ 
        'lokasi_file',
        'flag_aktif',
        'created_at',
        'created_by'
    ];
}
