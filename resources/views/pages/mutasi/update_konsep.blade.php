@extends('layouts.app')

@section('title', 'Edit Mutasi')


@section('content')
    <div class="w-full rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px]" x-data="ubahKonsep(@js($konsep))">
        <div class="flex">
            <p class="font-semibold text-[24px]">Update Konsep</p>
        </div>
        <div class="flex flex-col gap-[16px]">
            <x-input-text label='Nama Konsep' class="!w-[500px]" x-model="updateForm.nama_konsep" />
            <div class="flex flex-col gap-[8px]">
                <label>
                    <p class="text-sm font-medium text-gray-700">Tanggal Konsep</p>
                </label>
                <div class="relative w-[500px]">
                    <input type="text" x-ref="datepicker" placeholder="Pilih tanggal"
                        class="!w-[500px] h-fit text-sm text-gray-700 border border-gray-300 rounded-[8px] p-2 focus:outline-none focus:ring-1 focus:ring-blue-500 shadow-xs"
                        x-model="updateForm.tanggal_konsep" />
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none"><x-icons.calendar /></div>

                </div>
                <x-input.text_area label='Keterangan' class="!w-[500px]" x-model="updateForm.keterangan" />
            </div>
            <x-ui.button size="sm"
                class="text-[12px] bg-green-500 hover:bg-green-600 w-fit disabled:bg-green-500 disabled:opacity-50 hover:cursor-pointer disabled:cursor-not-allowed"
                @click="submit()"
                x-bind:disabled="!updateForm.nama_konsep || !updateForm.tanggal_konsep || loading">Submit</x-ui.button>
        </div>
    @endsection

    @push('scripts')
        <script>
            function ubahKonsep(konsep) {
                return {
                    updateForm: {
                        nama_konsep: null,
                        tanggal_konsep: null,
                        keterangan: null,
                    },
                    konsep,
                    loading: false,
                    error: null,

                    init() {
                        this.$watch('loading', (val) => {
                            if (val) {

                                SwalHelper.loadingToast('Sedang Memproses...')
                            } else if (!val) {
                                SwalHelper.close()
                            }
                        })

                        this.$watch('error', (val) => {
                            if (val) {
                                SwalHelper.errorToast(this.error)
                                this.error = null
                            }
                        })

                        flatpickr(this.$refs.datepicker, {
                            dateFormat: "Y-m-d",
                            defaultDate: "today",
                            onChange: (selectedDates, dateStr) => {
                                this.updateForm.tanggal_konsep = dateStr;
                            }
                        });

                        this.updateForm.nama_konsep = konsep.nama_konsep
                        this.updateForm.tanggal_konsep = konsep.tanggal_konsep
                        this.updateForm.keterangan = konsep.keterangan
                    },

                    async submit() {
                        try {
                            this.loading = true
                            if (this.updateForm.nama_konsep && this.updateForm.tanggal_konsep) {
                                const res = await axios.put(`{{ url('/') }}/mutasi/konsep/${konsep.id}`, this
                                    .updateForm)
                                if (res.data.redirect) {
                                    window.location.href = res.data.redirect;
                                }
                            } else {
                                this.error = `Mohon isi nama konsep dan tanggal konsep`
                            }
                        } finally {
                            this.loading = false

                        }
                    }
                }
            }
        </script>
    @endpush
