@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


<div class="max-w-7xl mx-auto p-4">
    <!-- Card Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                Sinkronisasi Data
            </h2>
        </div>

        <div class="p-6">
            <div class="mt-3 mb-3">

                @if(session('message'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="bg-green-200 p-3 mb-3 text-sm">
                    {{ session('message') }}
                </div>
                @endif

                <div x-data="dataComponent()">
                    <div x-show="loading"
                        style="background-color:rgb(0,0,0,0.5);display:none"
                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50">
                        <div class="spinner"></div>
                        <span>Processing...</span>
                    </div>
                    <table class="min-w-full text-xs text-left">

                        <!-- Loop Data -->
                        <template x-for="(data,index) in datas" :key="data.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-1 pl-6 py-3"
                                    x-text="index + 1"></td>
                                <td class="px-2 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-800" x-text="data.nama"></span>
                                    </div>
                                </td>
                                <td class="px-2 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-xs text-gray-800" x-text="'update terakhir: ' + (data.tanggal_update ? data.tanggal_update : 'belum pernah')"></span>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <button @click="aksi(data.id)"
                                        class="border px-3 py-1 bg-white-800 border-blue-500 text-blue-500 rounded text-xs">
                                        Aksi
                                    </button>
                                </td>
                            </tr>

                        </template>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .spinner {
        border: 4px solid #eee;
        border-top: 4px solid #3490dc;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>


<!-- Toggle Script -->
<script>
    function dataComponent() {
        return {
            loading: false,
            datas: @json($datasinkron),
            aksi(id) {
                // Show loading
                Swal.fire({
                    title: 'Proses Data',
                    text: 'Silakan Tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                fetch(`{{ url('/sinkronisasi') }}/${id}`)
                    .then(async res => {
                        const data = await res.json();

                        if (!res.ok || data.success === false) {
                            throw new Error(data.message || 'Sinkronisasi gagal.');
                        }

                        return data;
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                        }).then(() => location.reload());
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sinkronisasi gagal',
                            text: error.message,
                        });
                    });
            }
        }
    }
</script>


@endsection