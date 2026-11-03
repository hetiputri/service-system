@extends('layouts.app')

@section('title', 'Tambah Mutasi')


@section('content')
    <div class="w-full rounded-[16px] bg-white px-[24px] py-[20px] flex flex-col gap-[24px]" x-data="tambahKonsep()">
        <div class="flex">
            <p class="font-semibold text-[24px]">Tambah Konsep</p>
        </div>
        <div class="flex flex-col gap-[16px]">
            <x-input-text label='Nama Konsep' class="!w-[500px]" x-model="tambahForm.nama_konsep" />
            <div class="flex flex-col gap-[8px]">
                <label>
                    <p class="text-sm font-medium text-gray-700">Tanggal Konsep</p>
                </label>
                <div class="relative w-[500px]">
                    <input type="text" x-ref="datepicker" placeholder="Pilih tanggal"
                        class="!w-[500px] h-fit text-sm text-gray-700 border border-gray-300 rounded-[8px] p-2 focus:outline-none focus:ring-1 focus:ring-blue-500 shadow-xs" />
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none"><x-icons.calendar /></div>

                </div>
                <x-input.text_area label='Keterangan' class="!w-[500px]" x-model="tambahForm.keterangan" />
            </div>
            <x-ui.button size="sm"
                class="text-[12px] bg-green-500 hover:bg-green-600 w-fit disabled:bg-green-500 disabled:opacity-50 hover:cursor-pointer disabled:cursor-not-allowed"
                @click="submit()"
                x-bind:disabled="!tambahForm.nama_konsep || !tambahForm.tanggal_konsep || loading">Submit</x-ui.button>
        </div>
    @endsection

    @push('scripts')
        <script>
            function tambahKonsep() {
                return {
                    tambahForm: {
                        nama_konsep: null,
                        tanggal_konsep: null,
                        keterangan: null,
                    },

                    loading: false,
                    error: null,
                    validationError: null,

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
                            onReady: (selectedDates, dateStr) => {
                                this.tambahForm.tanggal_konsep = dateStr;
                            },
                            onChange: (selectedDates, dateStr) => {
                                this.tambahForm.tanggal_konsep = dateStr;
                            }
                        });
                    },

                    async submit() {
                        try {
                            this.loading = true
                            if (this.tambahForm.nama_konsep && this.tambahForm.tanggal_konsep) {
                                const res = await axios.post('{{ url('/') }}/mutasi/konsep', this.tambahForm)
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
