<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function getMenuGroups(): array
    {
        $user = Auth::user();
        $pegawai = session('pegawai');
        $id_status_pegawai = session('pegawai')['id_status_kepegawaian'];

        $pensiunMenus = [];
        $cariPegawaiMenu = [];
        $rekapDataMenu = [];

        $cariPegawaiMenu = $user->hasPermissionTo('r-cari-data-pegawai')
            ? [[
                'name' => 'Pencarian Data Pegawai',
                'path' => url('/cari-pegawai'),
                'icon' => 'person-search',
            ]]
            : [];

        // if (auth()->user()->hasRole('AdminOpdPensiun')) {
        //     $pensiunMenus[] = [
        //         'name' => 'Admin OPD',
        //         'path' => url('admin-pensiun?role=AdminOpdPensiun'),
        //     ];
        // }

        // if (auth()->user()->hasRole('AdminBkdPensiun')) {
        //     $pensiunMenus[] = [
        //         'name' => 'Admin BKPSDM',
        //         'path' => url('/admin-pensiun?role=AdminBkdPensiun'),
        //     ];
        // }

        // hasAnyPermission tidak error walaupun permission belum dibuat di tabel permissions
        $integrasiSiasnMenu = $user->hasAnyPermission('integrasi-siasn')
            ? [
                [
                    'name' => 'Dashboard Integrasi',
                    'path' => url('/integrasi-siasn/dashboard'),
                    'icon' => 'arrows-rotate',
                ],
            ]
            : [];

        $laporanMenu = [];
        if ($user->hasPermissionTo('read-bezetting')) {
            array_push(
                $laporanMenu,
                [
                    'name' => 'Bezetting',
                    'path' => url('/kepegawaian/bezetting'),
                    'icon' => 'dashboard',
                ]
            );
        }

        if ($user->hasPermissionTo('rekapan-data')) {
            array_push(
                $rekapDataMenu,
                [
                    'name' => 'Dashboard Data',
                    'path' => url('/dashboard-data'),
                    'icon' => 'report',
                ]
            );
        }

        if ($user->hasPermissionTo('read-daftar-pegawai')) {
            array_push(
                $laporanMenu,
                [
                    'name' => 'Daftar Pegawai',
                    'path' => url('/kepegawaian/daftar-pegawai'),
                    'icon' => 'dashboard',
                ]
            );
        }

        $layananMutasi = [];

        if ($user->hasAnyPermission('c-mutasi-struktural', 'r-mutasi-struktural', 'u-mutasi-struktural', 'd-mutasi-struktural', 'c-mutasi-struktural-detail', 'r-mutasi-struktural-detail', 'u-mutasi-struktural-detail', 'd-mutasi-struktural-detail')) {
            array_push(
                $layananMutasi,
                [
                    'name' => 'Konsep Mutasi',
                    'path' => url('/mutasi/konsep'),
                    'icon' => 'dashboard',
                ]
            );
        }

        if ($user->hasPermissionTo('read-pejabat-struktural')) {
            array_push(
                $laporanMenu,
                [
                    'name' => 'Pejabat Struktural',
                    'path' => url('/kepegawaian/pejabat-struktural'),
                    'icon' => 'dashboard',
                ]
            );
        }

        $pengaturan = [];
        if ($user->hasPermissionTo('pendaftaran-akses')) {
            array_push(
                $pengaturan,
                [
                    'name' => 'Daftar Akses',
                    'path' => url('/pengaturan-akses'),
                    'icon' => 'settings',
                ]
            );
        }

        if ($user->hasPermissionTo('pengaturan-akun')) {
            array_push(
                $pengaturan,
                [
                    'name' => 'Pengaturan Akun',
                    'path' => url('/pengaturan-akun'),
                    'icon' => 'dashboard',
                ]
            );
        }

        if ($user->hasPermissionTo('sinkronisasi-data')) {
            array_push(
                $pengaturan,
                [
                    'name' => 'Sinkronisasi Data',
                    'path' => url('/sinkron-data'),
                    'icon' => 'dashboard',
                ]
            );
        }

        $showed = [];
        if ($user->hasAnyPermission('pendaftaran-akses', 'pengaturan-akun', 'sinkronisasi-data')) {
            $showed =
                [
                    'title' => 'Pengaturan',
                    'items' => [
                        [
                            'name' => 'Pengaturan',
                            'icon' => 'settings',
                            'subItems' => $pengaturan,
                        ],
                    ],
                ];
        }

        $layananPensiun = [];
        if ($user->hasPermissionTo('pengajuan-pensiun') && $id_status_pegawai != 3) { // untuk PPPK tidak ditampilkan
            array_push(
                $layananPensiun,
                [
                    'name' => 'Pengajuan Pensiun',
                    'path' => url('/pengajuan-pensiun'),
                ],
                [
                    'name' => 'Riwayat Pengajuan',
                    'path' => url('/pensiun/riwayat'),
                ]
            );
        }

        if ($user->hasPermissionTo('pemroses-pensiun-pd')) {
            array_push(
                $layananPensiun,
                [
                    'name' => 'Admin OPD',
                    'path' => url('admin-pensiun?role=AdminOpdPensiun'),
                ]
            );
        }

        if ($user->hasPermissionTo('pemroses-pensiun-bkd')) {
            array_push(
                $layananPensiun,
                [
                    'name' => 'Admin BKPSDM',
                    'path' => url('/admin-pensiun?role=AdminBkdPensiun'),
                ],
                [
                    'name' => 'Laporan Rekap Pensiun',
                    'path' => url('/rekap-pensiun'),
                ]
            );
        }

        if ($user->hasPermissionTo('spv-pensiun')) {
            array_push(
                $layananPensiun,
                [
                    'name' => 'Pengaturan Admin Pensiun',
                    'path' => url('/supervisi-pensiun'),
                ]
            );
        }

        if ($user->hasPermissionTo('dashboard-pensiun')) {
            array_push(
                $layananPensiun,
                [
                    'name' => 'Dashboard Pensiun',
                    'path' => url('/pensiun/dashboard'),
                ]
            );
        }


        $layananCuti = [];
        if ($user->hasPermissionTo('pengajuan-cuti')) {
            array_push(
                $layananCuti,
                [
                    'name' => 'Pengajuan Cuti',
                    'path' => url('/pengajuan-cuti'),
                ],
                [
                    'name' => 'Riwayat Pengajuan',
                    'path' => url('/cuti/riwayat-usulan'),
                ]
            );
        }

        if ($user->hasPermissionTo('proses-cuti')) {
            array_push(
                $layananCuti,
                [
                    'name' => 'Admin BKPSDM',
                    'path' => url('/proses-utama-cuti'),
                ],
                [
                    'name' => 'Laporan Rekap Cuti',
                    'path' => url('/rekap-hasil-cuti'),
                ]
            );
        }

        return array_filter(
            [
                [
                    'title' => 'MENU',
                    'items' => [

                        [
                            'name' => 'Dashboard',
                            'path' => url('/dashboard'),
                            'icon' => 'dashboard',
                        ],
                        [
                            'name' => 'Data Pribadi',
                            'path' => url('/profile'),
                            'icon' => 'account-circle',
                        ],
                    ],
                ],
                [
                    ...($integrasiSiasnMenu ? [
                        'title' => 'Integrasi SIASN',
                        'items' => $integrasiSiasnMenu,
                    ] : []),
                ],
                [
                    ...($cariPegawaiMenu || $laporanMenu || $rekapDataMenu ? [
                        'title' => 'Data Kepegawaian',
                        'items' => [
                            ...$rekapDataMenu,
                            ...$cariPegawaiMenu,
                            ...($laporanMenu ? [[
                                'name' => 'Laporan',
                                'icon' => 'assignment',
                                'subItems' => $laporanMenu,
                            ]] : []),
                        ],
                    ] : []),
                ],
                [
                    ...($layananPensiun || $layananMutasi || $layananCuti ? [
                        'title' => 'Layanan',
                        'items' => [
                            ...($layananPensiun ? [[
                                'name' => 'Layanan Pensiun',
                                'icon' => 'admin-panel',
                                'subItems' => $layananPensiun,
                            ]] : []),
                            ...($layananMutasi ? [[
                                'name' => 'Layanan Mutasi',
                                'icon' => 'moving-ministry',
                                'subItems' => $layananMutasi,
                            ]] : []),
                            ...($layananCuti ? [[
                                'name' => 'Layanan Cuti',
                                'icon' => 'calendar',
                                'subItems' => $layananCuti,
                            ]] : []),
                        ]
                    ] : []),
                ],
                $showed,
            ]
        );
    }

    // public static function getIconSvg($icon)
    // {
    //     return match ($icon) {
    //         default => '',
    //     };
    // }
}
