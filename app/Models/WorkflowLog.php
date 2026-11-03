<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowLog extends Model
{
    protected $table = 'workflow_log';

    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_pengajuan',
        'id_step',
        'urutan_step',
        'id_user_proses',
        'role',
        'aksi',
        'catatan',
        'tanggal_proses'
    ];

    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // public function step()
    // {
    //     return $this->belongsTo(WorkflowStep::class, 'id_step', 'id_step');
    // }

    // public function pengajuan()
    // {
    //     return $this->belongsTo(PengajuanLayanan::class, 'id_pengajuan');
    // }

    /*
    |--------------------------------------------------------------------------
    | 🔥 HELPER FUNCTION (INI YANG KAMU MAU)
    |--------------------------------------------------------------------------
    */

    public function step()
    {
        return $this->belongsTo(WorkflowStep::class, 'id_step', 'id_step');
    }

    public static function addLog($pengajuanId, $stepId, $urutanStep, $aksi, $role = null, $catatan = null)
    {
        $pegawai = session('pegawai');
        $id = $pegawai['id_pegawai'];
        return self::create([
            'id_pengajuan'     => $pengajuanId,
            'id_step'          => $stepId,
            'urutan_step'      => $urutanStep,
            'id_user_proses'   => $id,
            'role'              => $role,
            'aksi'             => $aksi,
            'catatan'          => $catatan,
            'tanggal_proses'   => now(),
        ]);
    }
}
