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
                <a href="{{ url('/profile') }}"
                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg inline-block">
                    Selengkapnya
                </a>
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
                <!-- <div class="flex justify-between">
                    <span>Role</span>
                    <span class="font-medium">{{ auth()->user()->role->name ?? 'User' }}</span>
                </div> -->
            </div>
        </div>
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