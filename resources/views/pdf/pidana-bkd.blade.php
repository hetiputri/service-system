<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 55px 43px 20px 43px;
        }

        body {
            font-family: "Arial", sans-serif;
            font-size: 12pt;
            line-height: 1.2;
        }
    </style>
</head>

<body>

    <div style="padding:1px;">

        {{-- HEADER --}}
        <div style="text-align:center;">
            <img src="{{ public_path('image/HeaderSuratBkpsdm.png') }}" style="width:100%;">
        </div>

        {{-- TITLE --}}
        <div style="text-align:center; margin-top:5px;">
            <b>SURAT PERNYATAAN</b><br>
            <b>TIDAK SEDANG MENJALANI PROSES PIDANA ATAU PERNAH DIPIDANA PENJARA</b><br>
            <b>BERDASARKAN PUTUSAN PENGADILAN YANG TELAH BERKEKUATAN HUKUM TETAP</b>
        </div>
        <div style="text-align:left;margin-left:240px;margin-top:5px;">
            Nomor : {{ $nomor }}
        </div>


        {{-- PEJABAT --}}
        <table style="width:100%; margin-top:5px;">
            <tr>
                <td colspan='3' style='text-align:justify;'>Berdasarkan surat {{ $pejabat_opd }} Nomor {{$nomor_opd}} tanggal {{$tgl_opd}} tentang surat pernyataan tidak sedang menjalani proses pidana atau pernah dipidana
                    penjara berdasarkan putusan pengadilan yang telah berkekuatan hukum tetap, yang
                    bertanda tangan di bawah ini :</td>
            </tr>
            <tr>
                <td width="35%" style="padding-left:35px;">Nama</td>
                <td width="2%">:</td>
                <td> {{ $pejabat_nama }}</td>
            </tr>
            <tr>
                <td style="padding-left:35px;">NIP</td>
                <td width="2%">:</td>
                <td> {{ str_replace(' ','',$pejabat_nip) }}</td>
            </tr>
            <tr>
                <td style="padding-left:35px;">Pangkat/Golongan ruang</td>
                <td width="2%">:</td>
                <td> {{ $pejabat_pangkat }} ({{ $pejabat_golruang }})</td>
            </tr>
            <tr style='vertical-align: top;'>
                <td style="padding-left:35px;">Jabatan</td>
                <td width="2%">:</td>
                <td> {{ $pejabat_jabatan ?? '' }}</td>
            </tr>
        </table>

        {{-- PEGAWAI --}}
        <table style="width:100%;">
            <tr>
                <td colspan='3'>Dengan ini menyatakan dengan sesungguhnya, bahwa Pegawai Negeri Sipil,</td>
            </tr>
            <tr>
                <td width="35%" style="padding-left:35px;">Nama</td>
                <td width="2%">:</td>
                <td> {{ $nama }}</td>
            </tr>
            <tr>
                <td style="padding-left:35px;">NIP</td>
                <td width="2%">:</td>
                <td> {{ $nip }}</td>
            </tr>
            <tr>
                <td style="padding-left:35px;">Pangkat/Golongan ruang</td>
                <td width="2%">:</td>
                <td> {{ $pangkat }}</td>
            </tr>
            <tr>
                <td style="padding-left:35px;">Jabatan</td>
                <td width="2%">:</td>
                <td> {{ $jabatan }}</td>
            </tr>
            <tr style='vertical-align: top;'>
                <td style="padding-left:35px;">Instansi</td>
                <td width="2%">:</td>
                <td> {{ $instansi }}</td>
            </tr>
        </table>

        {{-- PARAGRAF --}}
        <p style="text-align: justify; margin-top:5px;">
            Tidak sedang menjalani proses pidana atau pernah dipidana penjara berdasarkan putusan
            pengadilan yang telah berkekuatan hukum tetap karena melakukan tindak pidana kejahatan
            jabatan atau tindak pidana kejahatan yang ada hubungannya dengan jabatan dan/atau
            pidana umum.
        </p>

        <p style="text-align: justify;">
            Demikian surat pernyataan ini saya buat dengan sesungguhnya dengan mengingat sumpah
            jabatan dan apabila di kemudian hari ternyata isi surat pernyataan ini tidak benar yang
            mengakibatkan kerugian bagi negara maka saya bersedia menanggung kerugian negara
            sesuai dengan ketentuan peraturan perundang-undangan.
        </p>

        {{-- TTD --}}
        <div style="text-align:right; margin-top:2px;margin-right:230px;">
            <p>Medan, </p>
            <!-- <p>Medan, {{ $tanggal }}</p> -->

        </div>

    </div>

</body>

</html>