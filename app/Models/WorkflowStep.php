<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    protected $table = 'workflow_step';

    protected $primaryKey = 'id_step';

    protected $fillable = [
        'id_layanan',
        'nama_step',
        'urutan_step',
        'role_pemroses',
        'sla_step_hari'
    ];

    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function logs()
    {
        return $this->hasMany(WorkflowLog::class, 'id_step', 'id_step');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }
}