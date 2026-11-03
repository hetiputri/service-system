<?php
namespace App\Services;

use Carbon\Carbon;

class PensiunEligibilityService
{
    public function check($tanggalLahir, $tipeJabatan, $masaKerja)
    {
        $umur = Carbon::parse($tanggalLahir)->age;

        $bupAge = $this->getBupAge($tipeJabatan);

        $eligibleBup = false;
        $eligibleAps = false;
        $reasonBup = null;
        $reasonAps = null;

        // ===== BUP =====
        if ($bupAge) {
            if ($umur >= ($bupAge - 1)) {
                $eligibleBup = true;
            } else {
                $reasonBup = "Belum masuk 1 tahun sebelum BUP ($bupAge tahun)";
            }
        } else {
            $reasonBup = "Tipe jabatan tidak dikenali";
        }

        // ===== APS =====
        if ($umur >= 50 && $masaKerja >= 20) {
            $eligibleAps = true;
        } else {
            $reasonAps = "Minimal usia 50 tahun & masa kerja 20 tahun";
        }

        return [
            'umur' => $umur,
            'bup_age' => $bupAge,
            'eligible_bup' => $eligibleBup,
            'eligible_aps' => $eligibleAps,
            'reason_bup' => $reasonBup,
            'reason_aps' => $reasonAps,
        ];
    }

    private function getBupAge($tipeJabatan)
    {
        $tipeJabatan = strtolower($tipeJabatan);

        if (str_contains($tipeJabatan, 'utama')) {
            return 65;
        }

        if (
            str_contains($tipeJabatan, 'pratama') ||
            str_contains($tipeJabatan, 'madya') ||
            str_contains($tipeJabatan, 'guru')
        ) {
            return 60;
        }

        return 58;
    }
}