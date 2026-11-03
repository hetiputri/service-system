@extends('layouts.app')
@section('title', 'Riwayat Pengajuan')

@section('content')

<div class="bg-white p-8 rounded-2xl shadow">

    <h2 class="text-lg font-semibold mb-6">
        Riwayat Pengajuan Pensiun
    </h2>

    @if($data->isEmpty())
    <div class="text-center py-16 text-gray-500">
        Belum ada pengajuan
    </div>
    @else

    <div class="overflow-x-auto">
        <table class="w-full text-sm border rounded-xl overflow-hidden">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nomor Tiket</th>
                    <th class="p-3 text-left">Jenis Pensiun</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($data as $i => $row)
                <tr class="border-t hover:bg-gray-50">

                    <td class="p-3">
                        {{ $i + 1 }}
                    </td>

                    <td class="p-3 font-semibold text-gray-700">
                        {{ $row->nomor_tiket ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $row->layanan->nama_layanan ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $row->tanggal_pengajuan?->format('d M Y') ?? '-' }}
                    </td>

                    <td class="p-3">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($row->status_akhir == 'diajukan') bg-blue-100 text-blue-600
                            @elseif($row->status_akhir == 'diproses') bg-yellow-100 text-yellow-600
                            @elseif($row->status_akhir == 'selesai') bg-green-100 text-green-600
                            @elseif($row->status_akhir == 'ditolak') bg-red-100 text-red-600
                            @else bg-gray-200 text-gray-600
                            @endif
                        ">
                            {{ ucfirst($row->status_akhir ?? 'draft') }}
                        </span>
                    </td>

                    <td class="p-3 space-x-2">
                        <!-- {{ url('/detail-pensiun/{id}') }} -->
                        <!-- DETAIL -->
                        <a href="{{ url('/detail-pensiun/' . $row->id) }}"
                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Detail
                        </a>

                        <!-- LANJUTKAN -->
                        <!-- @if($row->status_akhir != 'selesai')
                        <a href="/pengajuan/pensiun?pengajuan_id={{ $row->id }}"
                            class="px-3 py-1 text-xs bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                            Lanjutkan
                        </a>
                        @endif -->

                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>
    </div>

    @endif

</div>

@endsection