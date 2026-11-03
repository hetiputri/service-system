<!-- STEP FINAL -->
<div
    x-data="{ 
    isNotDraft: @json(optional($pengajuan)->status_akhir && !in_array(optional($pengajuan)->status_akhir, ['draft'])) 
}"
    x-show=" isNotDraft"
    x-transition>


    <div class="flex flex-col items-center justify-center text-center py-16">
        <!-- TITLE -->
        @if($pengajuan?->status_akhir == 'selesai')
        <!-- ICON -->
        <div class="w-24 h-24 flex items-center justify-center rounded-2xl bg-yellow-100 mb-6">
            <img src="{{asset('icons/clipboard-check.svg')}}">
        </div>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">
            Pengajuan Pensiun Selesai , Silahkan Unduh File SK Pensiun
        </h2>
        <!-- this.skPath = "{{ route('pensiun.preview.sk', $pengajuan->id) }}?t=" + Date.now(); -->
        <a
            href="{{ route('pensiun.preview.sk', $pengajuan?->id) }}"
            target="_blank"
            class="px-3 py-1 bg-blue-600 text-white rounded text-xl hover:bg-blue-700">
            👁 Lihat SK
        </a>
        @elseif ($pengajuan?->status_akhir == 'ditolak' || $pengajuan?->status_akhir == 'Revisi')
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>

        <h2 class="text-xl font-semibold text-gray-800 mb-2">
            Pengajuan Pensiun {{ $pengajuan->status_akhir == 'ditolak' ? 'Ditolak' : 'Perlu Revisi' }}
        </h2>
        <!-- NOMOR TIKET -->
        <p class="text-gray-600 mb-6">
            Nomor Tiket :
            <span class="font-bold text-gray-800">{{$pengajuan?->nomor_tiket}}</span>
        </p>
        <p class="text-red-500 mb-6 text-xl">
            Catatan : <span class="text-xl font-bold text-red-500">{{$lastLog?->catatan ?? '-'}}</span>
        </p>

        <!-- BUTTON -->
        <a href='{{ url("/pensiun/riwayat") }}'
            class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-sm rounded-lg transition">
            Lihat Riwayat Pengajuan
        </a>
        @elseif($pengajuan?->current_step != 1)
        <!-- ICON -->
        <div class="w-24 h-24 flex items-center justify-center rounded-2xl bg-yellow-100 mb-6">
            <img src="{{asset('icons/clipboard-check.svg')}}">
        </div>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">
            Pengajuan Pensiun Berhasil Dikirim
        </h2>
        <!-- NOMOR TIKET -->
        <p class="text-gray-600 mb-6">
            Nomor Tiket :
            <span class="font-bold text-gray-800">{{$pengajuan?->nomor_tiket}}</span>
        </p>

        <!-- BUTTON -->
        <a href='{{ url("/pensiun/riwayat") }}'
            class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-sm rounded-lg transition">
            Lihat Riwayat Pengajuan
        </a>
        @endif




    </div>

</div>