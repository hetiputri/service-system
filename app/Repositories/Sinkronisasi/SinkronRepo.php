<?php

namespace App\Repositories\Sinkronisasi;

interface SinkronRepo
{
    /**
     * Get data by ID and jenis_data
     *
     * @return mixed
     */
    public function ambilData($id_data, $urldata, $tgl_update);

    public function UploadDetail($data);

    public function sinkrondata($id_data);
}
