<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;
    protected $table = 'mst_unit_kerja';
    protected $guarded = [];
    protected $primaryKey = "key_id";
    protected $fillable = [ 
        'kd_unit_kerja',
        'nm_unit_kerja',
        'kd_wilayah',
        'kd_regional', 
        'flag_aktif',
        'seq_server',
        'timezone',
        'key',
    ];
}
