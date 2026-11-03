<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengajuanLayanan extends Model
{
    protected $table = 'pengajuan_layanan';

    protected $fillable = [
        'layanan_id',
        'id_jenis_layanan',
        'pegawai_id',
        'id_pangkat',
        'id_jabatan',
        'id_skpd',
        'tanggal_pengajuan',
        'data_pribadi',
        'riwayat_keluarga',
        'data_cpns',
        'data_pns',
        'status_akhir',
        'current_step',
        'form_step',
        'nomor_tiket',
        'sk_path'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'data_pribadi' => 'array',
        'riwayat_keluarga' => 'array',
        'data_cpns' => 'array',
        'data_pns' => 'array',
        'riwayat_pangkat' => 'array',
        'riwayat_PMK' => 'array',
    ];

    // ================= RELATION =================

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    // public function logs()
    // {
    //     // return $this->belongsTo(WorkflowLog::class, 'id_pengajuan');
    //     return $this->hasMany(WorkflowLog::class, 'id_pengajuan');
    // }

    public function workflowLogs()
    {
        return $this->hasMany(WorkflowLog::class, 'id_pengajuan', 'id')
            ->leftJoin('workflow_step', 'workflow_step.id_step', '=', 'workflow_log.id_step')
            ->select('workflow_log.*', 'workflow_step.nama_step')
            ->orderByDesc('id_log');
    }

    public function lastLog()
    {
        return $this->hasOne(WorkflowLog::class, 'id_pengajuan')->latestOfMany('id_log');
    }

    public function lastCatatan()
    {
        return $this->hasOne(WorkflowLog::class, 'id_pengajuan')
            ->whereNotNull('catatan')
            ->latest('id_log');
    }

    public function workflowSteps()
    {
        return $this->hasMany(WorkflowStep::class, 'id_layanan', 'id_jenis_layanan')
            ->orderBy('urutan_step');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenPengajuan::class, 'pengajuan_layanan_id');
    }

    // ================= AUTO DELETE FOLDER =================

    protected static function booted()
    {
        static::deleting(function ($pengajuan) {
            Storage::disk('private')
                ->deleteDirectory("layanan/{$pengajuan->id}");
        });
    }

    public function scopePensiun($query)
    {
        return $query->whereHas('layanan', function ($q) {
            $q->where('id_jenis_layanan', 1); // 1 = Pensiun
        });
    }

    public function scopeCuti($query)
    {
        return $query->whereHas('layanan', function ($q) {
            $q->where('id_jenis_layanan', 2); // 2 = Cuti
        });
    }

    public function scopeAktif($query)
    {
        return $query->whereNotIn('status_akhir', [
            'selesai',
            'ditolak'
        ]);
    }

    // ================= GENERATE NOMOR TIKET =================

    public static function generateNomorTiket($layananId)
    {
        return DB::transaction(function () use ($layananId) {

            $layanan = Layanan::with('jenis')->findOrFail($layananId);

            $prefix = trim($layanan->jenis->prefix);
            $monthYear = now()->format('my');

            $last = DB::table('pengajuan_layanan')
                ->where('nomor_tiket', 'like', $prefix . '-' . $monthYear . '-%')
                ->lockForUpdate()
                ->orderBy('nomor_tiket', 'desc')
                ->first();

            if ($last) {
                $parts = explode('-', $last->nomor_tiket);
                $lastNumber = (int) end($parts);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            return $prefix . '-' . $monthYear . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    public function generateTicket()
    {
        if (!$this->nomor_tiket) {

            $this->nomor_tiket = self::generateNomorTiket($this->layanan_id);

            $this->save();
        }

        return $this->nomor_tiket;
    }


    public function updateStep($step)
    {
        $this->update([
            'form_step' => $step
        ]);
    }
}
