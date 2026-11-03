@extends('layouts.app')
@section('title', 'Pengajuan Cuti')
@section('content')
<div>
    <div x-data="Rekap()">
        <div class="max-w-7xl mx-auto p-4">
            <!-- Card Container -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">

                <!-- Header -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Riwayat Pengajuan Cuti
                    </h2>

                    <div class="table-responsive p-3">
                        <table class="table align-items-center mb-0" id="myTable" width='100%'>
                            <thead class="bg-gray-200 text-gray-600">
                                <tr>
                                    <td width="5%" class='font-bold'>No</td>
                                    <td width='18%' class='font-bold'>Tanggal Pengajuan</td>
                                    <td width='10%' class='font-bold'>Jenis Cuti</td>
                                    <td width='20%' class='font-bold'>Waktu Cuti</td>
                                    <td width='10%' class='font-bold'>Status</td>
                                    <td width='10%' class='font-bold'>&nbsp;</td>
                                    <td width='10%' class='font-bold'>&nbsp;</td>
                                    <td width='10%' class='font-bold'>&nbsp;</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayats as $index => $riw)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $riw->tanggal_pengajuan }}</td>
                                    <td>{{ $riw->nama_layanan }}</td>
                                    <td>{{ $riw->tgl_selesai ? tglindonesia($riw->tgl_mulai).' s.d '. tglindonesia($riw->tgl_selesai):'' }}</td>
                                    <td>
                                        <p class='border-blue-200 w-20 bg-blue-200 px-2 py-1 rounded-md text-center text-sm text-blue'>{{ $riw->status_akhir }}</p>
                                    </td>
                                    <td><button type='button' @click='detail("{{ $riw->id }}")' class='border-blue-500 w-20 bg-blue-500 px-2 py-1 rounded-md text-sm text-white'>Detail</button></td>
                                    <td class='text-center'>
                                        @if($riw->status_akhir=='draft' || $riw->status_akhir=='Revisi')
                                        <button type='button' @click='lanjutkan("{{ $riw->id }}")' class='border-green-500 w-30 bg-green-500 px-2 py-1 rounded-md text-sm text-white'>Lanjutkan</button>
                                        @elseif($riw->status_akhir=='selesai')
                                        <button type='button' @click='lihatSK("{{ $riw->id }}")' class='border-yellow-500 w-30 bg-yellow-500 px-2 py-1 rounded-md text-sm text-white inline-flex items-center gap-2'>
                                            <img src="{{ asset('icons/download.svg') }}" class='inline-block' width="20%" />
                                            <span class='inline-block'>Persetujuan</span>
                                        </button>
                                        @endif
                                    </td>
                                    <td class='text-center'>
                                        @if($riw->status_akhir=='selesai')

                                        @elseif($riw->status_akhir=='draft' || $riw->status_akhir=='Revisi')
                                        <button type='button' @click='hapus("{{ $riw->id }}")' class='border-red-500 w-30 bg-red-500 px-2 py-1 rounded-md text-sm text-white'>Hapus</button>
                                        @endif

                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

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
    </div>
    <script>
        function Rekap() {
            return {
                previewOpen: false,
                previewUrl: null,
                init() {
                    $('#myTable').DataTable();
                },
                detail(id) {
                    document.location.href = "{{ url('/detail-cuti/')}}/" + id;
                },
                hapus(id) {
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: "Anda yakin ingin menghapus Seluruh Usulan Cuti ini?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ url('pengajuan/cuti/hapus') }}/${id}`)
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success == false) {
                                        alert(data.message);
                                    } else {
                                        Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: data.message,
                                                buttons: true,
                                                dangerMode: true,
                                            })
                                            .then((willOk) => {
                                                if (willOk) {
                                                    location.reload();
                                                }
                                            });
                                    }
                                });
                        }
                    });

                },
                lihatSK(id) {
                    let fileurl = `{{ route('cuti.lihat-sk') }}?id=${id}&v=${Date.now()}`;
                    this.previewUrl = fileurl;
                    this.previewOpen = true;
                },
                lanjutkan(id) {
                    document.location.href = "{{ route('pengajuan.cuti') }}?id=" + id;
                }

            }

        }
    </script>
</div>
@endsection