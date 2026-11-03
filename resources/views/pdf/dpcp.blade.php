<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 3px;
        }

        .border {
            border: 1px solid black;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .top-right {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .top-right img {
            max-width: 120px;
            max-height: 140px;
        }
    </style>

</head>

<body>
    @if($pasfotoUrl)
    <div class="top-right">
        <img src="{{ $pasfotoUrl }}">
    </div>
    @endif
    <!-- HEADER -->

    <table>
        <tr>
            <td Width='50%'>
                <table>
                    <tr>
                        <td>PROVINSI</td>
                        <td>:</td>
                        <td>SUMATERA UTARA</td>
                    </tr>

                    <tr>
                        <td>KAB/KOTA</td>
                        <td>:</td>
                        <td>MEDAN</td>
                    </tr>

                    <tr>
                        <td>UNIT KERJA</td>
                        <td>:</td>
                        <td>{{ strtoupper($dataPribadi['data']['unit_kerja']) }}</td>
                    </tr>

                    <tr>
                        <td>PEMBAYARAN</td>
                        <td>:</td>
                        <td>MEDAN</td>
                    </tr>

                    <tr>
                        <td>BUP</td>
                        <td>:</td>
                        <td>{{ $bup }} TAHUN</td>
                    </tr>
                </table>
            </td>
            <td Width='50%'>
                <!-- <table width="100%">
                    <tr>
                        <td class="justify-right"></td>
                        <td class="text-right">
                            @if($pasfotoUrl)
                            {{ $pasfotoUrl }}
                            @endif
                        </td>
                    </tr>
                </table> -->
            </td>
        </tr>


    </table>

    <br>

    <div class="text-center bold">
        DATA PERORANGAN CALON PENERIMA PENSIUN (DPCP) <br>
        PEGAWAI NEGERI SIPIL
    </div>

    <br>

    <table>

        <tr>

            <!-- LEFT -->

            <td width="55%">

                <div class="bold">1. KETERANGAN PRIBADI</div>

                <table>

                    <tr>
                        <td width="30%">A. NAMA</td>
                        <td width="2%">:</td>
                        <td>{{ strtoupper(nama_gelar($dataPribadi['data']['nama_pegawai'], $dataPribadi['data']['gelar_depan'], $dataPribadi['data']['gelar_belakang'])) }}</td>
                    </tr>

                    <tr>
                        <td>B. NIP</td>
                        <td>:</td>
                        <td>{{ str_replace(' ','',$dataPribadi['data']['nip']) }}</td>
                    </tr>

                    <tr>
                        <td>C. TEMPAT / TGL LAHIR</td>
                        <td>:</td>
                        <td>
                            {{ strtoupper($dataPribadi['data']['tempat_lahir']) }},
                            {{ \Carbon\Carbon::parse($dataPribadi['data']['tanggal_lahir'])->format('d-m-Y') }}
                        </td>
                    </tr>

                    <tr>
                        <td>D. JABATAN</td>
                        <td>:</td>
                        <td>{{ strtoupper($dataPribadi['data']['jabatan'].' '.$dataPribadi['data']['jenjang_jabfung']) }}</td>
                    </tr>

                    <tr>
                        <td>E. PANGKAT / GOL.RUANG</td>
                        <td>:</td>
                        Penata III/c
                        III/d / Penata Tingkat I // 01-04-2017
                        <td>
                            {{ $dataPribadi['data']['gol_ruang'] }}/
                            {{ $dataPribadi['data']['pangkat'] }}/
                            {{ \Carbon\Carbon::parse($dataPribadi['data']['tmt_pangkat'])->format('d-m-Y') }}
                        </td>
                    </tr>

                    <tr>
                        <td>F. GAJI POKOK TERAKHIR</td>
                        <td>:</td>
                        <td>Rp. {{ number_format($gajiTerakhir->gaji, 0, ',', '.') }}</td>
                    </tr>

                    <tr>
                        <td>G. MASA KERJA GOLONGAN</td>
                        <td>:</td>
                        <td>
                            {{ $dataPribadi['data']['mk_tahun'] }} TAHUN
                            {{ $dataPribadi['data']['mk_bulan'] }} BULAN
                            PADA TGL {{ $tmtPensiun }}
                        </td>
                    </tr>

                    <tr>
                        <td>H. MASA KERJA PENSIUN</td>
                        <td>:</td>
                        <td>{{ $masaKerjaPensiun->y }} TAHUN
                            {{ $masaKerjaPensiun->m }} BULAN
                        </td>
                    </tr>

                    <tr>
                        <td>I. MASA KERJA SEBELUM PNS</td>
                        <td>:</td>
                        <td>
                            {{ $masaKerjaPmk ? $masaKerjaPmk->mkg_tahun.' TAHUN '.$masaKerjaPmk->mkg_bulan.' BULAN' : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td>J. PENDIDIKAN PERTAMA</td>
                        <td>:</td>
                        <td>{{$dataCPNS['tingkat_pendidikan_cpns'] ?? '-'}} {{ $dataCPNS['tanggal_ijazah'] ? \Carbon\Carbon::parse($dataCPNS['tanggal_ijazah'])->format('Y') : '' }}</td>
                    </tr>

                    <tr>
                        <td>K. MULAI MASUK PNS</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($dataPNS['TMTPNS'])->format('d-m-Y') ?? '-' }}</td>
                    </tr>

                </table>


                <br>

                <div class="bold">2. KETERANGAN KELUARGA</div>
                <div class="ml-5">A. SUAMI / ISTRI</div>
                <table class="border ml-5">

                    <tr class="border bold text-center">
                        <td class="border">NAMA</td>
                        <td class="border">TGL LAHIR</td>
                        <td class="border">KAWIN TGL</td>
                        <td class="border">ISTRI/SUAMI KE</td>
                    </tr>

                    @foreach(data_get($riwayatKeluarga, 'data-pasangan', []) as $p)

                    <tr>
                        <td class="border">{{ strtoupper($p['nama_pasangan']) ?? '-' }}</td>

                        <td class="border text-center">
                            {{ isset($p['tgl_lahir']) ? \Carbon\Carbon::parse($p['tgl_lahir'])->format('d-m-Y') : '-' }}
                        </td>

                        <td class="border text-center">
                            {{ isset($p['tgl_nikah']) ? \Carbon\Carbon::parse($p['tgl_nikah'])->format('d-m-Y') : '-' }}
                        </td>

                        <td class="border text-center">1</td>
                    </tr>

                    @endforeach

                </table>

            </td>


            <!-- RIGHT -->

            <td width="45%">

                <div class="bold">B. ANAK-ANAK</div>

                <table class="border">

                    <tr class="bold text-center">
                        <td class="border">NAMA</td>
                        <td class="border">TGL LAHIR</td>
                        <td class="border">STATUS</td>
                        <td class="border">NAMA AYAH / IBU</td>
                    </tr>

                    @foreach(data_get($riwayatKeluarga, 'data-anak', []) as $anak)

                    <tr>

                        <td class="border">
                            {{ strtoupper($anak['nama_anak']) ?? '-' }}
                        </td>

                        <td class="border">
                            {{ isset($anak['tgl_lahir']) 
    ? \Carbon\Carbon::parse($anak['tgl_lahir'])->format('d-m-Y') 
    : '-' }}
                        </td>

                        <td class="border">
                            KANDUNG
                        </td>

                        <td class="border">
                            {{ strtoupper($anak['nama_ayah']) ?? '' }}/{{ strtoupper($anak['nama_ibu']) ?? ''}}
                        </td>

                    </tr>

                    @endforeach

                </table>

                <br>

                <div style="font-size:10px">
                    <table width='100%'>
                        <tr>
                            <td width='3%'>3.</td>
                            <td>ALAMAT SESUDAH PENSIUN : {{ strtoupper($alamat) }}</td>
                        </tr>
                        <tr>
                            <td width='3%'>4.</td>
                            <td style='text-align:justify'>DENGAN INI SAYA MENYATAKAN AKAN MENGEMBALIKAN SELURUH BARANG INVENTARIS
                                MILIK NEGARA SETELAH DIBERHENTIKAN DENGAN HORMAT SEBAGAI PEGAWAI NEGERI SIPIL
                                DAN APABILA SAYA TIDAK MEMATUHI SAYA BERSEDIA DIAMBIL TINDAKAN SESUAI
                                PERATURAN PERUNDANG-UNDANGAN YANG BERLAKU</td>
                        </tr>
                        <tr>
                            <td width='3%'>5.</td>
                            <td>DEMIKIAN DATA INI DIBUAT DENGAN SEBENARNYA</td>
                        </tr>
                    </table>
                </div>

                <br>

                <div style="text-align:center;margin-top:50px;margin-left:200px">

                    TANGGAL {{ strtoupper(tglindonesia(now())) }}

                    <br>
                    CALON PENERIMA PENSIUN

                    <br><br>
                    @if($tte==0 && $dpcp && $dpcp->signature_file)
                    <img
                        src="{{ public_path('storage/ttd/'.$dpcp->signature_file) }}"
                        width="120">

                    @endif

                    <br>
                    {{ strtoupper($penandatangan) }}<br>
                    {{ $nip }}
                </div>

            </td>

        </tr>

    </table>

</body>

</html>