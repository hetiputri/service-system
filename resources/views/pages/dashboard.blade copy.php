@php
use App\Helpers\SimpegHelper;

@endphp

@extends('layouts.app') 
@section('title', 'Dashboard') 

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Dashboard SIMPEG
        </h1>
        <p class="text-sm text-gray-500 text-bold dark:text-gray-400">
            Selamat datang kembali, {{ $dataPribadi['nama_pegawai'] ?? 'Pegawai' }}
        </p>
    </div>

    {{-- Card Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <p class="text-sm text-strong text-gray-500">Total Pegawai</p>
            <h2 class="text-2xl font-bold text-blue-600 mt-2">
                {{ $totalPegawai ?? 0 }}
            </h2>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Pegawai Aktif</p>
            <h2 class="text-2xl font-bold text-green-600 mt-2">
                {{ $dataPribadiAktif ?? 0 }}
            </h2>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Cuti</p>
            <h2 class="text-2xl font-bold text-yellow-600 mt-2">
                {{ $dataPribadiCuti ?? 0 }}
            </h2>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Pensiun</p>
            <h2 class="text-2xl font-bold text-red-600 mt-2">
                {{ $dataPribadiPensiun ?? 0 }}
            </h2>
        </div>

    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">



    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-800 dark:text-white">
            Kalender Pegawai & Pengajuan Cuti
        </h3>
    </div>

    <div id="calendar"></div>
</div>
    {{-- Pengajuan Cuti Terbaru --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <!-- <h3 class="font-semibold mb-4">Pengajuan Cuti Terbaru</h3>

        @forelse($cutiTerbaru ?? [] as $cuti)
            <div class="border-b py-2 text-sm">
                <div class="font-medium">{{ $cuti['nama_pegawai'] }}</div>
                <div class="text-gray-500">
                    {{ $cuti['jenis_cuti'] }} | {{ $cuti['tanggal'] }}
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400">Belum ada pengajuan</p>
        @endforelse -->
        {{-- Profile --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6  col-span-2">
            <div class="flex items-center gap-4">
                <img src="https://{{ $filePribadi['pasfoto'] ?? asset('images/default-user.png') }}"
                     class="w-20 h-20 rounded-xl object-cover border">

                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-white">
                        {{ $dataPribadi['nama_pegawai']?? '-' }}
                    </h3>
                    <p class="text-sm text-gray-500">
                         {{ $dataPribadi['nip'] ?? '-' }}
                    </p>
                </div>
                <button
    
                type="button"
                
                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg">
                
                <span>Selengkapnya</span>
            </button>
            </div>

            <div class="mt-4 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                <p>Jabatan: {{ $dataPribadi['jabatan'] ?? '-' }}</p>
                <p>Unit Kerja: {{ $dataPribadi['unit_kerja'] ?? '-' }}</p>
                <!-- <p>Status: {{ $dataPribadi['status'] ?? '-' }}</p> -->
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl mt-5 shadow p-6 col-span-2">
            <h3 class="font-semibold text-gray-800 dark:text-white mb-4">
                Informasi Sistem
            </h3>

            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <div class="flex justify-between">
                    <span>Versi Aplikasi</span>
                    <span class="font-medium">SIMPEG v1.0</span>
                </div>
                <div class="flex justify-between">
                    <span>Tanggal Login</span>
                    <span class="font-medium">{{ now()->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Role</span>
                    <span class="font-medium">{{ auth()->user()->role->name ?? 'User' }}</span>
                </div>
            </div>
        </div>
    </div>

</div>



    

    {{-- Tabel Pegawai Terbaru --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800 dark:text-white">
                Pegawai Terbaru
            </h3>
            <a href="{{ '/profile' }}"
               class="text-sm text-blue-600 hover:underline">
               Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b dark:border-gray-700">
                        <th class="py-2 text-left">No</th>
                        <th class="py-2 text-left">Nama</th>
                        <th class="py-2 text-left">NIP</th>
                        <th class="py-2 text-left">Jabatan</th>
                        <th class="py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataPribadiTerbaru ?? [] as $i => $p)
                    <tr class="border-b dark:border-gray-700">
                        <td class="py-2">{{ $i+1 }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->nip }}</td>
                        <td>{{ $p->jabatan }}</td>
                        <td>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                {{ $p->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-400">
                            Belum ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('opdChart');

    const data = {
        labels: {!! json_encode(collect($pegawaiPerOpd ?? [])->pluck('opd')) !!},
        datasets: [{
            label: 'Jumlah Pegawai',
            data: {!! json_encode(collect($pegawaiPerOpd ?? [])->pluck('total')) !!},
            backgroundColor: 'rgba(59, 130, 246, 0.6)',
            borderRadius: 8
        }]
    };

    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

});
document.addEventListener('DOMContentLoaded', function () {

const calendarEl = document.getElementById('calendar');

const cutiEvents = {!! json_encode(collect($cutiEvents ?? [])->map(function($c){
    return [
        'title' => $c['nama_pegawai'].' - '.$c['jenis_cuti'],
        'start' => $c['tanggal_mulai'],
        'end'   => \Carbon\Carbon::parse($c['tanggal_selesai'])->addDay()->format('Y-m-d'),
        'color' => match($c['status']) {
            'Disetujui' => '#16a34a',
            'Pending'   => '#f59e0b',
            'Ditolak'   => '#dc2626',
            default     => '#3b82f6'
        }
    ];
})) !!};

const holidayEvents = {!! json_encode(collect($holidayEvents ?? [])->map(function($h){
    return [
        'title' => 'LIBUR: '.$h->title,
        'start' => $h->start_date,
        'end'   => $h->end_date 
                    ? \Carbon\Carbon::parse($h->end_date)->addDay()->format('Y-m-d')
                    : null,
        'color' => '#2563eb',
        'display' => 'block'
    ];
})) !!};

const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',

    height: 'auto',          // tinggi mengikuti konten
    contentHeight: 'auto',   // tidak pakai fixed height
    aspectRatio: 1.8,        // makin besar → makin pendek tampilannya

    expandRows: true,        // semua row tampil penuh
    dayMaxEventRows: 2,      // batasi jumlah event per hari

    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth'
    }
});

calendar.render();

});
</script>
@endsection