@extends('layouts.app')
@section('title','Admin OPD')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6 rounded-2xl shadow">
        <h2 class="text-xl font-semibold">
            Data Pengajuan Pensiun
        </h2>
        <p class="text-sm opacity-90">
            Role: {{ $role }}
        </p>
    </div>

    <!-- FILTER -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border flex flex-wrap items-center gap-3">

        <div>
            <label class="text-xs text-gray-500 block mb-1">Filter Bulan</label>
            <input
                type="month"
                id="filterBulan"
                value="{{ now()->format('Y-m') }}"
                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>

        <div class="flex gap-2 mt-5">
            <button onclick="reloadTable()"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm transition">
                Filter
            </button>

            <button onclick="resetFilter()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm transition">
                Reset
            </button>
        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden p-5">

        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-700">
                Daftar Pengajuan
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table id="tablePengajuan" class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nomor Tiket</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Nama Pegawai</th>
                        <th class="px-4 py-3 text-left">NIP</th>
                        <th class="px-4 py-3 text-left">OPD</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-left">Step</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Catatan</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>

            </table>
        </div>

    </div>

</div>


<!-- MODAL -->
<div id="modalRiwayat" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50">

    <div class="flex items-center justify-center min-h-screen p-4">

        <div class="bg-white w-full max-w-xl rounded-2xl shadow-xl p-5 animate-fadeIn">

            <h3 class="font-semibold mb-4 text-lg">
                Riwayat Pengajuan
            </h3>

            <div id="contentRiwayat"
                class="text-sm space-y-3 max-h-80 overflow-y-auto pr-1">
            </div>

            <div class="text-right mt-4">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
                    Tutup
                </button>
            </div>

        </div>

    </div>
</div>


<script>
    let table;

    $(document).ready(function() {
        //$('#tablePengajuan').DataTable({});
        table = $('#tablePengajuan').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('admin.pensiun.data') }}",
                dataSrc: 'data',
                data: function(d) {
                    d.periode = $('#filterBulan').val();
                    d.role = "{{ $role }}";
                }
            },
            // createdRow: function(row, data, dataIndex) {
            //     const role = "{{ $role }}";
            //     const currentStep = Number(data?.current_step ?? 0);
            //     let className = '';

            //     row.classList.remove('bg-blue-50', 'bg-indigo-50', 'bg-green-50');
            //     row.querySelectorAll('td, th').forEach(cell => cell.classList.remove('bg-blue-50', 'bg-indigo-50', 'bg-green-50'));

            //     if (role === 'AdminOpdPensiun' && currentStep === 2) {
            //         className = 'bg-blue-50';
            //     } else if (role === 'AdminBkdPensiun' && currentStep === 3) {
            //         className = 'bg-indigo-50';
            //     } else if (currentStep >= 4) {
            //         className = 'bg-green-50';
            //     }

            //     if (className) {
            //         row.classList.add(className);
            //         row.querySelectorAll('td, th').forEach(cell => {
            //             cell.classList.add(className);
            //         });
            //     }
            // },
            columns: [{

                    data: 'no'
                },
                {
                    data: 'nomor_tiket'
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'nama_pegawai'
                },
                {
                    data: 'nip'
                },
                {
                    data: 'opd'
                },
                {
                    data: 'jenis'
                },
                {
                    data: 'nama_step',
                    // render: function(data, type, row) {
                    //     const value = data || '-';
                    //     const role = "{{ $role }}";
                    //     const currentStep = Number(row?.current_step ?? 0);

                    //     let className = 'bg-gray-100 text-gray-700 border-gray-200';

                    //     if (role === 'AdminOpdPensiun') { // && currentStep === 2) {
                    //         className = 'bg-blue-100 text-blue-700 border-blue-200';
                    //     } else if (role === 'AdminBkdPensiun' && currentStep === 3) {
                    //         className = 'bg-green-100 text-indigo-700 border-indigo-200';
                    //     } else if (currentStep >= 4) {
                    //         className = 'bg-green-100 text-green-700 border-green-200';
                    //     }

                    //     return `
                    //         <span class="inline-flex items-center px-2.5 py-1 rounded-full border font-medium ${className}">
                    //             ${value}
                    //         </span>
                    //     `;
                    // }
                },
                {
                    data: 'status',
                    render: function(data) {

                        let color = 'gray';

                        if (data === 'diajukan') color = 'blue'
                        else if (data === 'verifikasi_opd') color = 'yellow'
                        else if (data === 'selesai') color = 'green'

                        return `
                        <span class="px-2 py-1 text-xs rounded-full bg-${color}-100 text-${color}-600 font-medium">
                            ${data.replace('_',' ')}
                        </span>
                    `;
                    }
                },
                {
                    data: 'catatan'
                },
                {
                    data: null,
                    render: function(data) {
                        const role = "{{ $role }}";
                        const currentStep = Number(data?.current_step ?? 0);
                        //return 
                        let html = `<div class="flex gap-2">`;
                        if ((role === 'AdminOpdPensiun' && currentStep === 2) || (role === 'AdminBkdPensiun' && currentStep >= 3)) {
                            html += `<a href="{{ url('/admin-pensiun-detail') }}/${data.id}?role={{$role}}"
                                class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs transition">
                                Detail
                            </a>`;

                        }
                        html += `<button onclick='showRiwayat(${JSON.stringify(data.logs)})'
                                class="px-3 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-xs transition">
                                Riwayat
                            </button>
                        </div>
                    `;

                        return html;
                    }
                }
            ]
        });

    });

    function showRiwayat(logs) {

        let html = '';

        logs.forEach(log => {

            let color = 'gray';

            if (log.aksi === 'diajukan') color = 'blue'
            else if (log.aksi === 'revisi') color = 'yellow'
            else if (log.aksi === 'selesai') color = 'green'
            else if (log.aksi === 'diterima') color = 'green'
            else if (log.aksi === 'ditolak') color = 'red'

            html += `
            <div class="border rounded-xl p-3 hover:shadow-sm transition">
                
                <div class="flex justify-left items-center mb-1">
                        ${log.nama_steps}
                    <span class="px-2 py-1 text-xs rounded-full bg-${color}-100 text-${color}-600">
                        ${log.aksi}
                    </span>

                    <span class="text-xs text-gray-500">
                        ${log.created_at}
                    </span>
                </div>

                <div class="text-sm text-gray-700">
                    by ${log.role} : ${log.id_user_proses} <br>
                    Catatan : ${log.catatan ?? '-'}
                </div>

            </div>
        `;
        });

        $('#contentRiwayat').html(html);
        $('#modalRiwayat').removeClass('hidden');
    }

    function closeModal() {
        $('#modalRiwayat').addClass('hidden');
    }

    // FILTER
    function reloadTable() {
        table.ajax.reload()
    }

    function resetFilter() {
        $('#filterBulan').val('')
        table.ajax.reload()
    }
</script>
@endsection