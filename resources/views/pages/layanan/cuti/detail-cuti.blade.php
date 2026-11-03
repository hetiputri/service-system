@extends('layouts.app')
@section('title', 'Pengajuan Cuti')
@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow">

    <h2 class="text-lg font-semibold mb-6">
        Timeline Pengajuan Cuti
    </h2>
    <div x-data="detailForm()">
        <div class="relative">

            <!-- LINE -->
            <div class="absolute left-4 top-0 bottom-0 w-[2px] bg-gray-200"></div>

            @foreach($steps as $i => $step)

            <!-- <div class="flex gap-4 mb-6 relative"> -->
            <div class="flex gap-4 mb-6 relative transition-all duration-500">

                <!-- CIRCLE -->
                <div class="z-10">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center
                        @if($step->tanggal_proses != null && $step->aksi!='ditolak' && $step->aksi!='dibatalkan')  
                            border-2 border-green-500
                        @elseif($step->tanggal_proses != null && ($step->aksi=='ditolak' || $step->aksi=='dibatalkan'))  
                            border-2 border-red-100
                        @else
                            border-2 border-blue-500
                        @endif
                                       
                ">

                        <div class="w-6 h-6 flex items-center justify-center rounded-full text-xs font-semibold
                            @if($step->tanggal_proses != null && $step->aksi!='ditolak' && $step->aksi!='dibatalkan')  
                                bg-green-500 text-white
                            @elseif($step->tanggal_proses != null && ($step->aksi=='ditolak' || $step->aksi=='dibatalkan'))  
                                bg-red-100 border-red-300
                            @else 
                                bg-blue-500 text-white
                            @endif                       
                    ">
                            {{ $i+1 }}
                        </div>

                    </div>
                </div>

                <!-- CARD -->
                <div class="flex-1 border rounded-xl p-4
                    @if($step->tanggal_proses != null && $step->aksi!='ditolak' && $step->aksi!='dibatalkan')  
                        bg-green-50 border-green-300
                    @elseif($step->tanggal_proses != null && ($step->aksi=='ditolak' || $step->aksi=='dibatalkan'))  
                        bg-red-100 border-red-300
                    @else 
                        bg-blue-50 border-blue-300
                    @endif                
                ">

                    <div class="flex justify-between items-center mb-1">

                        <h3 class="font-semibold">
                            {{ $step->nama_step }}
                            @if($step->urutan_step == 1)
                            @if($step->catatan=="Revisi")
                            Ulang
                            @endif
                            @elseif($step->urutan_step == 2)
                            @if($step->aksi=="ditolak")
                            Ditolak
                            @elseif($step->aksi=="diterima")
                            Diterima
                            @endif
                            @endif
                        </h3>

                        <!-- <span class="text-xs px-2 py-1 rounded-full
                        @if($step->urutan_step <=  $step_now || $step->tanggal_proses != null)  
                            @if($step->urutan_step % 2 == 1) 
                                bg-green-200 text-green-700
                            @elseif($step->urutan_step % 2 == 0) 
                                bg-blue-200 text-blue-700
                            @endif
                        @else 
                            bg-gray-200 text-gray-500
                        @endif                        
                    "> -->
                        <!-- @if($step->urutan_step < $step_now ||$step->tanggal_proses!= null)
                                Selesai
                                @elseif($step->urutan_step == $step_now)
                                Proses
                                @else
                                Belum tersedia
                                @endif -->

                        <!-- </span> -->

                    </div>
                    <p class="text-md text-blue-500">
                        {{ $step->tanggal_proses? $step->tanggal_proses :'Dalam Proses'}}
                    </p>
                    <div class="flex justify-between items-center mb-1">
                        @if($step->aksi=="ditolak")
                        <p class="text-sm text-gray-500">
                            Catatan: {{ $step->catatan }}
                        </p>
                        @elseif($step->aksi == 'dibatalkan')
                        <p class="text-sm text-gray-500">
                            {{ $step->aksi }}
                        </p>
                        @elseif($step->urutan_step != 3 && $step->tanggal_proses!= null)
                        <p class="text-sm text-gray-500">
                            {{ $step->description }}
                        </p>
                        @elseif($step->urutan_step == 3 && $step->aksi=='selesai')
                        <p class="text-sm text-gray-500">
                            {{ $step->description }}

                        </p>
                        <button
                            class="flex w-auto whitespace-nowrap items-center gap-2 px-4 py-2 border border-blue-500 bg-blue-500 text-white font-bold rounded-lg text-md"
                            @click='lihatSK({{ $step->pengajuan_id }})'>
                            Unduh Persetujuan
                        </button>
                        @endif

                        <!-- <span class="text-xs px-2 py-1 rounded-full
                        @if(($step->urutan_step ==  2 && $step->aksi!='') || ($step->urutan_step ==  1 && $step->catatan != '')|| ($step->urutan_step ==  3 && $step->aksi == 'selesai')) 
                            @if($step->urutan_step % 2 == 1) 
                                bg-green-200 text-green-700
                            @elseif($step->urutan_step % 2 == 0 ) 
                                bg-blue-200 text-blue-700
                            @endif
                        @endif                        
                    "> -->
                        <!-- @if($step->urutan_step == 2)
                            {{ $step->aksi }}
                            @elseif($step->urutan_step == 3 && $step->aksi=='selesai')
                            <button class='button' @click='lihatSK({{ $step->id_proses }})'>Lihat SK</button>
                            @else
                            {{ $step->catatan }}
                            @endif

                        </span> -->
                    </div>


                </div>

            </div>

            @endforeach

        </div>
        <!-- Preview File-->
        <div x-show="previewOpen" class="fixed inset-0 bg-black/60 flex items-center justify-center z-999">

            <div class="bg-white p-4 rounded-xl w-[80%] h-[80%] relative">

                <button @click="previewOpen=false" class="absolute top-3 right-3 bg-white">
                    ✕
                </button>

                <iframe
                    :src="previewUrl"
                    class="w-full h-full rounded-lg">
                </iframe>

            </div>

        </div>
    </div>
    <script>
        function detailForm() {
            return {
                previewOpen: false,
                previewUrl: null,
                lihatSK(id) {
                    let fileurl = `{{ route('cuti.lihat-sk') }}?id=${id}&v=${Date.now()}`;
                    this.previewUrl = fileurl;
                    this.previewOpen = true;
                },

            }

        }
    </script>
</div>
@endsection