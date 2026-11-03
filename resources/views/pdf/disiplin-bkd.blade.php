<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: "Arial", sans-serif;
            font-size: 12pt;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div style="padding: 20px;">

        {{-- HEADER --}}
        <div style="display: flex; justify-content: center;">

            <img src="{{ public_path('image/HeaderSuratBkpsdm.png') }}">


        </div>

        {{-- JUDUL --}}
        <div style="text-align:center;">
            <h4>SURAT PERNYATAAN<br>
                TIDAK PERNAH DIJATUHI HUKUMAN DISIPLIN TINGKAT SEDANG/BERAT</h4>
        </div>
        <div style="margin-left:240px;margin-top:7px;">
            NOMOR : {{ $nomor }}
        </div>

        <div style="text-align:center;">
            <table style="margin-top:5px;">
                <tr>
                    <td colspan='3' style='text-align:justify;'>Berdasarkan surat {{ $pejabat_opd }} Nomor {{$nomor_opd}} tanggal {{$tgl_opd}} tentang surat pernyataan tidak pernah dijatuhi hukuman disiplin tingkat sedang/berat, yang bertanda tangan di bawah ini :</td>
                </tr>
                <tr style="vertical-align:top;">
                    <td width='32%' style="padding-left:20px;">Nama</td>
                    <td width='2%'>:</td>
                    <td>{{ $pejabat_nama }}</td>
                </tr>
                <tr>
                    <td style="padding-left:20px;">NIP</td>
                    <td>:</td>
                    <td>{{ str_replace(' ','',$pejabat_nip) }}</td>
                </tr>
                <tr>
                    <td style="padding-left:20px;">Pangkat/Golongan ruang</td>
                    <td>:</td>
                    <td>{{ $pejabat_pangkat }} ({{$pejabat_golruang}})</td>
                </tr>
                <tr style="vertical-align:top;">
                    <td style="padding-left:20px;">Jabatan</td>
                    <td>:</td>
                    <td>{{ $pejabat_jabatan }}</td>
                </tr>
                <tr style="vertical-align:top;">
                    <td colspan='3'>Dengan ini menyatakan dengan sesungguhnya, bahwa Pegawai Negeri Sipil,</td>
                </tr>
            </table>

            <table>
                <tr>
                    <td width='32%' style="padding-left:20px;">Nama</td>
                    <td width='2%'>:</td>
                    <td>{{ $nama }}</td>
                </tr>
                <tr>
                    <td style="padding-left:20px;">NIP</td>
                    <td>:</td>
                    <td>{{ str_replace(' ','',$nip) }}</td>
                </tr>

                <tr>
                    <td style="padding-left:20px;">Pangkat/Golongan ruang</td>
                    <td>:</td>
                    <td>{{$pangkat}}</td>
                </tr>
                <tr style="vertical-align:top;">
                    <td style="padding-left:20px;">Jabatan</td>
                    <td>:</td>
                    <td>{{ $jabatan }}</td>
                </tr>
                <tr style="vertical-align:top;">
                    <td style="padding-left:20px;">Instansi</td>
                    <td>:</td>
                    <td>{{ $instansi }}</td>
                </tr>
                <tr style="vertical-align:top;">
                    <td colspan='3'>Dalam satu tahun terakhir tidak pernah dijatuhi hukuman disiplin tingkat sedang/berat.</td>
                </tr>
            </table>
        </div>


        <p style="text-align: justify; line-height: 1.5;">
            Demikian surat pernyataan ini saya buat dengan sesungguhnya dengan mengingat sumpah
            jabatan dan apabila di kemudian hari ternyata isi surat pernyataan ini tidak benar yang
            mengakibatkan kerugian bagi negara maka saya bersedia menanggung kerugian tersebut.
        </p>

        <div style="text-align:right; margin-top:10px;margin-right:240px;">
            <p>Medan,</p>

        </div>

    </div>

</body>

</html>