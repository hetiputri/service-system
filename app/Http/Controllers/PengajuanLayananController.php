<?php
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanLayanan;
use App\Models\DokumenPengajuan;
use Illuminate\Support\Facades\Session;

class PengajuanLayananController extends Controller
{
    public function storeStep1(Request $request)
    {
        $pegawaiId = auth()->user()->pegawai_id;

        // Cek apakah sudah ada pengajuan pensiun aktif
        $existing = PengajuanLayanan::pensiun()
            ->aktif()
            ->where('pegawai_id', $pegawaiId)
            ->first();

        if ($existing) {
            return redirect()
                ->route('pengajuan.show', $existing->id)
                ->with('warning', 'Anda masih memiliki pengajuan pensiun yang aktif.');
        }

        // Kalau tidak ada → buat draft baru
        $pengajuan = PengajuanLayanan::create([
            'layanan_id' => $request->layanan_id,
            'pegawai_id' => $pegawaiId,
            'tanggal_pengajuan' => now(),
            'status_akhir' => 'draft',
            'current_step' => 1
        ]);

        return redirect()->route('pengajuan.show', $pengajuan->id);
    }

    public function show($id)
    {
        $pengajuan = PengajuanLayanan::with([
            'layanan.jenis',
            'dokumen.persyaratan'
        ])->findOrFail($id);

        // 🔐 Pastikan hanya pemilik yang bisa akses
        abort_if(
            $pengajuan->pegawai_id !== auth()->user()->pegawai_id,
            403
        );

        // Siapkan existing file untuk Alpine
        $existing = $pengajuan->dokumen
            ->mapWithKeys(function ($item) {
                return [
                    $item->persyaratan_layanan_id => route(
                        'file.show',
                        ['path' => $item->file_path]
                    )
                ];
            });

        return view('pengajuan.show', [
            'pengajuan' => $pengajuan,
            'fileExisting' => $existing
        ]);
    }


    public function uploadDokumenStep2(Request $request, $id)
    {
        $pengajuan = PengajuanLayanan::with('dokumen')
            ->findOrFail($id);

        // Cek kepemilikan
        abort_if($pengajuan->pegawai_id !== auth()->user()->pegawai_id, 403);

        if (!$request->hasFile('dokumen')) {
            return back()->with('error', 'Tidak ada file yang diupload');
        }

        foreach ($request->file('dokumen') as $persyaratanId => $file) {

            if (!$file) continue;

            // Cari dokumen existing
            $dokumen = DokumenPengajuan::firstOrNew([
                'pengajuan_layanan_id' => $pengajuan->id,
                'persyaratan_layanan_id' => $persyaratanId
            ]);

            // Jika sudah ada file lama → hapus
            if ($dokumen->file_path) {
                Storage::disk('private')->delete($dokumen->file_path);
            }

            // Simpan file baru
            $path = $file->store(
                "layanan/{$pengajuan->id}",
                'private'
            );

            $dokumen->file_path = $path;
            $dokumen->status_validasi = null;
            $dokumen->catatan_verifikator = null;
            $dokumen->save();
        }

        // Update step
        $pengajuan->current_step = 3;
        $pengajuan->save();

        return redirect()
            ->route('pengajuan.show', $pengajuan->id)
            ->with('success', 'Dokumen berhasil disimpan');
    }
}