<table border="1" cellpadding="0" cellspacing="0" style="width: 100%; font-family: Arial, sans-serif; font-size: 10px;">

    <thead>

        <tr>
            <td colspan="38" style="font-size: 12px; font-weight: bold; text-align: center; padding: 6px;">
                DAFTAR PEGAWAI DENGAN KRITERIA TERTENTU
            </td>
        </tr>

        <tr>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                No.
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Nama Pegawai
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                NIP
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Status Pegawai
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Jenis Pengadaan PPPK
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Pangkat/Gol Ruang
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                TMT Pangkat
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                No SK CPNS
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Tgl SK CPNS
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                TMT CPNS
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                No SK PNS
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Tgl SK PNS
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                TMT PNS
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                NIK
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                NPWP
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Tempat Lahir
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Tanggal Lahir
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Umur
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Jenis Kelamin
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Agama
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Telepon
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Tipe Jabatan
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Eselon
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Jabatan
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                TMT Jabatan
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Jenis Jenjang Jabatan
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Jenjang Jabatan
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                SKPD
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Pendidikan Terakhir
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Jurusan
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Nama Sekolah
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Agama
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Alamat
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Email
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Status Pernikahan
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Nama Suami/Istri
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Pekerjaan Pasangan
            </th>
            <th style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Tanggal Nikah
            </th>
        </tr>
    </thead>

    <tbody>
        <template x-if="laporan.length > 0">
            <template x-for="(item, index) in laporan">
                <tr>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="index + 1"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.nama_pegawai">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.nip"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"
                        x-text="item.status_kepegawaian"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"
                        x-text="item.jenis_pengadaan_pppk"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;"
                        x-text="`${item.pangkat} (${item.gol_ruang})`"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tmt_pangkat">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.no_sk_cpns">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tgl_sk_cpns">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tmt_cpns"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.no_sk_pns">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tgl_sk_pns">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tmpt_pns"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.nik"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.npwp"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tempat_lahir">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tanggal_lahir">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.umur"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jenis_kelamin">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.agama"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.telepon"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tipe_jabatan">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.eselon"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.jabatan"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tmt_jabatan">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jenis_jabfung">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"
                        x-text="item.jenjang_jabfung"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.skpd"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"
                        x-text="item.pendidikan_terakhir"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.jurusan"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.nama_sekolah">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.agama"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.alamat"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.email"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"
                        x-text="item.status_pernikahan"></td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;" x-text="item.nama_pasangan">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;"
                        x-text="item.pekerjaan_pasangan">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.tgl_nikah">
                    </td>

                </tr>
            </template>
        </template>
    </tbody>
</table>
