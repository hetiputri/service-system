<?php

namespace App\Services;

use App\Models\WorkflowLog;
use App\Models\WorkflowStep;

class WorkflowLayanan
{
    public function submit($pengajuan)
    {
        $firstStep = WorkflowStep::where('id_layanan', $pengajuan->layanan_id)
            ->where('urutan_step', 2)
            ->first();

        return $this->log($pengajuan, $firstStep->id_step, 'diajukan');
    }

    public function approve($pengajuan)
    {
        $currentStep = $pengajuan->lastLog->step;

        $nextStep = WorkflowStep::where('id_layanan', $pengajuan->layanan_id)
            ->where('urutan_step', '>', $currentStep->urutan_step)
            ->orderBy('urutan_step')
            ->first();

        return $this->log($pengajuan, $nextStep->id_step, 'approve');
    }

    public function revisi($pengajuan, $catatan)
    {
        $currentStep = $pengajuan->lastLog->step;

        return $this->log($pengajuan, $currentStep->id_step, 'revisi', $catatan);
    }

    public function tolak($pengajuan, $catatan)
    {
        $currentStep = $pengajuan->lastLog->step;

        return $this->log($pengajuan, $currentStep->id_step, 'ditolak', $catatan);
    }
    
    private function log($pengajuan, $stepId, $aksi, $catatan = null)
    {
        return WorkflowLog::create([
            'id_pengajuan'   => $pengajuan->id,
            'id_step'        => $stepId,
            'aksi'           => $aksi,
            'catatan'        => $catatan,
            'id_user_proses' => auth()->id(),
            'tanggal_proses' => now()
        ]);
    }

    
}