<?php

namespace App\Http\Controllers\Sinkronisasi;

use App\Http\Controllers\Controller;
use App\Models\SinkronDataModel;
use App\Repositories\Sinkronisasi\SinkronRepo;
use Throwable;

class SinkronData extends Controller
{
    protected $sinkronRepo;
    public function __construct(SinkronRepo $sinkronRepo)
    {
        $this->sinkronRepo = $sinkronRepo;
    }

    public function index()
    {
        $datasinkron = SinkronDataModel::orderBy('id', 'asc')
            ->selectRaw("id,
                         nama,
                         DATE_FORMAT(updated_at, '%Y-%m-%d %H:%i:%s') as tanggal_update
                        ")
            ->get();
        return view('pages.sinkronisasi.sinkrondata', compact('datasinkron'));
    }

    public function sinkron($id)
    {
        try {
            return response()->json($this->sinkronRepo->sinkrondata($id));
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
