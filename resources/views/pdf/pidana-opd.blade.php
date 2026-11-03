<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 55px 47px 20px 47px;
        }

        body {
            /* font-family: "Times New Roman", serif; */
            font-family: "Arial", sans-serif;
            font-size: 12pt;
            line-height: 1.3;

        }

        .container {
            padding: 0px 10px 2px 10px;
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

        .ttd {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">

        {{-- HEADER --}}
        <!-- <div class="center">
        <img src="{{ public_path('image/HeaderSuratBkpsdm.png') }}" style="width:100%;">
    </div> -->
        <div style="height:80px; border-bottom:1px solid #000;"></div>

        {{-- TITLE --}}
        <div class="center" style="margin-top:20px;">
            <b>SURAT PERNYATAAN</b><br>
            <b>TIDAK SEDANG MENJALANI PROSES PIDANA ATAU PERNAH DIPIDANA PENJARA</b><br>
            <b>BERDASARKAN PUTUSAN PENGADILAN YANG TELAH BERKEKUATAN HUKUM TETAP</b>

        </div>
        <div style="margin-top:10px;margin-left:220px;">
            Nomor : {{ $nomor }}
        </div>

        </p>
        {{-- PEJABAT --}}
        <table>
            <tr>
                <td colspan='3'>Yang bertanda tangan di bawah ini :</td>
            </tr>
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
        <p style="margin-top:15px;">
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

        {{-- PARAGRAF --}}
        <p class="justify" style="margin-top:10px;">
            Tidak sedang menjalani proses pidana atau pernah dipidana penjara berdasarkan
            putusan pengadilan yang telah berkekuatan hukum tetap karena melakukan tindak
            pidana kejahatan jabatan atau tindak pidana kejahatan yang ada hubungannya
            dengan jabatan dan/atau pidana umum.
        </p>

        <p class="justify">
            Demikian surat pernyataan ini saya buat dengan sesungguhnya dengan mengingat
            sumpah jabatan dan apabila di kemudian hari ternyata isi surat pernyataan ini
            tidak benar yang mengakibatkan kerugian bagi negara maka saya bersedia
            menanggung kerugian negara sesuai dengan ketentuan peraturan perundang-undangan.
        </p>

        {{-- TTD --}}
        <div class="ttd">
            <table>
                <tr>
                    <td width="50%"></td>
                    <td class="left" width="50%">

                        Medan, <br>
                        @if($sarana==0)

                        {{ str_replace('Kota Medan', '', $pejabat_jabatan) }}
                        <br><br><br><br>
                        <b>{{ $pejabat_nama }}</b>
                        <br>
                        {{ $pejabat_pangkat }}<br>
                        NIP. {{ str_replace(' ','',$pejabat_nip) }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>