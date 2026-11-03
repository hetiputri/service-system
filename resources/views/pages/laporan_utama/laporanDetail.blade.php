@extends('layouts.app')
@section('title', 'Dashboard Pimpinan')
@section('content')

<div class="max-w-7xl mx-auto p-4">

    <!-- Card Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Result Data Table Card -->
        <div class="bg-white rounded-lg border border-stroke shadow-sm overflow-hidden">
            <div class="px-6 py-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-darktext">{{ $judul}}</h3>

                <span class="text-xs bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full font-medium" id="totalCountBadge">0 Pegawai</span>

            </div>
            <div class="px-6 pb-6 border-b border-slate-100 flex items-center justify-between">
                <button @click="kembali()"
                    class="px-3 py-1 bg-gray-300 text-black rounded text-xs hover:bg-warning-300">
                    Kembali
                </button>
            </div>
            <!-- Data Table -->
            <div class="overflow-x-auto p-4">
                <table id="pegawaiTable" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-stroke">
                            <th class="px-4 py-3 w-12 text-center">NO</th>
                            <th class="px-4 py-3 w-1/5">PEGAWAI</th>
                            <th class="px-4 py-3">PANGKAT/GOL</th>
                            <th class="px-4 py-3">OPD</th>
                            <th class="px-4 py-3 w-16">JK</th>
                            <th class="px-4 py-3 w-20 text-center">PROFIL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @foreach ($dapeg as $idx => $pegawai)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 text-center font-medium text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-darktext">{{ $pegawai['nama_pegawai'] }}</div>
                                <div class="text-xs text-subtext font-mono mt-0.5 tracking-tight">{{ $pegawai['nip'] }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-500 font-medium"> {{ $pegawai['pangkat']}} {{ $pegawai['gol_ruang']}}</td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $pegawai['jabatan'] }}</div>
                                <div class="text-xs text-subtext mt-0.5">{{ $pegawai['unit_kerja'] }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($pegawai['jenis_kelamin'] === 'Laki-laki')
                                    bg-blue-50 text-blue-600 border border-blue-200
                                @else
                                    bg-pink-50 text-pink-600 border border-pink-200
                                @endif">
                                    {{ $pegawai['jenis_kelamin'] }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <a href="{{ route('pegawai.profile', $pegawai['id_pegawai']) }}" target="_blank"
                                    class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                                    Lihat
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<script>
    let table;

    $(document).ready(function() {
        // Inisialisasi DataTables
        table = $('#pegawaiTable').DataTable({
            responsive: true,
            ordering: true,
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_ entries",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entries",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "›",
                    previous: "‹"
                },
                zeroRecords: "Tidak ada data pegawai yang sesuai"
            },
            // Modern custom dom layout for DataTables
            dom: '<"flex flex-col sm:flex-row sm:items-center justify-between border-b border-stroke px-6 py-3 gap-3"<"text-sm text-subtext flex items-center"l><"w-full sm:w-auto"f>>rt<"flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 gap-3"<"text-sm text-subtext"i><"pagination-sm"p>>'
        });

        // Synchronize total count badge
        updateBadgeCount();
        table.on('draw', updateBadgeCount);

        function updateBadgeCount() {
            const count = table.rows({
                filter: 'applied'
            }).count();
            $('#totalCountBadge').text(count + ' Pegawai');
        }
    });

    function kembali() {
        document.location.href = "{{ route('menu.pimpinan') }}";
    }
</script>

@endsection