<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            /* font-family: "Times New Roman", serif; */
            font-family: "Arial", sans-serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        .container {
            padding: 10px;
        }

        .center {
            text-align: center;
        }

        .justify {
            text-align: justify;
        }

        table {
            width: 100%;
            margin-top: 5px;
        }

        td {
            vertical-align: top;
            padding: 2px 0;
        }
    </style>
</head>

<body>
    <div class="container">

        {{-- HEADER KOSONG (BIAR OPD ISI SENDIRI) --}}
        <div style="height:80px; border-bottom:1px solid #000;"></div>

        {{-- TITLE --}}
        <div class="center" style="margin-top:20px;">
            <b>SURAT PERNYATAAN</b><br>
            <b>TIDAK PERNAH DIJATUHI HUKUMAN DISIPLIN TINGKAT SEDANG/ BERAT</b>
        </div>

        <div style="margin-top:10px;margin-left:240px;">
            Nomor : {{ $nomor }}
        </div>

        {{-- PEMBUKA --}}
        <p class="justify" style="margin-top:10px;">
            Yang bertanda tangan di bawah ini :
        </p>

        {{-- PEJABAT --}}
        <table>
            <tr>
                <td width="35%">Nama</td>
                <td width="2%">:</td>
                <td>{{ $pejabat_nama }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $pejabat_nip }}</td>
            </tr>
            <tr>
                <td>Pangkat/Gol. Ruang</td>
                <td>:</td>
                <td>{{ $pejabat_pangkat }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $pejabat_jabatan }}</td>
            </tr>
            <!-- <tr>
                <td>Instansi</td>
                <td>:</td>
                <td>{{ $instansi_induk }}</td>
            </tr> -->
        </table>

        {{-- ISI --}}
        <p style="margin-top:10px;">
            Dengan ini menyatakan dengan sesungguhnya, bahwa Pegawai Negeri Sipil,
        </p>

        {{-- PEGAWAI --}}
        <table>
            <tr>
                <td width="35%">Nama</td>
                <td width="2%">:</td>
                <td>{{ $nama }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $nip }}</td>
            </tr>
            <tr>
                <td>Pangkat/Gol. Ruang</td>
                <td>:</td>
                <td>{{ $pangkat }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $jabatan }}</td>
            </tr>
            <tr>
                <td>Instansi</td>
                <td>:</td>
                <td>{{ $instansi_induk }}</td>
            </tr>
        </table>

        {{-- PARAGRAF UTAMA --}}
        <p class="justify" style="margin-top:10px;">
            Dalam satu tahun terakhir tidak pernah dijatuhi hukuman disiplin tingkat sedang/berat.
        </p>

        {{-- PENUTUP --}}
        <p class="justify">
            Demikian surat pernyataan ini saya buat dengan sesungguhnya dengan mengingat
            sumpah jabatan dan apabila di kemudian hari ternyata isi surat pernyataan ini
            tidak benar yang mengakibatkan kerugian bagi negara maka saya bersedia
            menanggung kerugian negara sesuai dengan ketentuan peraturan perundang-undangan.
        </p>

        {{-- TTD --}}
        <table style="width:100%; margin-top:10px;">
            <tr>
                <td width="50%"></td>
                <td class="left" width="50%">
                    Medan, <br>

                    @if($sarana==0)

                    {{ str_replace('Kota Medan', '', $pejabat_jabatan) }}
                    <br><br><br><br>
                    <b>{{ $pejabat_nama }}</b><br>
                    {{ $pejabat_pangkat }}<br>
                    NIP. {{ str_replace(' ','',$pejabat_nip) }}
                    @endif
                </td>
            </tr>
        </table>

    </div>
</body>

</html>