<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrasiModel extends Model
{
    protected $connection = 'mysql_third';
    protected $table = 'web_params';
    protected $primaryKey = 'id_tabel';
    protected $keyType = 'integer';

    protected $fillable = [
        'id',
        'value',
        'keterangan',
    ];

    public $timestamps = true;
}
