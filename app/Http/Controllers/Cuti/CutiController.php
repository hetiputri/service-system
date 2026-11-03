<?php

namespace App\Http\Controllers\Cuti;

use App\Http\Controllers\Controller;

use App\Models\DokumenPengajuan;
use App\Models\Layanan;
use App\Models\LayananCutiDetail;
use App\Models\PengajuanLayanan;
use App\Models\PersyaratanLayanan;
use App\Models\WorkflowLog;
use App\Models\WorkflowStep;
use App\Repositories\Cuti\CutiRepo;
use App\Services\SimpegService;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

//use Illuminate\Http\Request;

class CutiController extends Controller
{
    protected $cutiRepo;
    protected $simpeg;
    public function __construct(CutiRepo $cutiRepo, SimpegService $simpeg)
    {
        $this->cutiRepo = $cutiRepo;
        $this->simpeg = $simpeg;
    }

    public function eligible($pegawai)
    {
        //Prasyarat Cuti

        $masa_kerja = $this->simpeg->masaKerja($pegawai['id_pegawai']);

        //1.Diberi Batasan Cuti Melahirkan tidak untuk laki2
        $eligibility = [];
        if ($pegawai['id_jenis_kelamin'] == 1) {
            array_push(
                $eligibility,
                [
                    'id_jenis_layanan' => 9,
                    'disabled' => true
                ]
            );
        }
        //2. Batasan Jenis Cuti untuk PPPK hanya Cuti Sakit, Melahirkan, Tahunan
        if ($pegawai['id_status_kepegawaian'] == 3) {
            array_push(
                $eligibility,
                [
                    'id_jenis_layanan' => 7,
                    'disabled' => true
                ],
                [
                    'id_jenis_layanan' => 10,
                    'disabled' => true
                ],
                [
                    'id_jenis_layanan' => 11,
                    'disabled' => true
                ]
            );
        }
        //3. Untuk Pegawai dengan TMT<1 Tahun ngga dapat Cuti Tahunan, Cuti Besar, CLTN dll tidak memenuhi syarat
        if ($masa_kerja['y'] < 1 && $pegawai['id_status_kepegawaian'] != 3) {
            array_push(
                $eligibility,
                [
                    'id_jenis_layanan' => 6,
                    'disabled' => true
                ],
            );
        }

        //4. Untuk Pegawai dengan TMT<5 Tahun ngga dapat mengajukan Cuti Besar dan CLTN.
        if ($masa_kerja['y'] < 5) {
            array_push(
                $eligibility,
                [
                    'id_jenis_layanan' => 7,
                    'disabled' => true
                ],
                [
                    'id_jenis_layanan' => 11,
                    'disabled' => true
                ]
            );
        }

        return $eligibility;
    }

    public function index(Request $request)
    {
        $pengajuanId = $request->id ?? 0;

        $pegawai = session('pegawai');
        $token = session('token');

        if (!$pegawai || !$token) {
            return redirect('/login');
        }

        $id = $pegawai['id_pegawai'];
        $id_skpd = $pegawai['id_satuan_organisasi'];
        $id_status_pegawai = $pegawai['id_status_kepegawaian'];

        $hitunganCuti = $this->simpeg->hitungCuti($id);

        //cek masa kerja pegawai dan pppk
        $masa_kerja = $this->simpeg->masaKerja($id);

        if ($pengajuanId > 0) {
            $pengajuan = PengajuanLayanan::with('layanan')
                ->cuti()
                ->where('pegawai_id', $id)
                ->where('current_step', 1)
                ->where('id', $pengajuanId)
                ->first();
        } else {
            $pengajuan = PengajuanLayanan::with('layanan')
                ->cuti()
                ->where('pegawai_id', $id)
                ->where('current_step', 1)
                ->where('id', 0)
                ->first();
        }

        //pilihan daftar usulan
        $daftar_pengajuan = PengajuanLayanan::cuti()
            ->Join('layanan as b', 'pengajuan_layanan.layanan_id', '=', 'b.id')
            ->leftJoin('layanan_cuti_detail as c', 'c.pengajuan_id', '=', 'pengajuan_layanan.id')
            ->where('pegawai_id', $id)
            ->where('current_step', 1)
            ->selectRaw("pengajuan_layanan.nomor_tiket, pengajuan_layanan.status_akhir, pengajuan_layanan.id,DATE_FORMAT(pengajuan_layanan.tanggal_pengajuan, '%Y-%m-%d') AS tanggal_ajuan,b.nama_layanan,c.tgl_mulai,c.tgl_selesai")
            ->orderBy('id', 'ASC');

        $count_daftar = $daftar_pengajuan->count();
        $daftar_pengajuan = $daftar_pengajuan->count() > 0 ? $daftar_pengajuan->get() : [];

        $steps = $this->cutiRepo->stepUsulanCuti();

        //$pengajuanId = $pengajuan ? $pengajuan->id : '0';

        $currentStep = $pengajuan ? $pengajuan->form_step - 1 : 0;
        $selectedJenis = $pengajuan ? $pengajuan->layanan_id : 0;
        $pilihan_jenis_cuti = $pengajuan ? $pengajuan->layanan->nama_layanan : null;


        $maxStep = $pengajuan ? ($pengajuan->form_step ?? 1) - 1 : 0;
        $status_akhir = $pengajuan ? $pengajuan->status_akhir : 'draft';
        $today =  $pengajuan ? $pengajuan->tanggal_pengajuan->format('Y-m-d') : now()->format('Y-m-d');

        //Untuk Cuti Sakit, Bersalin dan alasan penting bisa backdate, PPPK cuti tahunan juga bisa backdate
        if ($selectedJenis == 8 || $selectedJenis == 9 || $selectedJenis == 10 || ($selectedJenis == 6 && $id_status_pegawai == 3)) {
            //$today = now()->subMonth()->format('Y-m-d');
            $today = now()->subDays(15)->format('Y-m-d');
        } elseif ($selectedJenis == 7) {
            $today = now()->copy()
                ->addDays(7)
                ->format('Y-m-d');
        } elseif ($selectedJenis == 11) {
            $today = now()->copy()
                ->addMonth(3)
                ->format('Y-m-d');
        }

        //$kaopd = $this->cutiRepo->kepalaOPD($id_skpd, $id, $selectedJenis)->get();
        $kaopd = $this->simpeg->pejabat($id, 'kaopd', $selectedJenis);

        // $atasan_langsung = $this->cutiRepo->AtasanLangsung($id_skpd, $id, $selectedJenis);
        $atasan_langsung = $this->simpeg->pejabat($id, 'langsung', $selectedJenis);

        //Isian Cuti Detail
        $doc_sp_skpd = '';
        $doc_form_cuti = '';
        $doc_pendukung = '';
        if ($pengajuanId != '0') {
            $cutiDetail = $this->cutiRepo->getCutiDetail($pengajuanId);

            //file upload cuti
            $files = DokumenPengajuan::where('pengajuan_layanan_id', $pengajuanId)->get();

            if ($files->count() > 0) {
                foreach ($files as $doc) {
                    if ($doc->persyaratan_layanan_id == 17) {
                        $doc_sp_skpd = $doc->file_path;
                    }
                    if ($doc->persyaratan_layanan_id == 18) {
                        $doc_form_cuti = $doc->file_path;
                    }
                    if ($doc->persyaratan_layanan_id == 19) {
                        $doc_pendukung = $doc->file_path;
                    }
                }
            }
        } else {
            $cutiDetail = new LayananCutiDetail();
            $files = new DokumenPengajuan();
        }

        if ($pengajuan && $pengajuan->pegawai_id > 0) {
            $data_pegawai = $this->cutiRepo->getDataPegawai($pengajuan->pegawai_id);
        } else {
            $data_pegawai = null;
        }

        $id_detail = $cutiDetail ? $cutiDetail->id : 0;
        $kaopd_data = $cutiDetail ? $this->cutiRepo->getDataPegawai($cutiDetail->kaopd_id) : null; //$this->simpeg->pejabat($id, 'kaopd', $selectedJenis); //$this->cutiRepo->kepalaOPD($id_skpd, $id, $selectedJenis)->first();
        $atasan_data = $cutiDetail ? $this->cutiRepo->getDataPegawai($cutiDetail->atasan_langsung_id) : null;
        $haricuti = $cutiDetail ? $cutiDetail->lama_cuti : null;
        $tanggal_mulai = $cutiDetail ? $cutiDetail->tgl_mulai : null;
        $tanggal_selesai = $cutiDetail ? $cutiDetail->tgl_selesai : null;
        $deskripsi = $cutiDetail ? $cutiDetail->deskripsi : null;
        $nomor_telepon = $cutiDetail ? $cutiDetail->no_telepon : null;
        $alamat = $cutiDetail ? $cutiDetail->alamat : null;

        $dokumen_wajib = '';
        //Hitung 7 hari sejak tanggal pengajuan
        $tenggat_cuti =  now()->copy()
            ->addDays(7)
            ->format('Y-m-d');
        if ($selectedJenis == 6 && $tanggal_mulai && $tenggat_cuti > $tanggal_mulai) {
            $dokumen_wajib = '*';
        } elseif ($selectedJenis >= 0 && $selectedJenis != 6) {
            $dokumen_wajib = '*';
        }

        $eligibility = $this->eligible($pegawai);

        $jenisCuti = Layanan::where('id_jenis_layanan', 2)->orderBy('id', 'asc')->get();
        //dd($doc_pendukung);

        $cat = [];
        //kasus balik ke pengajuan awal dengan perbaikan (status revisi karena ditolak)
        if ($pengajuanId > 0) {
            $cat = WorkflowLog::where('id_pengajuan', $pengajuanId)
                ->where('urutan_step', 2)
                ->latest()->first();
        }

        $catatan = $cat ? $cat->catatan : '';
        $satuan_waktu = 'Hari Kalender';
        if ($pengajuan && $pengajuan->layanan_id == 6) {
            $satuan_waktu = 'Hari Kerja';
        }

        //dd($eligibility);
        return view('pages.layanan.cuti.pengajuan-cuti', compact(
            'id_status_pegawai',
            'kaopd',
            'atasan_langsung',
            'eligibility',
            'status_akhir',
            'steps',
            'pengajuan',
            'jenisCuti',
            'pengajuanId',
            'currentStep',
            'maxStep',
            'today',
            'id_detail',
            'kaopd_data',
            'atasan_data',
            'haricuti',
            'tanggal_mulai',
            'tanggal_selesai',
            'deskripsi',
            'nomor_telepon',
            'alamat',
            'satuan_waktu',
            'cutiDetail',
            'data_pegawai',
            'doc_sp_skpd',
            'doc_form_cuti',
            'doc_pendukung',
            'selectedJenis',
            'catatan',
            'dokumen_wajib',
            'count_daftar',
            'daftar_pengajuan',
            'masa_kerja',
            'hitunganCuti',
            'pilihan_jenis_cuti'
        ));
    }
    public function setJenis(Request $request)
    {
        $pengajuan_id = $request->id;
        $jenis_cuti = $request->jenis_cuti;
        $onStep = $request->ongoingStep;
        $formData = [];
        if ($onStep == 3) {
            $formData = [
                'pengajuan_id' => $pengajuan_id,
                'kaopd_id' => $request->selected['id_pegawai'],
                'atasan_langsung_id' => $request->selectedAt['id_pegawai'],
                'tanggal_pengajuan' => $request->tanggal,
                'lama_cuti' => $request->haricuti,
                'tgl_mulai' => $request->dariTanggal,
                'tgl_selesai' => $request->sampaiTanggal,
                'deskripsi' => $request->deskripsi,
                'no_telepon' => $request->nomorTelepon,
                'alamat' => $request->alamat,
                'satuan_waktu' => $request->satuan_waktu
            ];
        }

        //dd($request->id);
        $success = false;
        if ($pengajuan_id == 0) {
            $onStep = 1;
            $ajuan = PengajuanLayanan::create([
                'pegawai_id' => session('pegawai')['id_pegawai'],
                'id_pangkat' => session('pegawai')['id_pangkat'],
                'id_jabatan' => session('pegawai')['id_jabatan'],
                'id_skpd' => session('pegawai')['id_satuan_organisasi'],
                'layanan_id' => $jenis_cuti,
                'id_jenis_layanan' => 2,
                'tanggal_pengajuan' => now(),
                'status_akhir' => 'draft',
                'current_step' => 1,
                'form_step' => $onStep + 1
            ]);
            $success = true;
        } else {
            $ajuan = PengajuanLayanan::cuti()->findOrFail($pengajuan_id);
            if ($ajuan) {
                if ($jenis_cuti != 0) { //dari step pilih jenis cuti atau onStep 1
                    $ajuan->update([
                        'layanan_id' => $jenis_cuti,
                        'form_step' => $onStep + 1
                    ]);
                } else if ($onStep == 2 || $onStep == 4) { //dari step tinjau prasyarat
                    $ajuan->update([
                        'form_step' => $onStep + 1
                    ]);
                } else if ($onStep == 3) { //dari step input data
                    $ajuan->update([
                        'form_step' => $onStep + 1
                    ]);

                    if ($request->id_detail == 0) {
                        LayananCutiDetail::create($formData);
                    } elseif ($request->id_detail > 0) {
                        LayananCutiDetail::findOrFail($request->id_detail)->update($formData);
                    }
                }
            } else {
                $success = false;
            }
        }
        return response()->json([
            'success' => $success,
            'pengajuan_id' => $ajuan->id
        ]);
    }

    public function printFormulir(string $pengajuanId, string $ttd)
    {
        $cutiDetail = $this->cutiRepo->getCutiDetail($pengajuanId);

        if (!$cutiDetail) {
            return redirect()->back()->with('error', 'Data cuti tidak ditemukan.');
        }

        $pegawai = session('pegawai');
        $id_status_pegawai = $pegawai['id_status_kepegawaian'];

        $hitunganCuti = $this->simpeg->hitungCuti($pegawai['id_pegawai']);

        //cek masa kerja pegawai dan pppk
        //$masa_kerja = $this->cutiRepo->masaKerja($pegawai['id_pegawai'], $id_status_pegawai);
        $masa_kerja = $this->simpeg->masaKerja($pegawai['id_pegawai']);

        $data_pegawai = $this->cutiRepo->getDataPegawai($cutiDetail->pegawai_id);
        $kaopd_data = $this->cutiRepo->getDataPegawai($cutiDetail->kaopd_id);
        $atasan_data = $this->cutiRepo->getDataPegawai($cutiDetail->atasan_langsung_id);

        $pdf = PDF::loadView('pdf.layananCuti.formulir-cuti', [
            'cutiDetail' => $cutiDetail,
            'data_pegawai' => $data_pegawai,
            'kaopd_data' => $kaopd_data,
            'atasan_data' => $atasan_data,
            'id_status_pegawai' => $id_status_pegawai,
            'ttd' => $ttd,
            'masa_kerja' => $masa_kerja,
            'hitunganCuti' => $hitunganCuti
        ])->setPaper('A4', 'portrait');

        return $pdf->download('Formulir_Cuti_' . str_replace(' ', '', $data_pegawai->nip) . '.pdf');
    }
    public function uploadDokumen(Request $request)
    {
        try {

            $maxKb = $request->doc_id == 18 ? 500 : 2048; // 500KB for doc_id 18, otherwise 2MB
            $maxMessage = $request->doc_id == 18 ? 'Ukuran maksimal 500KB ' : 'Ukuran maksimal 2MB ';

            $request->validate([
                'idPeng' => 'required|integer',
                'doc_id' => 'required|integer',
                'file' => 'required|file|mimes:pdf|max:' . $maxKb
            ], [
                'file.required' => 'File wajib diupload ',
                'file.mimes' => 'Format harus PDF',
                'file.max' => $maxMessage
            ]);

            $pengajuanId = $request->idPeng;
            $persyaratanId = $request->doc_id;

            $persyaratan = PersyaratanLayanan::where("id", $persyaratanId)->first();
            $nip =  str_replace(' ', '', session('pegawai')['nip']);

            if (!$persyaratan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Persyaratan tidak ditemukan ❌'
                ], 200); // ✅ ubah jadi 200
            }

            $dokumen = $request->file("file");
            $filename = 'PengajuanId-' . $pengajuanId . '-id_syarat_layanan-' . $persyaratanId . '-' . Str::slug($persyaratan->nama_persyaratan, '_') . '.' . $dokumen->getClientOriginalExtension();
            $path = $dokumen->storeAs('cuti/pegawai/' . $nip, $filename);

            $dokumen = DokumenPengajuan::updateOrCreate(
                [
                    'pengajuan_layanan_id' => $pengajuanId,
                    'persyaratan_layanan_id' => $persyaratanId
                ],
                [
                    'file_path' => $path,
                    'status_validasi' => 'pending'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload ✅',
                //'file_url' => route('dokumen.preview', $dokumen->id),
                'path' => $path
            ], 200);
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

    public function LihatDokumen(Request $request)
    {
        $pengajuanId = $request->pengajuanId;
        $docs_id = $request->docs_id;

        $filedoc = '';
        $alldoc = DokumenPengajuan::where('pengajuan_layanan_id', $pengajuanId)->get();
        if ($alldoc->count() > 0) {
            foreach ($alldoc as $docs) {
                if ($docs->persyaratan_layanan_id == $docs_id) {
                    $filedoc = $docs->file_path;
                }
            }
        }

        abort_unless($filedoc, 404);
        abort_unless(Storage::disk('private')->exists($filedoc), 404);
        return response()->file(storage_path('app/private/' . $filedoc));
    }

    public function UsulanSelesai(string $id)
    {
        $ajuan = PengajuanLayanan::cuti()->findOrFail($id);
        $cutiDetail = LayananCutiDetail::where('pengajuan_id', $id)->first();
        if ($ajuan) {
            $layanan_id = $ajuan->layanan_id;
            $tanggal_usulan = now();

            //Cek Kelengkapan Berkas
            $allsyarat = '';
            $sp_skpd = 'Surat Pengantar tidak tersedia, ';
            $form_cuti = 'Formulir Cuti tidak tersedia, ';
            $dokumen_lain = 'Dokumen Pendukung tidak tersedia.';
            $dokumens = DokumenPengajuan::where('pengajuan_layanan_id', $id);
            if ($dokumens->count() > 0) {
                $dokumens = $dokumens->get();
                foreach ($dokumens as $docs) {
                    //1. Cek Dokumen Surat Pengantar
                    if ($docs->persyaratan_layanan_id == 17 && $docs->file_path != '') {
                        $sp_skpd = '';
                    }

                    //2. Cek Form Cuti
                    if ($docs->persyaratan_layanan_id == 18 && $docs->file_path != '') {
                        $form_cuti = '';
                    }

                    //3. Cek Dokumen Lain
                    if ($docs->persyaratan_layanan_id == 19 && $docs->file_path != '') {
                        $dokumen_lain = '';
                    }
                }
            }

            $allsyarat .= $sp_skpd;
            $allsyarat .= $form_cuti;

            //Hitung 7 hari sejak tanggal pengajuan
            $tenggat_cuti =  $tanggal_usulan->copy()
                ->addDays(7)
                ->format('Y-m-d');

            if ($layanan_id >= 7 && $layanan_id <= 11) {
                $allsyarat .= $dokumen_lain; //Kecuali cuti tahunan dengan tanggal pengajuan>=7 hari TMT Cuti bebas dokumen pendukung
            } elseif ($layanan_id == 6 && $tenggat_cuti > $cutiDetail->tgl_mulai) {
                $allsyarat .= $dokumen_lain;
            }

            if ($allsyarat != '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload dokumen pendukung tidak lengkap :' . $allsyarat,
                    //'data' => $docs
                ]);
            } else {

                //dokumen prasyarat lengkap
                //Buat Workflow Log dan cek WorkflowStep
                $lastLog = WorkflowLog::where('id_pengajuan', $id)
                    ->latest('id_log')
                    ->first();

                //belum ada log
                $workflowStep = WorkflowStep::where('urutan_step', $ajuan->current_step + 1)
                    ->where("id_layanan", $ajuan->id_jenis_layanan)
                    ->first();

                $catatan = '';
                if ($ajuan->status_akhir == 'Revisi') {
                    $catatan = 'Revisi';
                }
                $ajuan->update([
                    'status_akhir' => 'diajukan',
                    'current_step' => 2,
                    'form_step' => $ajuan->form_step + 1
                ]);

                WorkflowLog::addLog(
                    $id,
                    $workflowStep->id_step - 1,
                    $workflowStep->urutan_step - 1,
                    $ajuan->status_akhir,
                    'USER',
                    $catatan
                );


                // ❗ validasi penting
                if (!$workflowStep) {
                    throw new \Exception('Workflow step tidak ditemukan');
                }

                // 🔥 generate nomor tiket
                if (!$ajuan->nomor_tiket) {
                    $generate = PengajuanLayanan::generateNomorTiket($ajuan->layanan_id);
                    $ajuan->nomor_tiket = $generate;

                    $ajuan->tanggal_pengajuan = now();
                    $ajuan->save();
                } else {
                    $generate = $ajuan->nomor_tiket;
                }


                return response()->json([
                    'success' => true,
                    'message' => 'Pegawai berhasil mengajukan Usulan Cuti ke Admin BKPSDM dengan Nomor Tiket ' . $generate
                ]);
            }
        }
    }

    public function HapusUsulan(string $id)
    {
        //1. Cari dokumen existing
        $dokumen = DokumenPengajuan::where('pengajuan_layanan_id', $id);

        if ($dokumen->count() > 0) {
            $dokumen = $dokumen->get();
            foreach ($dokumen as $docs) {
                if ($docs->file_path) {
                    Storage::disk('private')->delete($docs->file_path);
                }
            }
        }

        //2. Hapus Isian Layanan Cuti Detail
        $cutidetail = LayananCutiDetail::where('pengajuan_id', $id)->first();
        if ($cutidetail) {
            $cutidetail->delete();
        }

        //3 Hapus dari Pengajuan Layanan
        $pengajuan = PengajuanLayanan::find($id);
        if ($pengajuan) {
            $pengajuan->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Usulan Cuti berhasil dihapus dari sistem'
        ]);
    }
}
