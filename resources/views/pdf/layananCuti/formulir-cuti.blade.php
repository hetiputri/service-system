<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 10mm 15mm 10mm 15mm;
        }

        body {
            font-family: "Arial", sans-serif;
            font-size: 10pt;
            line-height: 1;
        }

        td {
            padding: 2px;
        }
    </style>
</head>

<body>

    <div>
        <table style="border-collapse: collapse;width: 100%;">
            <tr>
                <td width='50%'>&nbsp;</td>
                <td width='50%'>Medan,</td>
            </tr>
            <tr>
                <td width='50%'>&nbsp;</td>
                <td width='50%'>Kepada</td>
            </tr>
            <tr>
                <td width='50%'>&nbsp;</td>
                <td width='50%'>Yth, Wali Kota Medan</td>
            </tr>
            <tr>
                <td width='50%'>&nbsp;</td>
                <td width='50%'>c.q. Kepala BKPSDM Kota Medan</td>
            </tr>
            <tr>
                <td width='50%'>&nbsp;</td>
                <td width='50%'>di</td>
            </tr>
            <tr>
                <td width='50%'>&nbsp;</td>
                <td width='50%'>Medan</td>
            </tr>
        </table>

        <h4 style="font-size: 12pt; text-align: center; font-weight: bold; margin-bottom: 5px;">
            FORMULIR PERMINTAAN DAN PEMBERIAN CUTI
        </h4>

        <table style="border: 1px solid black; border-collapse: collapse;width: 100%; margin-bottom: 15px;">
            <tr>
                <td colspan='4' style="padding-left: 10px;">I. DATA PEGAWAI</td>
            </tr>
            <tr>
                <td width='20%' style="border: 1px solid black; padding-left: 10px;">Nama</td>
                <td width='35%' style="border: 1px solid black; padding-left: 10px;">{{ $data_pegawai ? $data_pegawai->nama_pegawai : '' }}</td>
                <td width='20%' style="border: 1px solid black; padding-left: 10px;">NIP</td>
                <td width='25%' style="border: 1px solid black; padding-left: 10px;">{{ $data_pegawai ? str_replace(' ', '', $data_pegawai->nip) : '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='20%' style="border: 1px solid black; padding-left: 10px;">Jabatan</td>
                <td width='35%' style="border: 1px solid black; padding-left: 10px;">{{ $data_pegawai ? $data_pegawai->jabatan : '' }}</td>
                <td width='20%' style="border: 1px solid black; padding-left: 10px;">Masa Kerja</td>
                <td width='25%' style="border: 1px solid black; padding-left: 10px;">{{ $masa_kerja ? $masa_kerja['y'].' Tahun '.$masa_kerja['m'].' Bulan': '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='20%' style="border: 1px solid black; padding-left: 10px;">Unit Kerja</td>
                <td colspan='3' width='80%' style="border: 1px solid black; padding-left: 10px;">{{ $data_pegawai ? $data_pegawai->skpd : '' }}</td>
            </tr>
        </table>

        <table style="border: 1px solid black; border-collapse: collapse;width: 100%; margin-bottom: 15px;">
            <tr style="border: 1px solid black;">
                <td colspan='4' style="padding-left: 10px;">II. JENIS CUTI YANG DIAMBIL</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td colspan='4' style="padding-left: 10px;">{{ $cutiDetail ? $cutiDetail->nama_layanan : '' }}</td>
            </tr>
        </table>

        <table style="border: 1px solid black; border-collapse: collapse; width: 100%; margin-bottom: 15px;">
            <tr style="border: 1px solid black;">
                <td colspan='4' style="padding-left: 10px;">III. ALASAN CUTI</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td colspan='4' style="padding-left: 10px;">{{ $cutiDetail ? $cutiDetail->deskripsi : '' }}</td>
            </tr>
        </table>

        <table style="border: 1px solid black; border-collapse: collapse;width: 100%; margin-bottom: 15px;">
            <tr style="border: 1px solid black;">
                <td colspan='6' style="padding-left: 10px;">IV. LAMANYA CUTI</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='10%' style="text-align:center;border: 1px solid black;">SELAMA</td>
                <td width='20%' style="text-align:center;font-weight:bold;border: 1px solid black;">{{ $cutiDetail ? $cutiDetail->lama_cuti.' '.$cutiDetail->satuan_waktu : '' }}</td>
                <td width='20%' style="text-align:center;border: 1px solid black;">MULAI TANGGAL</td>
                <td width='20%' style="text-align:center;font-weight:bold;border: 1px solid black;">{{ $cutiDetail ? tglindonesia($cutiDetail->tgl_mulai) : '' }}</td>
                <td width='10%' style="text-align:center;border: 1px solid black;">S.D</td>
                <td width='20%' style="text-align:center;font-weight:bold;border: 1px solid black;">{{ $cutiDetail ? tglindonesia($cutiDetail->tgl_selesai) : '' }}</td>
            </tr>
        </table>
        @if($id_status_pegawai!=3)
        <table style="border: 1px solid black; border-collapse: collapse;width: 100%; margin-bottom: 15px;">
            <tr style="border: 1px solid black;">
                <td colspan='5' style="border: 1px solid black;padding-left: 10px;">V. CATATAN CUTI</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td colspan='3' style="border: 1px solid black;padding-left: 10px;">1. CUTI TAHUNAN</td>
                <td width='40%' style="border: 1px solid black;padding-left: 5px;">2. CUTI BESAR</td>
                <td width='20%' style="border: 1px solid black;">&nbsp;</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;text-align: center;">TAHUN</td>
                <td style="border: 1px solid black;text-align: center;">SISA</td>
                <td style="border: 1px solid black;text-align: center;">KETERANGAN</td>
                <td width='40%' style="border: 1px solid black;padding-left: 5px;">3. CUTI SAKIT</td>
                <td width='20%' style="border: 1px solid black;">&nbsp;</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;text-align: center;">N-2</td>
                <td style="border: 1px solid black;text-align: center;" class="text-center text-base">{{ $hitunganCuti['total_cuti_last_two_year'] == 12 ? '' : $hitunganCuti['total_cuti_last_two_year']}}</td>
                <td style="border: 1px solid black;text-align: center;" class="text-center text-base">&nbsp;</td>
                <td width='40%' style="border: 1px solid black;padding-left: 5px;">4. CUTI MELAHIRKAN</td>
                <td width='20%' style="border: 1px solid black;padding-left: 5px;">&nbsp;</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='10%' style="border: 1px solid black;text-align: center;">N-1</td>
                <td width='10%' style="border: 1px solid black;text-align: center;" class="text-center text-base">{{ $hitunganCuti['total_cuti_last_year'] == 12 ? '' : $hitunganCuti['total_cuti_last_year'] }}</td>
                <td width='10%' style="border: 1px solid black;text-align: center;" class="text-center text-base">&nbsp;</td>
                <td width='40%' style="border: 1px solid black;padding-left: 5px;">5. CUTI KARENA ALASAN PENTING</td>
                <td width='20%' style="border: 1px solid black;padding-left: 5px;">&nbsp;</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td style="border: 1px solid black;text-align: center;">N</td>
                <td style="border: 1px solid black;text-align: center;" class="text-center text-base">{{ $hitunganCuti['total_cuti_now'] == 12 ? '' : $hitunganCuti['total_cuti_now'] }}</td>
                <td style="border: 1px solid black;text-align: center;" class="text-center text-base">&nbsp;</td>
                <td width='40%' style="border: 1px solid black;padding-left: 5px;">6. CUTI DILUAR TANGGUNGAN NEGARA</td>
                <td width='20%' style="border: 1px solid black;padding-left: 5px;">&nbsp;</td>
            </tr>
        </table>
        @else
        <table style="border: 1px solid black; border-collapse: collapse;width: 100%; margin-bottom: 15px;">
            <tr style="border: 1px solid black;">
                <td colspan='2' style="border: 1px solid black;padding-left: 10px;">V. CATATAN CUTI***</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='40%' style="border: 1px solid black;padding-left: 10px;">1. CUTI TAHUNAN</td>
                <td width='60%' style="border: 1px solid black;padding-left: 5px;">&nbsp;</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='40%' style="border: 1px solid black;padding-left: 10px;">2. CUTI SAKIT</td>
                <td width='60%' style="border: 1px solid black;padding-left: 5px;">&nbsp;</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='40%' style="border: 1px solid black;padding-left: 10px;">3. CUTI MELAHIRKAN</td>
                <td width='60%' style="border: 1px solid black;padding-left: 5px;">&nbsp;</td>
            </tr>
        </table>
        @endif

        <table style="border: 1px solid black; border-collapse: collapse;width: 100%; margin-bottom: 15px;">
            <tr style="border: 1px solid black;">
                <td colspan='3' style="border: 1px solid black;padding-left: 10px;">VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='50%' style="border: 1px solid black;padding-left: 10px;">{{ $cutiDetail ? $cutiDetail->alamat : '' }}</td>
                <td width='20%' style="border: 1px solid black;padding-left: 10px;">TELPON</td>
                <td width='30%' style="border: 1px solid black;padding-left: 10px;">{{ $cutiDetail ? $cutiDetail->no_telepon : '' }}</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='50%' style="border: 1px solid black;" class="text-base p-2">&nbsp;</td>
                <td colspan='2' style="border: 1px solid black;text-align: center;">Hormat Saya,<br><br><br>({{nama_gelar($data_pegawai->nama_pegawai,$data_pegawai->gelar_depan,$data_pegawai->gelar_belakang)}})<br>NIP. {{str_replace(' ', '', $data_pegawai->nip) }}</td>
            </tr>
        </table>

        <table style="border: 1px solid black; border-collapse: collapse;width: 100%; margin-bottom: 15px;">
            <tr style="border: 1px solid black;">
                <td colspan='4' style='padding-left: 10px;'>VII. PERTIMBANGAN ATASAN LANGSUNG**</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='25%' style="border: 1px solid black;padding-left:10px;">DISETUJUI</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">PERUBAHAN****</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">DITANGGUHKAN****</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">TIDAK DISETUJUI****</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='25%' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td colspan='2' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
                @if($ttd=='basah')
                <td colspan='2' style="border: 1px solid black;padding-left:5px;text-align:center;">Atasan Langsung,<br><br><br>({{nama_gelar($atasan_data->nama_pegawai,$atasan_data->gelar_depan,$atasan_data->gelar_belakang)}})<br>NIP. {{str_replace(' ', '', $atasan_data->nip) }}</td>
                @else
                <td colspan='2' style="border: 1px solid black;padding-left:5px;text-align:center;">Atasan Langsung,<br><br>&nbsp;<br><br></td>
                @endif
            </tr>
        </table>

        <table style="border: 1px solid black; border-collapse: collapse;width: 100%; margin-bottom:8px;">
            <tr style="border: 1px solid black;">
                <td colspan='4' style='padding-left: 10px;'>VIII. PERTIMBANGAN PEJABAT BERWENANG YANG MEMBERIKAN CUTI**</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='25%' style="border: 1px solid black;padding-left:10px;">DISETUJUI</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">PERUBAHAN****</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">DITANGGUHKAN****</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">TIDAK DISETUJUI****</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td width='25%' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
                <td width='25%' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
            </tr>
            <tr style="border: 1px solid black;">
                <td colspan='2' style="border: 1px solid black;padding-left:5px;">&nbsp;</td>
                <!-- <td colspan='2' style="border: 1px solid black;padding-left:5px;text-align: center;">Pejabat yang berwenang memberikan cuti,<br><br><br>({{$kaopd_data->nama_pegawai}})<br>NIP. {{str_replace(' ', '', $kaopd_data->nip) }}</td> -->
                <td colspan='2' style="border: 1px solid black;padding-left:5px;text-align: center;">Pejabat yang berwenang memberikan cuti,<br><br><br>&nbsp;<br></td>
            </tr>
        </table>

        <div style="font-size: 8pt;">
            Catatan:<br>
            * Coret yang tidak perlu<br>
            * Coret yang tidak perlu<br>
            ** Pilih salah satu dengan memberi tanda centang (V)<br>
            *** Diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti<br>
            **** Diberi tanda centang dan alasannya<br>
            N = Cuti tahun berjalan<br>
            N-1 = Sisa Cuti 1 Tahun Sebelumnya<br>
            N-2 = Sisa Cuti Tahunan Sebelumnya<br>
        </div>

    </div>
</body>

</html>