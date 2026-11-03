<?php

namespace App\Http\Controllers\Pensiun;

use App\Http\Controllers\Controller;
use App\Models\AdminPensiunModel;
use App\Models\Pengguna;
use App\Models\PensiunModel;
use App\Services\Referensi\ReferensiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SupervisiController extends Controller
{
    private ReferensiService $referensiService;
    private  $pensiunModel;

    public function __construct(ReferensiService $referensiService, PensiunModel $pensiunModel)
    {
        $this->referensiService = $referensiService;
        $this->pensiunModel = $pensiunModel;
    }

    public function index(Request $request)
    {
        // $nama = $request->nama_pegawai ?? '';
        // $nip = $request->nip ?? '';

        $id_admin = $request->id_admin ?? '';

        //dd($nama);
        $allpegawai = $this->pensiunModel->getPegawaiBkpsdm();

        $skpd = $this->referensiService->skpdAll();
        // $skpdOptions = $skpd->mapWithKeys(function ($item) {
        //     return [$item->kode_skpd => sprintf('%s - %s', $item->kode_skpd, $item->skpd)];
        // });

        $secondDb = config('database.connections.mysql_second.database');

        $admin = AdminPensiunModel::leftJoin("$secondDb.tbl_pegawai as b", 'b.id_pegawai', '=', 'wilayah_admin_pensiun.id_pegawai')
            ->select('b.id_pegawai', 'b.nama_pegawai', 'b.nip')
            ->groupBy('b.id_pegawai');

        $admin = $admin ? $admin->get() : [];

        $wil = AdminPensiunModel::leftJoin("$secondDb.tbl_pegawai as b", 'b.id_pegawai', '=', 'wilayah_admin_pensiun.id_pegawai')
            ->leftJoin("$secondDb.ref_skpd as c", 'c.id_skpd', '=', 'wilayah_admin_pensiun.id_skpd')
            ->where(function ($q) use ($id_admin) {
                if ($id_admin != '') {
                    $q->where('wilayah_admin_pensiun.id_pegawai', $id_admin);
                }
                // if ($nama != '') {
                //     $q->where('b.nama_pegawai', 'like', '%' . $nama . '%');
                // }
                // if ($nip != '') {
                //     $nip2 = $nip;
                //     if (strlen($nip) == 18) {
                //         $nip2 = substr($nip, 0, 8) . " " . substr($nip, 8, 6) . " " . substr($nip, 14, 1) . " " . substr($nip, 15, 3);
                //         $nip2 = str_replace(' ', '', $nip2);
                //     }

                //     $q->where('b.nip', 'like', '%' . $nip . '%')
                //         ->orWhere('b.nip', 'like', '%' . $nip2 . '%');
                // }
            })
            ->select('b.nama_pegawai', 'b.nip', 'b.id_pegawai', 'c.skpd', 'c.id_skpd', 'wilayah_admin_pensiun.id');

        $kerja = $wil ? $wil->get() : [];


        //dd($kerja);
        return view('pages.layanan.pensiun.supervisor.wilayah-kerja', compact('skpd', 'allpegawai', 'kerja', 'id_admin', 'admin'));
    }

    public function assignAdmin(Request $request)
    {
        $request->validate([
            'pegawai' => ['required'],
            'skpd' => ['required']
        ]);

        $id = $request->id;
        $pegawai = DB::connection('mysql_second')
            ->table('tbl_pegawai')
            ->where('id_pegawai', $request->pegawai['id_pegawai'])
            ->first();

        if (! $pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan.',
            ], 404);
        }

        $skpd = DB::connection('mysql_second')
            ->table('ref_skpd')
            ->where('kode_skpd', $request->skpd['kode_skpd'])
            ->first();

        if (! $skpd) {
            echo ('Kode SKPD tidak valid.');
            return response()->json([
                'success' => false,
                'message' => 'Kode SKPD tidak valid.',
            ], 404);
        }

        if ($id > 0) {
            $admin = AdminPensiunModel::findOrFail($id);
            if ($admin) {
                $admin->update([
                    'id_pegawai' => $request->pegawai['id_pegawai'],
                    'id_skpd' => $request->skpd['id_skpd'],
                    'kode_skpd' => $request->skpd['kode_skpd'],
                ]);
            }
        } else {
            AdminPensiunModel::create([
                'id_pegawai' => $request->pegawai['id_pegawai'],
                'id_skpd' => $request->skpd['id_skpd'],
                'kode_skpd' => $request->skpd['kode_skpd'],
            ]);
        }

        // if (Role::where('name', 'AdminOpdPensiun')->exists()) {
        //     $user->syncRoles(['AdminOpdPensiun']);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Data Admin BKPSDM Pensiun berhasil ditambahkan.',
        ]);
    }

    public function dataDetail($id)
    {
        $secondDb = config('database.connections.mysql_second.database');

        $wilayah = AdminPensiunModel::leftJoin("$secondDb.tbl_pegawai as b", 'b.id_pegawai', '=', 'wilayah_admin_pensiun.id_pegawai')
            ->leftJoin("$secondDb.ref_skpd as c", 'c.id_skpd', '=', 'wilayah_admin_pensiun.id_skpd')
            ->select('b.nama_pegawai', 'b.nip', 'b.id_pegawai', 'c.skpd', 'c.id_skpd', 'wilayah_admin_pensiun.id')
            ->where('id', $id)
            ->first();

        if ($wilayah) {
            return response()->json($wilayah);
        }
    }

    public function hapusData($id)
    {
        $wilayah = AdminPensiunModel::findOrFail($id);

        if ($wilayah) {
            $wilayah->delete();

            return response([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }

        return response([
            'success' => false,
            'message' => 'Data tidak tersedia dalam database'
        ]);
    }
}
