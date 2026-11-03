<style>
    @page {
        size: A4 landscape;
        margin: 5mm;
    }

    body {
        font-size: {{ $status_kepegawaian === '3' ? '5px' : '8px' }}
    }
</style>
<div>
    {{-- @php
        dd($data);
    @endphp --}}
    <table cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif;">
        <thead>
            <tr>
                <th colspan={{ in_array($status_kepegawaian, ['1', '2']) ? '40' : '59' }}
                    style="font-size: 12px; font-weight: bold; text-align: center; padding: 6px;">
                    BEZETTING PEGAWAI SKPD SELURUH ASN (CPNS/PNS DAN PPPK)
                </th>
            </tr>
            <tr>
                <th rowspan="{{ $status_kepegawaian === '2' ? 3 : 4 }}"
                    style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                    No.
                </th>
                <th rowspan="{{ $status_kepegawaian === '2' ? 3 : 4 }}"
                    style='max-width: 300px; border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                    SKPD
                </th>
                <th colspan="{{ $status_kepegawaian === '1' ? 3 : ($status_kepegawaian === '2' ? 2 : 4) }}"
                    style='border: 1px solid black; font-weight: bold; text-align: center; padding: 4px;'>
                    Jumlah Pegawai
                </th>
                <th colspan="7" style="border: 1px solid black; text-align: center; padding: 4px;">
                    Pejabat Administrasi
                </th>
                <th colspan="{{ $status_kepegawaian === '1' ? 18 : ($status_kepegawaian === '2' ? 19 : 36) }}"
                    style="border: 1px solid black; text-align: center; padding: 4px;">
                    {{ in_array($status_kepegawaian, ['1', '3']) ? 'Pangkat dan Golongan' : 'Golongan' }}
                </th>
                <th colspan="10" style="border: 1px solid black; text-align: center; padding: 4px;">
                    Tingkat Pendidikan
                </th>
            </tr>
            <tr>
                @if (in_array($status_kepegawaian, ['1', '3']))
                    <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                        CPNS
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['1', '3']))
                    <th rowspan="3" style='border: 1px solid black; text-align: center; padding: 4px;'>
                        PNS
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 2 : 3 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        PPPK
                    </th>
                @endif
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    Jumlah
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    II.a
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    II.b
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    III.a
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    III.b
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    IV.a
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    IV.b
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    Jumlah
                </th>
                @if (in_array($status_kepegawaian, ['1', '3']))
                    <th colspan="17" style='border: 1px solid black; text-align: center; padding: 4px;'>
                        CPNS/PNS
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th colspan="18" style='border: 1px solid black; text-align: center; padding: 4px;'>
                        PPPK
                    </th>
                @endif
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    Jumlah
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    SD
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    SMP
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    SMA
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    DI
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    DII
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    DIII
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    S1
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    S2
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    S3
                </th>
                <th rowspan={{ $status_kepegawaian === '2' ? '2' : '3' }}
                    style='border: 1px solid black; text-align: center; padding: 4px;'>
                    Jumlah
                </th>
            </tr>
            <tr>
                @if (in_array($status_kepegawaian, ['1', '3']))
                    <th colspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                        I
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['1', '3']))
                    <th colspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                        II
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['1', '3']))
                    <th colspan="4" style='border: 1px solid black; text-align: center; padding: 4px;'>
                        III
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['1', '3']))
                    <th colspan="5" style='border: 1px solid black; text-align: center; padding: 4px;'>
                        IV
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px; width:25px'>
                        Tanpa Golongan
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        I
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        II
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        III
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        IV
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        V
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        VI
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        VII
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        VIII
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        IX
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        X
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        XI
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        XII
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        XIII
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        XIV
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        XV
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        XVI
                    </th>
                @endif
                @if (in_array($status_kepegawaian, ['2', '3']))
                    <th rowspan="{{ $status_kepegawaian === '2' ? 1 : 2 }}"
                        style='border: 1px solid black; text-align: center; padding: 4px;'>
                        XVII
                    </th>
                @endif
            </tr>
            @if (in_array($status_kepegawaian, ['1', '3']))
                <tr>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        a
                    </th>


                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        b
                    </th>


                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        c
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        d
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        a
                    </th>


                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        b
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        c
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        d
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        a
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        b
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        c
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        d
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        a
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        b
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        c
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        d
                    </th>

                    <th style='border: 1px solid black; text-align: center; padding: 4px;'>
                        e
                    </th>

                </tr>
            @endif
        </thead>


        <tbody>
            @forelse($data as $lap)
                <tr>
                    @if ($loop->iteration !== count($data))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">
                            {{ $loop->iteration }}</td>
                        <td style="border: 1px solid black; padding: 4px; text-align: left;">
                            {{ $lap['skpd_name'] }}
                        </td>
                    @endif
                    @if ($loop->iteration === count($data))
                        <td colspan="2" style="border: 1px solid black; padding: 4px; text-align: center; ">
                            Total</td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">
                            {{ $lap['jlh_cpns'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">
                            {{ $lap['jlh_pns'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">
                            {{ $lap['jlh_pppk'] }}
                        </td>
                    @endif
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">
                        {{ $lap['jlh_pegawai'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['eselon_2a'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['eselon_2b'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['eselon_3a'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['eselon_3b'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['eselon_4a'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['eselon_4b'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jlh_eselon'] }}
                    </td>
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['gol_1a'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_1b'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_1c'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_1d'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_2a'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_2b'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_2c'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_2d'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_3a'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_3b'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_3c'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_3d'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_4a'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_4b'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_4c'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_4d'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['1', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['gol_4e'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">
                            {{ $lap['tanpa_golongan'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['I'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['II'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['III'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['IV'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['V'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['VI'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['VII'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['VIII'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['IX'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['X'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['XI'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['XII'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['XIII'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['XIV'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['XV'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['XVI'] }}
                        </td>
                    @endif
                    @if (in_array($status_kepegawaian, ['2', '3']))
                        <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['XVII'] }}
                        </td>
                    @endif
                    <td style="border: 1px solid black; padding: 4px; text-align: center;"> {{ $lap['jlh_gol'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jlh_sd'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jlh_smp'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jlh_sma'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jlh_d1'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">
                        {{ $lap['jlh_d2'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jlh_d3'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jlh_s1'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jlh_s2'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">{{ $lap['jlh_s3'] }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px; text-align: center;">
                        {{ $lap['jlh_sekolah'] }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="39" style="border: 1px solid black; padding: 4px; text-align: center;">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
