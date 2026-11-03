<table border="1" cellpadding="0" cellspacing="0" style="width: 100%; font-family: Arial, sans-serif; font-size: 10px;">

    <thead>
        <tr>
            <td colspan="60" style="font-size: 12px; font-weight: bold; text-align: center; padding: 6px;">
                BEZETTING PEGAWAI SKPD SELURUH ASN (CPNS/PNS DAN PPPK)
            </td>
        </tr>

        <tr>
            <th rowspan="4" style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                No.
            </th>
            <th rowspan="4"
                style='max-width: 300px; border: 1px solid black; font-weight: bold; text-align: center; padding: 4px; position: sticky; left: 0; background: white; z-index: 2;'>
                SKPD
            </th>
            <th x-bind:colspan="status_kepegawaian === '1' ? 3 : (status_kepegawaian === '2' ? 2 : 4)"
                style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                Jumlah Pegawai
            </th>
            <th colspan="7" style="border: 1px solid black; text-align: center; padding: 4px;">
                Pejabat Administrasi
            </th>
            <th x-bind:colspan="status_kepegawaian === '1' ? 18 : (
                status_kepegawaian === '2' ? 19 : (status_kepegawaian === '3' ? 36 : 36))"
                style="border: 1px solid black; text-align: center; padding: 4px;"
                x-text="status_kepegawaian === '1' ? 'Pangkat dan Golongan' : (status_kepegawaian === '3' ? 'Pangkat dan Golongan' : 'Golongan')">
            </th>
            <th colspan="10" style="border: 1px solid black; text-align: center; padding: 4px;">
                Tingkat Pendidikan
            </th>
        </tr>
        <tr>
            <th x-show="['1', '3'].includes(status_kepegawaian)" rowspan="3"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                CPNS
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)" rowspan="3"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                PNS
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="3"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                PPPK
            </th>
            <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                Jumlah
            </th>
            <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                II.a
            </th>
            <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                II.b
            </th>
            <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                III.a
            </th>
            <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                III.b
            </th>
            <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                IV.a
            </th>
            <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                IV.b
            </th>
            <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                Jumlah
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)" colspan="17"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                CPNS/PNS
            </th>

            <th x-show="['2', '3'].includes(status_kepegawaian)" colspan="18"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                PPPK
            </th>
            <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                Jumlah
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                SD
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                SMP
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                SMA
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                DI
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                DII
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                DIII
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                S1
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                S2
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                S3
            </th>
            <th rowspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                Jumlah
            </th>
        </tr>
        <tr>
            <th x-show="['1', '3'].includes(status_kepegawaian)" colspan="4"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                I
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)" colspan="4"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                II
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)" colspan="4"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                III
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)" colspan="5"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                IV
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px; width:50px'>
                Tanpa Golongan
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                I
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                II
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                III
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                IV
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                V
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                VI
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                VII
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                VIII
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                IX
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                X
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                XI
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                XII
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                XIII
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                XIV
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                XV
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                XVI
            </th>
            <th x-show="['2', '3'].includes(status_kepegawaian)" rowspan="2"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                XVII
            </th>
        </tr>
        <tr>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                a
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                b
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                c
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                d
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                a
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                b
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                c
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                d
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                a
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                b
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                c
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                d
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                a
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                b
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                c
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                d
            </th>
            <th x-show="['1', '3'].includes(status_kepegawaian)"
                style='border: 1px solid black; text-align: center; padding: 4px;'>
                e
            </th>
        </tr>
    </thead>

    <tbody>
        <template x-if="laporan.length > 0">
            <template x-for="(item, index) in laporan">
                <tr>
                    <td x-show="index !== (laporan.length - 1)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="index + 1"></td>
                    <td x-show="index !== (laporan.length - 1)"
                        style="border: 1px solid black; padding: 4px; text-align: left; position: sticky; left: 0; background: white; z-index: 2;"
                        x-text="item.skpd_name">
                    </td>
                    <td x-show="index === (laporan.length - 1)" colspan="2"
                        style="border: 1px solid black; padding: 4px; text-align: center;">
                        Total</td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_cpns">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_pns">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_pppk">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_pegawai">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.eselon_2a">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.eselon_2b">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.eselon_3a">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.eselon_3b">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.eselon_4a">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.eselon_4b">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_eselon">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_1a">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_1b">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_1c">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_1d">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_2a">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_2b">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_2c">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_2d">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_3a">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_3b">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_3c">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_3d">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_4a">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_4b">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_4c">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_4d">
                    </td>
                    <td x-show="['1', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.gol_4e">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;"
                        x-text="item.tanpa_golongan">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.I">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.II">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.III">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.IV">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.V">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.VI">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.VII">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.VIII">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.IX">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.X">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.XI">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.XII">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.XIII">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.XIV">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.XV">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.XVI">
                    </td>
                    <td x-show="['2', '3'].includes(status_kepegawaian)"
                        style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.XVII">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_gol">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_sd">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_smp">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_sma">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_d1">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_d2">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_d3">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_s1">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_s2">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_s3">
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;" x-text="item.jlh_sekolah">
                    </td>
                </tr>
            </template>
        </template>
    </tbody>
</table>
