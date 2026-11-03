<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Filter\FilterRepository;

class Referensi extends Controller
{

    private $filterRepository;

    public function __construct(FilterRepository $filterRepository)
    {
        $this->filterRepository = $filterRepository;
    }

    public function ReferensiData($jenis = null)
    {

        $model = $this->filterRepository->getRef($jenis);
        return response()->json($model);
    }
}
