<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerjaAktir extends Model
{
    //trn_unit_kerja_aktif
    
    use HasFactory;
    protected $table = 'trn_unit_kerja_aktif';
    protected $guarded = [];
    protected $primaryKey = "key_id";
    protected $fillable = [ 
        'key_id_unit_kerja',
        'seq_server',
        'ip_server',
        'nm_server',
        'user_local',
        'password_local',
        'email_local',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
}
