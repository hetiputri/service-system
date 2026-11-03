<?php

namespace App\Console\Commands;

use App\Models\ReportSinkronisasi;
use App\Models\SinkronDataModel;
use App\Repositories\Sinkronisasi\SinkronRepo;
use Illuminate\Console\Command;
use Throwable;

class SinkronisasiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sinkronisasi-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi seluruh data SIMPEG';

    /**
     * Execute the console command.
     */
    public function handle(SinkronRepo $sinkronRepo)
    {
        try {
            $alldata = SinkronDataModel::get();
        } catch (Throwable $exception) {
            report($exception);
            $this->table(
                ['ID', 'Data', 'Status', 'Keterangan'],
                [['-', 'Memuat data sinkronisasi', 'GAGAL', $exception->getMessage()]]
            );

            return self::FAILURE;
        }

        $rows = [];
        $failures = 0;

        foreach ($alldata as $sinkdata) {
            if ($sinkdata->id >= 1) {
                try {
                    $result = $sinkronRepo->sinkrondata($sinkdata->id);

                    if (!is_array($result) || !array_key_exists('success', $result)) {
                        $rows[] = [$sinkdata->id, $sinkdata->nama, 'GAGAL', 'Respons sinkronisasi tidak valid.'];
                        $failures++;
                        continue;
                    }

                    $success = $result['success'] === true;
                    $message = $result['message'] ?? ($success ? 'Berhasil.' : 'Sinkronisasi gagal.');
                    $state = $success ? 'BERHASIL' : 'GAGAL';
                    $rows[] = [
                        $sinkdata->id,
                        $sinkdata->nama,
                        $state,
                        is_scalar($message) ? (string) $message : 'Respons tidak menyertakan keterangan yang valid.',
                    ];

                    ReportSinkronisasi::InsertData(now()->format('Y-m-d'), $sinkdata->id, $sinkdata->nama, $state, $message);

                    if (!$success) {
                        $failures++;
                    }
                } catch (Throwable $exception) {
                    report($exception);
                    $rows[] = [$sinkdata->id, $sinkdata->nama, 'GAGAL', $exception->getMessage()];
                    $failures++;

                    ReportSinkronisasi::InsertData(now()->format('Y-m-d'), $sinkdata->id, $sinkdata->nama, 'GAGAL', $exception->getMessage());
                }
            }
        }

        //$this->table(['ID', 'Data', 'Status', 'Keterangan'], $rows);

        //return $failures > 0 ? self::FAILURE : self::SUCCESS;
    }
}
