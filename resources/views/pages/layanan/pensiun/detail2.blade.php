@extends('layouts.app')
@section('title','Detail Pengajuan')

@section('content')
@php

$logs = $pengajuan->workflowLogs->keyBy('id_step');
$current = $pengajuan->current_step ?? 1;

@endphp


<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow">

    <h2 class="text-lg font-semibold mb-6">
        Timeline Pengajuan Pensiun
    </h2>

    <div class="relative">

        <!-- LINE -->
        <div class="absolute left-4 top-0 bottom-0 w-[2px] bg-gray-200"></div>

        @foreach($steps as $i => $step)
        @if($i > $current) @break @endif
        @php
        $isDone = $i < $current;
            $isActive=$i==$current;
            $log=$logs[$i] ?? null;
            @endphp
            <!-- @php
            $isDone=$i < $current;
            $isActive=$i==$current;
            @endphp -->

            <!-- <div class="flex gap-4 mb-6 relative"> -->
            <div class="flex gap-4 mb-6 relative transition-all duration-500">

                <!-- CIRCLE -->
                <div class="z-10">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center
                    @if($isDone) border-2 border-green-500
                    @elseif($isActive) border-2 border-blue-500
                    @else border-2 border-gray-300
                    @endif
                ">

                        <div class="w-6 h-6 flex items-center justify-center rounded-full text-xs font-semibold
                        @if($isDone) bg-green-500 text-white
                        @elseif($isActive) bg-blue-500 text-white
                        @else bg-gray-200 text-gray-500
                        @endif
                    ">
                            {{ $i }}
                        </div>

                    </div>
                </div>

                <!-- CARD -->
                <div class="flex-1 border rounded-xl p-4
                @if($isDone) bg-green-50 border-green-300
                @elseif($isActive) bg-blue-50 border-blue-300
                @else bg-gray-50 border-gray-200
                @endif
            ">

                    <div class="flex justify-between items-center mb-1">

                        <h3 class="font-semibold">
                            <!-- {{ $step['title'] }} -->
                            {{ $step->nama_step }}
                        </h3>

                        <span class="text-xs px-2 py-1 rounded-full
                        @if($isDone) bg-green-200 text-green-700
                        @elseif($isActive) bg-blue-200 text-blue-700
                        @else bg-gray-200 text-gray-500
                        @endif
                    ">
                            @if($isDone) Selesai
                            @elseif($isActive) Proses
                            @else Belum tersedia
                            @endif
                        </span>

                    </div>

                    <p class="text-sm text-gray-500">
                        <!-- {{ $step['desc'] }} -->
                        {{ $step->description }}
                    </p>

                    <!-- OPTIONAL DATE -->
                    <!-- @if($isDone || $isActive)
                <p class="text-xs text-gray-400 mt-1">
                    {{ $pengajuan->updated_at->format('d M Y H:i') }}
                </p>
                @endif -->
                    @if($log)
                    <p class="text-xs text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($log->tanggal_proses)->format('d M Y H:i') }}
                    </p>

                    @if($log->catatan)
                    <p class="text-xs text-gray-500 mt-1 italic">
                        Catatan : {{ $log->catatan }}
                    </p>
                    @endif
                    @endif

                </div>

            </div>

            @endforeach

            @if($pengajuan->status_akhir == 'selesai')
            <!-- FINAL STEP -->
            <div class="flex gap-4 relative">

                <div class="z-10">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full
                    {{ $pengajuan->status_akhir == 'selesai' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                        ✔
                    </div>
                </div>

                <div class="flex-1 border rounded-xl p-4
                {{ $pengajuan->status_akhir == 'selesai' ? 'bg-yellow-50 border-yellow-300' : 'bg-gray-50 border-gray-200' }}">

                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold">
                            Update Status di SIMPEG
                        </h3>

                        <span class="text-xs px-2 py-1 rounded-full
                        {{ $pengajuan->status_akhir == 'selesai' ? 'bg-yellow-200 text-yellow-700' : 'bg-gray-200 text-gray-500' }}">
                            {{ $pengajuan->status_akhir == 'selesai' ? 'Selesai' : 'Menunggu' }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-500">
                        Mengubah status di sistem SIMPEG oleh operator
                    </p>

                </div>

            </div>
            @endif
    </div>

</div>

@endsection