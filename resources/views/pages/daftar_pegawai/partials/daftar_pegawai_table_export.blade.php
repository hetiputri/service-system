<style>
    @page {

        size: 330mm 215mm;
        margin: 5mm;
    }

    body {
        font-size: 4px;
    }
</style>
<div>
    <table cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif;">

        <thead>

            <tr>
                <th colspan="38"
                    style="font-size: {{ $type === 'excel' ? '12px' : '6px' }}; font-weight: bold; text-align: center; padding: 6px;">
                    DAFTAR PEGAWAI DENGAN KRITERIA TERTENTU
                </th>
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
            @forelse($data as $lap)
                <tr>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $loop->iteration }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;"> {{ $lap['nama_pegawai'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['nip'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">
                        {{ $lap['status_kepegawaian'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">
                        {{ $lap['jenis_pengadaan_pppk'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">
                        {{ $lap['pangkat'] . ' (' . $lap['gol_ruang'] . ')' }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['tmt_pangkat'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;"> {{ $lap['no_sk_cpns'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['tgl_sk_cpns'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['tmt_cpns'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;"> {{ $lap['no_sk_pns'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['tgl_sk_pns'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['tmt_pns'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">
                        {{ $lap['nik'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $lap['npwp'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['tempat_lahir'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['tanggal_lahir'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['umur'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jenis_kelamin'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['agama'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['telepon'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['tipe_jabatan'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['eselon'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $lap['jabatan'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['tmt_jabatan'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jenis_jabfung'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">
                        {{ $lap['jenjang_jabfung'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $lap['skpd'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">
                        {{ $lap['pendidikan_terakhir'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $lap['jurusan'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $lap['nama_sekolah'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['agama'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $lap['alamat'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $lap['email'] }}</td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">
                        {{ $lap['status_pernikahan'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">{{ $lap['nama_pasangan'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: left;">
                        {{ $lap['pekerjaan_pasangan'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['tgl_nikah'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="38" style="border: 1px solid black; padding: 4px; text-align: center;">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
