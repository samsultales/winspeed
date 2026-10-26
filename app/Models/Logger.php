<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    //
    use HasFactory;
    protected $table = 'trn_logger';
    protected $guarded = [];
    protected $primaryKey = "key_id";
}
