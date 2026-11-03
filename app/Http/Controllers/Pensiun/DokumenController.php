<?php

namespace App\Http\Controllers\Pensiun;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\DokumenPengajuan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\SimpegService;
use App\Services\Layanan\SuratDisiplinService;
use App\Services\Layanan\SuratPidanaService;
use App\Services\Layanan\DokumenService;

class DokumenController extends Controller
{
    public function generate(Request $request)
    {
        return app(DokumenService::class)
            ->generate($request->jenis, $request->user_id, $request);
    }

    public function store(Request $request)
    {
        app(DokumenService::class)->store($request->jenis, $request);

        return response()->json(['success' => true]);
    }

    public function show(Request $request)
    {
        $path = $request->query('path');

        abort_unless($path, 404);

        abort_unless(Storage::disk('private')->exists($path), 404);

        // Authorization via policy
        $this->authorize('viewFile', $path);

        return Storage::disk('private')->response($path);
    }

    public function preview($id)
    {
        $dokumen = DokumenPengajuan::findOrFail($id);

        $path = $dokumen->file_path;

        if (!Storage::exists($path)) {
            abort(404);
        }

        return Storage::response($path);
    }
}
