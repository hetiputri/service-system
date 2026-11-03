<?php

namespace App\Http\Controllers\Cuti;

use App\Http\Controllers\Controller;
use App\Models\CutiModel;
use App\Models\Layanan;
use App\Models\LayananCutiDetail;
use App\Models\PengajuanLayanan;
use App\Models\ProsesLayananCuti;
use App\Models\ProsesLayananCutiDetail;
//use App\Models\RiwCutiModel;
use App\Models\WorkflowLog;
use App\Models\WorkflowStep;
use App\Repositories\Cuti\CutiRepo;
use App\Services\SimpegService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use iio\libmergepdf\Merger;

class CutiProsesAdmin extends Controller
{
    protected $cutiModel;
    protected $cutiRepo;
    protected $simpeg;
    public function __construct(CutiModel $cutiModel, CutiRepo $cutiRepo, SimpegService $simpeg)
    {
        $this->cutiModel = $cutiModel;
        $this->cutiRepo = $cutiRepo;
        $this->simpeg = $simpeg;
    }

    public function first()
    {
        $arrays = $this->cutiModel->allProses();
        return view('pages.layanan.cuti.admin.main-proses', compact('arrays'));
    }

    public function tambahProses(Request $request)
    {
        $validatedData = $request->validate([
            'adminName' => 'required|string|max:255',
            'tanggalKerja' => 'required|date'
        ]);

        $data = [
            'id_pemroses' => session('pegawai')['id_pegawai'],
            'nama_admin' => $validatedData['adminName'],
            'tgl_kerja' => $validatedData['tanggalKerja'],
            'tgl_input' => now()
        ];
        $proses = $this->cutiModel->insertProses($data);

        return $proses;
    }

    public function hapusProsesUtama($id_proses)
    {
        $proses = ProsesLayananCuti::find($id_proses);
        if ($proses) {
            $proses->delete();
            return [
                'success' => true,
                'message' => 'Proses Kerja berhasil dihapus'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Maaf, Proses Kerja tidak terdaftar'
            ];
        }
    }

    public function DaftarPegawai(Request $request)
    {
        $id = $request->id;
        $dataPegawai = $this->cutiRepo->getDaftarPegawai($id) ?? [];
        $proses = ProsesLayananCuti::find($id);
        //dd($dataPegawai);
        return view('pages.layanan.cuti.admin.daftar-pegawai', compact('dataPegawai', 'id', 'proses'));
    }

    public function index(Request $request)
    {
        $id = $request->id;
        $arrays = $this->cutiModel->allUsulan();
        return view('pages.layanan.cuti.admin.proses-usulan', compact('arrays', 'id'));
    }

    public function cariUsulan(Request $request)
    {
        $nama = $request->nama_pegawai;
        $nip = $request->nip;
        $id_skpd = $request->id_skpd;

        $success = false;
        $data = $this->cutiModel->search($nama, $nip, $id_skpd);

        if ($data != null) {
            $success = true;
        }
        return response()->json([
            'success' => $success,
            'data' => $data
        ]);
    }

    public function detailUsulan(Request $request)
    {
        $id = $request->id;
        $idProses = $request->idp;
        $pengajuan = PengajuanLayanan::find($id);
        $dataPegawai = $this->cutiRepo->getDataPegawai($pengajuan->pegawai_id);
        $cutiDetail = $this->cutiRepo->getCutiDetail($id);

        //cek dokumen
        $files = $this->cutiRepo->cekDokumen($id);
        $doc_sp_skpd = $files ? $files['doc_sp_skpd'] : '';
        $doc_form_cuti = $files ? $files['doc_form_cuti'] : '';
        $doc_pendukung = $files ? $files['doc_pendukung'] : '';

        //history riwayat cuti pegawai        
        // $riwayat_cuti = $this->simpeg->riwayatCuti($pengajuan->pegawai_id);
        $riwayat_cuti = $this->simpeg->getCuti($pengajuan->pegawai_id, '', '', 'riwayat-proses');
        $riwayat_cuti_baru = $this->cutiRepo->getRiwayatBaru($pengajuan->pegawai_id, $pengajuan->id);

        return view('pages.layanan.cuti.admin.detail-usulan', compact(
            'pengajuan',
            'dataPegawai',
            'cutiDetail',
            'doc_sp_skpd',
            'doc_form_cuti',
            'doc_pendukung',
            'idProses',
            'riwayat_cuti',
            'riwayat_cuti_baru'
        ));
    }

    public function tolakUsulan(Request $request)
    {
        $idPengajuan = $request->pengajuanId;
        $catatan = $request->catatan_text;

        $workflowStep = WorkflowStep::where('urutan_step', 2)
            ->where("id_layanan", 2)
            ->first();

        WorkflowLog::addLog(
            $idPengajuan,
            $workflowStep->id_step,
            2,
            'ditolak',
            'BKPSDM',
            $catatan
        );

        $pengajuan = PengajuanLayanan::find($idPengajuan);
        $pengajuan->update([
            'status_akhir' => 'Revisi',
            'current_step' => 1,
            'form_step' => 5
        ]);

        return [
            'success' => true,
            'message' => 'Catatan Tolak berhasil disimpan.'
        ];
    }

    public function tambahDaftarPegawai(Request $request) //diterima
    {
        $id_usulan = $request->id_usulan;
        $idProses = $request->id_proses;
        $pengajuan = PengajuanLayanan::find($id_usulan);
        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Usulan tidak ditemukan.'
            ], 404);
        } else {
            $data = [
                'id_proses' => $idProses,
                'pengajuan_id' => $id_usulan,
                'tgl_proses' => now(),
                'id_pemroses' => session('pegawai')['id_pegawai']
            ];

            ProsesLayananCutiDetail::create($data);

            $workflowStep = WorkflowStep::where('urutan_step', 2)
                ->where("id_layanan", 2)
                ->first();

            WorkflowLog::addLog(
                $id_usulan,
                $workflowStep->id_step,
                2,
                'diterima',
                'BKPSDM',
                ''
            );

            //jika diterima, naikkan current_step ke 3 dan ubah status_akhir menjadi diterima
            $pengajuan->update([
                'status_akhir' => 'Diterima',
                'current_step' => 3
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Pegawai berhasil ditambahkan ke daftar proses kerja cuti.'
            ]);
        }
    }

    public function hapusProses(string $id)
    {
        $prosesDetail = ProsesLayananCutiDetail::where('pengajuan_id', $id)->first();
        if ($prosesDetail) {
            $prosesDetail->delete();

            //jika dihapus, kembalikan current_step ke 2 dan ubah status_akhir menjadi diajukan
            $pengajuan = PengajuanLayanan::find($id);
            $pengajuan->update([
                'status_akhir' => 'diajukan',
                'current_step' => 2
            ]);

            $workflowStep = WorkflowStep::where('urutan_step', 3)
                ->where("id_layanan", 2)
                ->first();

            //Tambah workflowlog terakhir
            WorkflowLog::addLog(
                $pengajuan->id,
                $workflowStep->id_step,
                3,
                'dibatalkan',
                'BKPSDM'
            );
            // $logs = WorkflowLog::where('id_pengajuan', $id)
            //     ->latest('id_log')
            //     ->first();

            // if ($logs) {
            //     $logs->delete();
            // }

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan Cuti Pegawai berhasil dihapus dari daftar proses kerja cuti.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan Cuti Pegawai tidak ditemukan dalam daftar proses kerja cuti.'
            ], 404);
        }
    }

    public function cekFile(string $id)
    {
        //cek dokumen
        $files = $this->cutiRepo->cekDokumen($id);
        $doc_sp_skpd = $files ? $files['doc_sp_skpd'] : '';
        $doc_form_cuti = $files ? $files['doc_form_cuti'] : '';
        $doc_pendukung = $files ? $files['doc_pendukung'] : '';

        return response()->json([
            'doc_sp_skpd' => $doc_sp_skpd,
            'doc_form_cuti' => $doc_form_cuti,
            'doc_pendukung' => $doc_pendukung
        ]);
    }

    public function uploadSKFinal(Request $request)
    {
        try {
            $request->validate([
                //'idProses' => 'required|integer',
                'id_pengajuan' => 'required|integer',
                'no_sk' => 'required',
                'tgl_sk' => 'required',
                'pejabat_sk' => 'required',
                'file' => 'required|file|mimes:pdf|max:8048'
            ], [
                'file.required' => 'File wajib diupload ',
                'file.mimes' => 'Format harus PDF',
                'file.max' => 'Ukuran maksimal 7MB '
            ]);

            $pengajuanId = $request->id_pengajuan;
            $no_sk = $request->no_sk;
            $tgl_sk = $request->tgl_sk;
            $pejabat_sk = $request->pejabat_sk;
            $proses = ProsesLayananCutiDetail::where('pengajuan_id', $pengajuanId)->first();

            if (!$proses) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pegawai dalam Proses Kerja Cuti tidak ditemukan ❌'
                ], 200); // ✅ ubah jadi 200
            }

            $dokumen = $request->file("file");
            //$filename = 'ProsesId-' . $prosesId . '-SK_Final-' . $dokumen->getClientOriginalExtension();
            $filename = 'PengajuanId-' . $pengajuanId . '-SK_Final-' . $dokumen->getClientOriginalExtension();
            $path = $dokumen->storeAs('cuti/sk', $filename);

            $proses->update([
                'no_sk' => $no_sk,
                'tgl_sk' => $tgl_sk,
                'pejabat_sk' => $pejabat_sk,
                'scan_sk' => $path,
            ]);

            $workflowStep = WorkflowStep::where('urutan_step', 3)
                ->where("id_layanan", 2)
                ->first();

            if ($proses) {
                //1. Update di workflowlog
                //Cek jika terjadi pengulangan upload SK maka hapus log sebelumnya
                $existingLog = WorkflowLog::where('id_pengajuan', $pengajuanId)
                    ->where('id_step', $workflowStep->id_step)
                    ->where('aksi', 'selesai')
                    ->first();
                if ($existingLog) {
                    $existingLog->update(['catatan' => 'upload ulang SK']);
                } else {
                    WorkflowLog::addLog(
                        $pengajuanId, //$dapeg->id,
                        $workflowStep->id_step,
                        3,
                        'selesai',
                        'BKPSDM',
                        ''
                    );
                }


                //2. update di status pengajuan layanan
                $pengajuan = PengajuanLayanan::find($pengajuanId); //$dapeg->id);
                if ($pengajuan) {
                    $pengajuan->update([
                        'status_akhir' => 'selesai',
                        'sk_path' => $path,
                    ]);
                }

                //3.masukkan ke Data Riwayat Cuti Pegawai
                $detail = LayananCutiDetail::where('pengajuan_id', $pengajuanId)->first();
                $pengajuan = PengajuanLayanan::find($pengajuanId);
                $data = [];
                if ($detail) {
                    $jenis_cuti_konv = $this->cutiModel->KonversiJenisCuti($pengajuan->layanan_id);
                    $pejabat = $this->cutiModel->getPegawai($detail->kaopd_id);
                    $data = [
                        'id_pegawai' => $pengajuan->pegawai_id,
                        'id_jenis_cuti' => $jenis_cuti_konv->id_jenis_cuti,
                        'lama' => $detail->lama_cuti,
                        'dari' => $detail->tgl_mulai,
                        'sampai' => $detail->tgl_selesai,
                        'keterangan' => $detail->deskripsi,
                        'no_sk' => $no_sk,
                        'tgl_sk' => $tgl_sk,
                        'pejabat_sk' => $pejabat_sk,
                        'scan_sk' => '',
                        'status_spv' => 0,
                        'id_user' => session('pegawai')['id_pegawai']
                    ];

                    $filePath = Storage::path($pengajuan->sk_path);

                    //RiwCutiModel::create($data);
                    $this->simpeg->updateData($data, 'cuti', $filePath); //update ke simpeg lama dengan API

                }
            }


            return response()->json([
                'success' => true,
                'message' => 'SK Cuti Final berhasil ditambahkan.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 200); // ✅ ubah jadi 200

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server ❌',
                // 'debug' => $e->getMessage() // optional (aktifin kalau mau debug)
            ], 200); // ✅ ubah jadi 200
        }
    }

    public function LihatSK(Request $request)
    {
        //$prosesId = $request->id;
        $pengajuanId = $request->id;

        $filedoc = '';
        //$proses = ProsesLayananCuti::findOrFail($prosesId);
        $proses = ProsesLayananCutiDetail::where('pengajuan_id', $pengajuanId)->first();
        if ($proses) {
            $filedoc = $proses->scan_sk;
        }

        abort_unless($filedoc, 404);
        abort_unless(Storage::disk('private')->exists($filedoc), 404);

        return response()->file(storage_path('app/private/' . $filedoc), [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function UnduhFiles(Request $request)
    {
        $id_proses = $request->id_proses;
        $jenis_file = $request->jenis_file;

        $query = ProsesLayananCuti::leftJoin('proses_layanan_cuti_detail as b', 'b.id_proses', '=', 'proses_layanan_cuti.id')
            ->leftJoin('dokumen_pengajuan as c', 'b.pengajuan_id', '=', 'c.pengajuan_layanan_id')
            ->where('proses_layanan_cuti.id', $id_proses)
            ->whereNotNull('c.file_path');

        $today = date("Y-m-d");

        if ($jenis_file === 'sp') {
            $query->where('c.persyaratan_layanan_id', '=', 17);
            $filename = 'surat-pengantar-kumulatif-' . $today . '.pdf';
        } elseif ($jenis_file === 'form') {
            $query->where('c.persyaratan_layanan_id', '=', 18);
            $filename = 'form-cuti-kumulatif-' . $today . '.pdf';
        } else {
            abort(404, 'Jenis file tidak valid.');
        }

        $filePaths = $query->pluck('c.file_path')->filter()->unique()->values();

        if ($filePaths->isEmpty()) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $merger = new Merger();

        foreach ($filePaths as $filePath) {
            $storagePath = Storage::disk('private')->path($filePath);
            if (!file_exists($storagePath)) {
                continue;
            }
            $merger->addFile($storagePath);
        }

        $mergedPdf = $merger->merge();

        return response($mergedPdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function rekapCuti(Request $request)
    {
        $mulai_tanggal = $request->dariTanggal ?? '';
        $sampai_tanggal = $request->sampaiTanggal ?? '';
        $id_jenis_cuti = $request->selectedAt['id'] ?? 0;
        $status = $request->selectedStat['nama_status'] ?? '';

        $selected = $request->selectedAt ?? [];
        $selectedStat = $request->selectedStat ?? [];

        //Lihat Hasil Cuti
        $rekap_cuti = $this->cutiModel->rekapCuti($mulai_tanggal, $sampai_tanggal, $id_jenis_cuti, $status);

        //Jenis Cuti
        $jenis_cuti = Layanan::where('id_jenis_layanan', 2)
            ->orderBy('id', 'ASC')->get();

        $status = [
            [
                'status' => 'selesai',
                'nama_status' => 'selesai'
            ],
            [
                'status' => 'diterima/ dalam proses',
                'nama_status' => 'diterima'
            ]

        ];
        $title = "Rekap Cuti";


        return view('pages.layanan.cuti.admin.rekap-hasil-cuti', compact('jenis_cuti', 'rekap_cuti', 'mulai_tanggal', 'sampai_tanggal', 'selected', 'status', 'selectedStat', 'title'));
    }

    public function insertIndexVer($id_data, $id_pegawai, $id_riwayat, $id_index) //untuk update simpeg lama
    {
        //, hanya untuk data riwayat berjumlah>1 data per riwayat
        // $id_index = 1;//sementara hanya untuk jenis 'tambah data' dari simpeg baru bagian layanan
        // #Riwayat Cuti id_data ke 15
        // $dataname = array("", "", "tbl_anak", "", "", "tbl_riwayat_pmk", "tbl_riwayat_pangkat", "tblriwayatgaji", "tbl_pendidikan", "tbl_riwayat_jabatan", "tbl_riwayat_pi", "tbl_riwayat_hukuman_disiplin", "tbl_riwayat_diklat_struktural", "tbl_riwayat_penghargaan", "tbl_riwayat_diklat_lainnya", "tbl_cuti_riwayat");
        // $cek_index = \DB::table('tbl_index_verifikasi')
        //     ->where('jenis_tabel', $dataname[$id_data])
        //     ->where('id_riwayat', $id_riwayat)
        //     ->where('id_pegawai', $id_pegawai)
        //     ->where('id_index', $id_index);

        // if ($cek_index->count() == 0) {
        //     #1. Masukkan ke Data Index
        //     \DB::table('tbl_index_verifikasi')->insert([
        //         'id_index' => $id_index,
        //         'jenis_tabel' => $dataname[$id_data],
        //         'id_riwayat' => $id_riwayat,
        //         'id_pegawai' => $id_pegawai
        //     ]);

        //     #2. Masukkan ke Aktivitas Input Data
        //     \DB::table('tbl_aktivitas_input_data')->insert([
        //         'id_index' => $id_index,
        //         'jenis_tabel' => $dataname[$id_data],
        //         'id_riwayat' => $id_riwayat,
        //         'id_pegawai' => $id_pegawai,
        //         'id_user' => session('pegawai')['id_pegawai'],
        //         'tgl_input' => now(),
        //         'asal_input' => 3
        //     ]);          

        // }

    }

    public function editMenuVer($id_data, $id_pegawai, $id_author)
    {
        // $verif = \DB::table('tbl_verifikasi_data_pegawai')->where('id_pegawai', $id_pegawai);
        // if ($verif->count() == 0) {
        //     \DB::table('tbl_verifikasi_data_pegawai')->insert([
        //         'id_pegawai' => $id_pegawai,
        //         'ref_verifikasi_' . $id_data => 'yes',
        //         'id_author' => $id_author
        //     ]);
        // } else {
        //     $verif->first()->update([
        //         'ref_verifikasi_' . $id_data => 'yes',
        //         'id_author' => $id_author
        //     ]);
        // }
    }

    public function UpdateCutiRiwayat(Request $request) //untuk update simpeg lama
    {
        // $id_pegawai = $request->id_pegawai;
        // $id_jenis_cuti = $request->id_jenis_cuti;
        // $lama = $request->lama;
        // $dari = $request->dari;
        // $sampai = $request->sampai;

        // $data = [];
        // if ($id_pegawai > 0) {
        //     $data = [
        //         'id_pegawai' => $id_pegawai,
        //         'id_jenis_cuti' => $id_jenis_cuti,
        //         'lama' => $lama,
        //         'dari' => $dari,
        //         'sampai' => $sampai,
        //         'keterangan' => $request->keterangan,
        //         'no_sk' => $request->no_sk,
        //         'tgl_sk' => $request->tgl_sk,
        //         'pejabat_sk' => $request->pejabat_sk,
        //         'scan_sk' => '',
        //         'status_spv' => 0
        //     ];

        //     #1.Masukkan ke Riwayat Cuti Pegawai dengan stat_spv=0
        //     $riwcuti = \DB::table('tbl_cuti_riwayat')
        //         ->where([
        //             ['id_pegawai', $id_pegawai],
        //             ['id_jenis_cuti', $id_jenis_cuti],
        //             ['lama', $lama],
        //             ['dari', $dari],
        //             ['sampai', $sampai]
        //         ]);

        //     if ($riwcuti->count() > 0) {
        //         $riwcuti = $riwcuti->first();
        //         $riwcuti->update($data);
        //     } else {
        //         $riwcuti = \DB::table('tbl_cuti_riwayat')->insert($data);
        //     }

        //     #2.Masukkan ke Riwayat Cuti Temporer
        //     \DB::table('temporer_tbl_cuti_riwayat')->insert([
        //         'id_riwayat_cuti' => $riwcuti->id_riwayat_cuti ?? null,
        //         'id_pegawai' => $id_pegawai,
        //         'id_jenis_cuti' => $id_jenis_cuti,
        //         'lama' => $lama,
        //         'dari' => $dari,
        //         'sampai' => $sampai,
        //         'keterangan' => $request->keterangan,
        //         'no_sk' => $request->no_sk,
        //         'tgl_sk' => $request->tgl_sk,
        //         'pejabat_sk' => $request->pejabat_sk,
        //         'status_tolak' => 0
        //     ]);

        //     #3. Assign Info Verifikasi ke Menu Verifikasi Data
        //     //disini

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Data Riwayat Cuti berhasil diperbarui.'
        //     ]);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'ID Pegawai tidak valid.'
        //     ], 400);
        // }
    }
}
