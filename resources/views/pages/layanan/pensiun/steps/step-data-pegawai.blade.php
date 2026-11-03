<!-- STEP 2 -->
<div x-show="currentStep === 1 && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'" x-transition>
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <h2 class="text-xl font-semibold text-gray-800"> Data Pribadi </h2>
    </div>
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <div class="grid grid-cols-12 gap-6">
            {{-- FOTO --}}
            <div class="col-span-12 md:col-span-3 flex justify-center">
                <img src="https://{{ $filePribadi['pasfoto']}}" class="w-full h-full object-cover rounded-lg border">
            </div>

            <div class="col-span-12 md:col-span-9">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-5 text-sm">
                    {{-- Nama Lengkap --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Nama Lengkap</div>
                        <div class="font-semibold text-gray-800">
                            {{ nama_gelar($dataPribadi['nama_pegawai'],$dataPribadi['gelar_depan'],$dataPribadi['gelar_belakang']) }}
                        </div>
                    </div>

                    {{-- Agama --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Agama</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['agama'] ?? '-' }}
                        </div>
                    </div>

                    {{-- NIP --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">NIP</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['nip'] }}
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Alamat Rumah</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['alamat'] ? $dataPribadi['alamat'].' Kel. '.$dataPribadi['kelurahan'].' Kec. '.$dataPribadi['kecamatan'].' Kab./Kota '.$dataPribadi['kotakabupaten'].' Prov. '.$dataPribadi['provinsi'] : '-' }}
                        </div>
                    </div>

                    {{-- NIK --}}
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="text-gray-500 dark:text-gray-400">NIK</div>
                            <div class="font-semibold">
                                {{ $dataPribadi['ktp'] ?? '-' }}
                            </div>
                        </div>
                        <x-file-button :url="$filePribadi['ktp'] ?? null" theme="default" />

                    </div>

                    {{-- Golongan Darah --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Golongan Darah</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['golongan_darah'] ?? '-' }}
                        </div>
                    </div>

                    {{-- Tempat Tanggal Lahir --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Tempat / Tanggal Lahir</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['tempat_lahir'] }},
                            {{ $dataPribadi['tanggal_lahir'] }}
                        </div>
                    </div>

                    {{-- Nomor KARPEG --}}
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="text-gray-500 dark:text-gray-400">Nomor KARPEG</div>
                            <div class="font-semibold">
                                {{ $dataPribadi['no_karpeg'] ?? '-' }}
                            </div>
                        </div>
                        <x-file-button :url="$filePribadi['kartu_pegawai'] ?? null" theme="default" />

                    </div>

                    {{-- Pangkat --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Pangkat / Golongan</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['pangkat'] ?? '-' }} ( {{ $dataPribadi['gol_ruang'] ?? '-'}} )
                        </div>
                    </div>

                    {{-- Nomor BPJS --}}
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="text-gray-500 dark:text-gray-400">Nomor BPJS</div>
                            <div class="font-semibold">
                                {{ $dataPribadi['no_askes'] ?? '-' }}
                            </div>
                        </div>

                        <x-file-button :url="$filePribadi['bpjs'] ?? null" theme="default" />
                    </div>

                    {{-- Masa Kerja --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Masa Kerja</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['mk_tahun'] ?? '-' }} Tahun {{ $dataPribadi['mk_bulan'] ?? '-' }} Bulan
                        </div>
                    </div>

                    {{-- Nomor TASpen --}}
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="text-gray-500 dark:text-gray-400">Nomor TASPEN</div>
                            <div class="font-semibold">
                                {{ $dataPribadi['taspen'] ?? '-' }}
                            </div>
                        </div>

                        <x-file-button :url="$filePribadi['taspen'] ?? null" theme="default" />
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Jabatan</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['jabatan'] ?? '-' }}
                        </div>
                    </div>

                    {{-- Nomor NPWP --}}
                    <div class="flex items-center justify-between">

                        <div>
                            <div class="text-gray-500 dark:text-gray-400">Nomor NPWP</div>
                            <div class="font-semibold">
                                {{ $dataPribadi['npwp'] ?? '-' }}
                            </div>
                        </div>
                        <x-file-button :url="$filePribadi['npwp'] ?? null" theme="default" />


                    </div>

                    {{-- Unit Kerja --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Unit Kerja</div>
                        <div class="font-semibold">
                            {{ $dataPribadi['unit_kerja'] ?? '-' }}
                        </div>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">
                            Jenis Kelamin
                        </div>
                        <div class="font-semibold">
                            {{ $dataPribadi['jenis_kelamin'] ?? null }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Card Riwayat Kepangkatan -->
    <div class="bg-white dark:bg-gray-800 rounded-xl  shadow-sm border border-gray-200 dark:border-gray-700 p-5 mt-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Riwayat Kepangkatan
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi Riwayat Karier Pegawai
                </p>
            </div>

        </div>

        <!-- Table responsive -->
        <div class="overflow-x-auto">

            <x-table>

                <x-slot name="head">
                    <th class="p-2 text-left">No</th>
                    <th class="p-2 text-left">Pangkat</th>
                    <th class="p-2 text-left">TMT</th>
                    <th class="p-2 text-left">Pejabat</th>
                    <th class="p-2 text-left">Nomor SK</th>
                    <th class="p-2 text-left">Tanggal SK</th>
                    <th class="p-2 text-left">File</th>
                </x-slot>

                @forelse($riwayatPangkat as $i => $row)

                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">

                    <td class="p-2">{{ $i+1 }}</td>

                    <td class="p-2">
                        {{ $row['pangkat'] }} ({{ $row['gol_ruang'] }})
                    </td>

                    <td class="p-2">
                        <!-- {{ \Carbon\Carbon::parse($row['tmt_pangkat'])->format('d M Y') }} -->
                        {{ tglindonesia($row['tmt_pangkat']) }}
                    </td>

                    <td class="p-2">
                        {{ $row['pejabat_penetapan'] }}
                    </td>

                    <td class="p-2">
                        {{ $row['no_sk'] }}
                    </td>

                    <td class="p-2">
                        {{ tglindonesia($row['tgl_sk']) }}
                    </td>
                    <td class="p-2">
                        <x-file-button :url="$row['file_sk_pangkat'] ?? null" />
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse

            </x-table>
        </div>
    </div>

    <!-- Card Riwayat Jabatan & Mutasi -->
    <div class="bg-white dark:bg-gray-800 rounded-xl  shadow-sm border border-gray-200 dark:border-gray-700 p-5 mt-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Riwayat Jabatan & Mutasi
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi Riwayat Karier Pegawai
                </p>
            </div>
        </div>

        <!-- Table responsive -->
        <div class="overflow-x-auto">
            <x-table>

                <x-slot name="head">
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Jabatan</th>
                    <th class="p-3 text-left">Unit Kerja</th>
                    <th class="p-3 text-left">TMT Jabatan</th>
                    <th class="p-3 text-left">Pejabat</th>
                    <th class="p-3 text-left">Nomor SK</th>
                    <th class="p-3 text-left">Tanggal SK</th>
                    <th class="p-3 text-left">File</th>
                </x-slot>

                @forelse($riwayatJabatan as $i => $row)

                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                    <td class="p-3">
                        {{ $i+1 }}
                    </td>
                    <td class="p-3">

                        <div class="font-semibold">
                            {{ $row['jabatan'] }}
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ $row['tipe_jabatan'] }}
                        </div>

                        @if($row['eselon'])
                        <div class="text-xs text-gray-500">
                            Eselon {{ $row['eselon'] }}
                        </div>
                        @endif

                    </td>

                    <td class="p-3">
                        {{ $row['unit_kerja'] }}
                    </td>

                    <td class="p-3">

                        {{ $row['tmt_jabatan'] != '0000-00-00'
        ? tglindonesia($row['tmt_jabatan'])
        : '-' }}

                    </td>

                    <td class="p-3">
                        {{ $row['pejabat_penetapan'] }}
                    </td>

                    <td class="p-3">
                        {{ $row['no_sk'] }}
                    </td>

                    <td class="p-3">

                        {{ $row['tgl_sk'] != '0000-00-00'
        ? tglindonesia($row['tgl_sk'])
        : '-' }}

                    </td>
                    <td class="p-3">
                        <x-file-button :url="$row['file_sk_jabatan'] ?? null" />
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="8" class="text-center py-6 text-gray-500">
                        Tidak ada data riwayat jabatan
                    </td>

                </tr>

                @endforelse

            </x-table>

        </div>
    </div>

    <!-- Card Riwayat Penyesuaian Masa Kerja -->
    <div class="bg-white dark:bg-gray-800 rounded-xl  shadow-sm border border-gray-200 dark:border-gray-700 p-5 mt-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Riwayat Penyesuaian Masa Kerja
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi Riwayat Penyesuaian Masa Kerja Pegawai
                </p>
            </div>
        </div>

        <!-- Table responsive -->
        <div class="overflow-x-auto">
            <x-table>

                <x-slot name="head">
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Pengalaman</th>
                    <th class="p-3 text-left">TMT</th>
                    <th class="p-3 text-left">Nomor SK</th>
                    <th class="p-3 text-left">Tanggal SK</th>
                    <th class="p-3 text-left">Pejabat Penetapan</th>
                    <th class="p-3 text-left">File</th>
                </x-slot>

                @forelse($riwayatPMK as $i => $row)

                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                    <td class="p-3">
                        {{ $i+1 }}
                    </td>
                    <td class="p-3">
                        {{ $row['pengalaman'] }}
                    </td>
                    <td class="p-3">
                        {{ tglindonesia($row['tmt']) }}
                    </td>
                    <td class="p-3">
                        {{ $row['nomor_sk'] }}
                    </td>
                    <td class="p-3">
                        {{ tglindonesia($row['tanggal_sk']) }}
                    </td>
                    <td class="p-3">
                        {{ $row['pejabat_sk'] }}
                    </td>

                    <td class="p-3">
                        <x-file-button :url="$row['file_sk'] ?? null" />
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="8" class="text-center py-6 text-gray-500">
                        Tidak ada data riwayat Penyesuaian Masa Kerja
                    </td>

                </tr>

                @endforelse

            </x-table>

        </div>
    </div>

    <!-- Card Riwayat Data Pasangan -->
    <div class="bg-white dark:bg-gray-800 rounded-xl  shadow-sm border border-gray-200 dark:border-gray-700 p-5 mt-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                    Data Pasangan
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi Data Pasangan
                </p>
            </div>
        </div>

        <!-- Table responsive -->
        <div class="overflow-x-auto">
            <x-table>

                <x-slot name="head">
                    <th class="p-1 text-center">No.</th>
                    <th class="p-3 text-left">Nama Pasangan</th>
                    <th class="p-3 text-left">Status Hidup</th>
                    <th class="p-3 text-left">Tempat/Tanggal Lahir</th>
                    <th class="p-3 text-left">Tanggal Nikah</th>
                    <th class="p-3 text-left">Pekerjaan</th>
                    <th class="p-3 text-left">Status Nikah</th>
                    <th class="p-3 text-left">Kartu Keluarga</th>
                    <th class="p-3 text-left">Akta Nikah</th>
                    <th class="p-3 text-left">Akta Cerai/Meninggal</th>
                </x-slot>

                @forelse($pasangan as $i => $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                    <td class="p-3">
                        {{ $i+1 }}
                    </td>
                    <td class="p-3">
                        {{ $item['nama_pasangan'] }}
                    </td>

                    <td class="p-3">
                        {{ $item['status_hidup'] }}
                    </td>

                    <td class="p-3">
                        {{ $item['tempat_lahir'] ?? '-' }},
                        {{ isset($item['tgl_lahir'])
                        ? \Carbon\Carbon::parse($item['tgl_lahir'])->translatedFormat('d F Y')
                        : '-' }}
                    </td>

                    <td class="p-3">
                        {{ isset($item['tgl_nikah'])
                        ? \Carbon\Carbon::parse($item['tgl_nikah'])->translatedFormat('d F Y')
                        : '-' }}
                    </td>

                    <td class="p-3">
                        {{ $item['pekerjaan'] }}
                    </td>

                    <td class="p-3">
                        {{ $item['status_nikah'] }}
                    </td>

                    <td class="p-3">
                        <x-file-button :url="$item['kartu_keluarga'] ?? null" />
                    </td>

                    <td class="p-3">
                        <x-file-button :url="$item['akta_nikah'] ?? null" />
                    </td>

                    <td class="p-3">
                        <x-file-button :url="$item['akta_cerai'] ?? ($item['akta_meninggal'] ?? null)" />
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-500">
                        Tidak ada data pasangan
                    </td>

                </tr>

                @endforelse

            </x-table>

        </div>
    </div>

    <!-- Card Riwayat Data Anak -->
    <div class="bg-white dark:bg-gray-800 rounded-xl  shadow-sm border border-gray-200 dark:border-gray-700 p-5 mt-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                    Data Anak
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Informasi Data Anak
                </p>
            </div>
        </div>

        <!-- Table responsive -->
        <div class="overflow-x-auto">
            <x-table>

                <x-slot name="head">
                    <th class="p-1 text-center">No.</th>
                    <th class="p-3 text-left">Nama Anak</th>
                    <th class="p-3 text-left">Jenis Kelamin</th>
                    <th class="p-3 text-left">Tempat/Tanggal Lahir</th>
                    <th class="p-3 text-left">Pendidikan</th>
                    <th class="p-3 text-left">Pekerjaan</th>
                    <th class="p-3 text-left">Nama Ayah</th>
                    <th class="p-3 text-left">Nama Ibu</th>
                    <th class="p-3 text-left">Akta Lahir</th>
                </x-slot>

                @forelse($anak as $i => $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                    <td class="p-3">
                        {{ $i+1 }}
                    </td>
                    <td class="p-3">
                        {{ $item['nama_anak'] }}
                    </td>

                    <td class="p-3">
                        {{ $item['jenis_kelamin'] }}
                    </td>

                    <td class="p-3">
                        {{ $item['tempat_lahir'] ?? '-' }},
                        {{ isset($item['tgl_lahir'])
                        ? \Carbon\Carbon::parse($item['tgl_lahir'])->translatedFormat('d F Y')
                        : '-' }}
                    </td>

                    <td class="p-3">
                        {{ isset($item['pendidikan'])
                        ? $item['pendidikan']
                        : '-' }}
                    </td>

                    <td class="p-3">
                        {{ $item['pekerjaan'] }}
                    </td>

                    <td class="p-3">
                        {{ $item['nama_ayah'] }}
                    </td>

                    <td class="p-3">
                        {{ $item['nama_ibu'] }}
                    </td>

                    <td class="p-3">
                        <x-file-button :url="$item['akta_lahir'] ?? null" />
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-500">
                        Tidak ada data anak
                    </td>

                </tr>

                @endforelse

            </x-table>

        </div>
    </div>
    <!-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        {{-- ================= DATA PASANGAN ================= --}}
        @forelse($pasangan as $item)

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                        Data Pasangan
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Data Pasangan
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12 text-sm">

                {{-- Nama --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Nama Pasangan</span>
                    <div class="font-medium">
                        {{ $item['nama_pasangan'] ?? '-' }}
                    </div>
                </div>

                {{-- Status Hidup --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Status Hidup</span>
                    <div class="font-medium">
                        {{ $item['status_hidup'] ?? '-' }}
                    </div>
                </div>

                {{-- Tempat / Tanggal Lahir --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">
                        Tempat / Tanggal Lahir
                    </span>
                    <div class="font-medium">
                        {{ $item['tempat_lahir'] ?? '-' }},
                        {{ isset($item['tgl_lahir'])
                        ? \Carbon\Carbon::parse($item['tgl_lahir'])->translatedFormat('d F Y')
                        : '-' }}
                    </div>
                </div>

                {{-- Tanggal Nikah --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">
                        Tanggal Nikah
                    </span>
                    <div class="font-medium">
                        {{ isset($item['tgl_nikah'])
                        ? \Carbon\Carbon::parse($item['tgl_nikah'])->translatedFormat('d F Y')
                        : '-' }}
                    </div>
                </div>

                {{-- Pekerjaan --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">
                        Pekerjaan
                    </span>
                    <div class="font-medium">
                        {{ $item['pekerjaan'] ?? '-' }}
                    </div>
                </div>

                {{-- Status Nikah --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">
                        Status Pernikahan
                    </span>
                    <div class="font-medium">
                        {{ $item['status_nikah'] ?: 'Menikah' }}
                    </div>
                </div>

            </div>

        </div>

        @empty
        <div class="text-center text-gray-500 py-6">
            Tidak ada data pasangan
        </div>
        @endforelse
        <?php $no = 1; ?>
        @if(count($anak))
        @foreach($anak as $item)

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                        Data Anak
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Informasi Data Anak ke-{{ $no++ }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12 text-sm">

                {{-- Nama --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Nama Anak</span>
                    <div class="font-medium">
                        {{ $item['nama_anak'] ?? '-' }}
                    </div>
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Jenis Kelamin</span>
                    <div class="font-medium">
                        {{ $item['jenis_kelamin'] ?? '-' }}
                    </div>
                </div>

                {{-- Tempat / Tanggal Lahir --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">
                        Tempat / Tanggal Lahir
                    </span>
                    <div class="font-medium">
                        {{ $item['tempat_lahir'] ?? '-' }},
                        {{ isset($item['tgl_lahir'])
                        ? \Carbon\Carbon::parse($item['tgl_lahir'])->translatedFormat('d F Y')
                        : '-' }}
                    </div>
                </div>

                {{-- Pendidikan --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Pendidikan</span>
                    <div class="font-medium">
                        {{ $item['pendidikan'] ?? '-' }}
                    </div>
                </div>

                {{-- Pekerjaan --}}
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Pekerjaan</span>
                    <div class="font-medium">
                        {{ $item['pekerjaan'] ?? '-' }}
                    </div>
                </div>

            </div>

        </div>
        @endforeach
        @else
        <div class="text-center text-gray-500 py-6">
            Tidak ada data anak
        </div>
        @endif

    </div> -->

    <!-- VALIDASI DATA -->
    <div class="mt-6 border-t pt-4">
        <label class="flex items-start gap-3 cursor-pointer">
            <span class="text-lg text-gray-700 mb-6">
                Periksa dengan seksama Data Anda, karena kesalahan Data akan berdampak pada proses kepegawaian anda selanjutnya.
            </span>
        </label>

        <label class="flex items-start gap-3 cursor-pointer">

            <input
                type="checkbox"
                x-model="step2Checked"
                class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded">


            <span class="text-lg text-gray-700">
                Saya telah memeriksa seluruh <b>data kepribadian</b> saya dan sudah sesuai dengan data yang sebenarnya.
            </span>

        </label>

    </div>

    <div class="mt-8 flex justify-between">

        <button
            @click="prevStep()"
            class="px-6 py-2 bg-gray-300 rounded-xl">
            Kembali
        </button>

        <button
            @click="nextFromStep2()"
            :disabled="!step2Checked"
            :class="!step2Checked 
            ? 'bg-gray-400 cursor-not-allowed' 
            : 'bg-blue-600 hover:bg-blue-700'"
            class="px-6 py-2 text-white rounded-xl transition">
            Lanjut
        </button>

    </div>
</div>