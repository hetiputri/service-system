<div x-show="currentStep === 3   && status_akhir && status_akhir != 'selesai' && status_akhir != 'diajukan'" x-transition>
    <div class="grid grid-cols-1 gap-4">

        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold mb-6 mt-5 text-center pb-2">
                FORMULIR PERMINTAAN DAN PEMBERIAN CUTI
            </h2>
            <div class="flex gap-2 mb-4 mt-4">

                <button
                    @click="ttd('basah')"
                    class="px-4 py-2 rounded-lg text-sm border"
                    :class="ttdMode=='basah' ? 'bg-gray-200 font-semibold' : 'bg-white'">
                    ✍️ Tanda Tangan Basah
                </button>

                <button
                    @click="ttd('elektronik')"
                    class="px-4 py-2 rounded-lg text-sm border"
                    :class="ttdMode=='elektronik' ? 'bg-gray-200 font-semibold' : 'bg-white'">
                    🖊️ Tanda Tangan Elektronik
                </button>

            </div>
            <div class="border rounded-lg p-8 bg-gray-50 mx-5">

                <table class="w-200 mt-4 border-collapse border-2 mx-15 border-black">
                    <tr class="border-2 border-black">
                        <td colspan=' 4' class="font-bold text-base p-2">I. DATA PEGAWAI</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='15%' class='border-2 border-black pl-3'>Nama</td>
                        <td width='45%' class='border-2 border-black pl-3'>{{ $data_pegawai ? $data_pegawai->nama_pegawai : '' }}</td>
                        <td width='15%' class='border-2 border-black pl-3'>NIP</td>
                        <td width='45%' class='border-2 border-black pl-3'>{{ $data_pegawai ? str_replace(' ', '', $data_pegawai->nip) : '' }}</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='15%' class='border-2 border-black pl-3'>Jabatan</td>
                        <td width='45%' class='border-2 border-black pl-3'>{{ $data_pegawai ? $data_pegawai->jabatan : '' }}</td>
                        <td width='15%' class='border-2 border-black pl-3'>Masa Kerja</td>
                        <td width='45%' class='border-2 border-black pl-3'>{{ $masa_kerja ? $masa_kerja['y'].' Tahun '.$masa_kerja['m'].' Bulan': '' }}</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='15%' class='border-2 border-black pl-3'>Unit Kerja</td>
                        <td colspan='3' width='85%' class='border-2 border-black px-3'>{{ $data_pegawai ? $data_pegawai->skpd : '' }}</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='4' width='85%' class='border-2 border-b-black px-3'>&nbsp;</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='4' class="font-bold text-base p-2">II. JENIS CUTI YANG DIAMBIL</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='4' class="text-base p-2 border-black">{{ $cutiDetail ? $cutiDetail->nama_layanan : '' }}</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='4' width='85%' class='border-2 border-y-black  px-3'>&nbsp;</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='4' class="font-bold text-base p-2">III. ALASAN CUTI</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='4' class="text-base p-2">{{ $cutiDetail ? $cutiDetail->deskripsi : '' }}</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='4' width='85%' class='border-2 border-t-black  px-3'>&nbsp;</td>
                    </tr>
                </table>
                <table class="w-200 border-collapse border-2 mx-15 border-black">
                    <tr class="border-2 border-black">
                        <td colspan='4' class="font-bold text-base p-2">IV. LAMANYA CUTI</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='15%' class='border-2 border-black text-center'>SELAMA</td>
                        <td width='20%' class='border-2 border-black text-center'>{{ $cutiDetail ? $cutiDetail->lama_cuti . ' ' . $cutiDetail->satuan_waktu : '' }}</td>
                        <td width='20%' class='border-2 border-black text-center'>MULAI TANGGAL</td>
                        <td width='15%' class='border-2 border-black text-center'>{{ $cutiDetail ? tglindonesia($cutiDetail->tgl_mulai) : '' }}</td>
                        <td width='10%' class='border-2 border-black text-center'>S.D</td>
                        <td width='20%' class='border-2 border-black text-center'>{{ $cutiDetail ? tglindonesia($cutiDetail->tgl_selesai) : '' }}</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='6' width='85%' class='border-2 border-t-black  px-3'>&nbsp;</td>
                    </tr>
                </table>

                <!-- cetak table bagian khusus untuk PNS-->
                <template x-if="id_status_pegawai != 3">
                    <table class="w-200 border-collapse border-2 mx-15 border-black">
                        <tr class="border-2 border-black">
                            <td colspan='6' class="font-bold text-base p-2">V. CATATAN CUTI</td>
                        </tr>
                        <tr class="border-2 border-black">
                            <td colspan='3' class="border-2 border-black pl-3 text-base">1. CUTI TAHUNAN</td>
                            <td width='40%' class="border-2 border-black pl-3 text-base">2. CUTI BESAR</td>
                            <td width='20%' class="border-2 border-black pl-3 text-base">&nbsp;</td>
                        </tr>
                        <tr class="border-2 border-black">
                            <td class="border-2 border-black text-center text-base">TAHUN</td>
                            <td class="border-2 border-black text-center text-base">SISA</td>
                            <td class="border-2 border-black text-center text-base">KETERANGAN</td>
                            <td width='40%' class="border-2 border-black pl-3 text-base">3. CUTI SAKIT</td>
                            <td width='20%' class="border-2 border-black pl-3 text-base">&nbsp;</td>
                        </tr>
                        <tr class="border-2 border-black">
                            <td class="border-2 border-black pl-5 text-base">N-2</td>
                            <td class="border-2 border-black text-center text-base">{{ $hitunganCuti['total_cuti_last_two_year'] == 12 ? '' : $hitunganCuti['total_cuti_last_two_year']}}</td>
                            <td class="border-2 border-black text-center text-base">&nbsp;</td>
                            <td width='40%' class="border-2 border-black pl-3 text-base">4. CUTI MELAHIRKAN</td>
                            <td width='20%' class="border-2 border-black pl-3 text-base">&nbsp;</td>
                        </tr>
                        <tr class="border-2 border-black">
                            <td width='10%' class="border-2 border-black pl-5 text-base">N-1</td>
                            <td width='10%' class="border-2 text-center border-black text-base">{{ $hitunganCuti['total_cuti_last_year'] == 12 ? '' : $hitunganCuti['total_cuti_last_year'] }}</td>
                            <td width='10%' class="border-2 border-black pl-3 text-base">&nbsp;</td>
                            <td width='40%' class="border-2 border-black pl-3 text-base">5. CUTI KARENA ALASAN PENTING</td>
                            <td width='20%' class="border-2 border-black pl-3 text-base">&nbsp;</td>
                        </tr>
                        <tr class="border-2 border-black">
                            <td class="border-2 border-black pl-5 text-base">N</td>
                            <td class="border-2 border-black text-center text-base">{{ $hitunganCuti['total_cuti_now'] == 12 ? '' : $hitunganCuti['total_cuti_now'] }}</td>
                            <td class="border-2 border-black pl-3 text-base">&nbsp;</td>
                            <td width=40%' class="border-2 border-black pl-3 text-base">6. CUTI DILUAR TANGGUNGAN NEGARA</td>
                            <td width='20%' class="border-2 border-black pl-3 text-base">&nbsp;</td>
                        </tr>
                        <tr class="border-2 border-black">
                            <td colspan='6' width='85%' class='border-2 border-t-black  px-3'>&nbsp;</td>
                        </tr>
                    </table>
                </template>

                <!-- cetak table bagian khusus untuk PPPK-->
                <template x-if="id_status_pegawai == 3">
                    <table class="w-200 border-collapse border-2 mx-15 border-black">
                        <tr class="border-2 border-black">
                            <td colspan='2' class="font-bold text-base p-2">V. CATATAN CUTI***</td>
                        </tr>
                        <tr class="border-2 border-black">
                            <td width='40%' class="border-2 border-black pl-3 text-base">1. CUTI TAHUNAN</td>
                            <td width='60%' class="border-2 border-black pl-3 text-base">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width='40%' class="border-2 border-black pl-3 text-base">2. CUTI SAKIT</td>
                            <td width='60%' class="border-2 border-black pl-3 text-base">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width='40%' class="border-2 border-black pl-3 text-base">2. CUTI MELAHIRKAN</td>
                            <td width='60%' class="border-2 border-black pl-3 text-base">&nbsp;</td>
                        </tr>
                        <tr class="border-2 border-black">
                            <td colspan='2' width='85%' class='border-2 border-t-black  px-3'>&nbsp;</td>
                        </tr>
                    </table>
                </template>


                <table class="w-200 border-collapse border-2 mx-15 border-black">
                    <tr class="border-2 border-black">
                        <td colspan='3' class="font-bold text-base p-2">VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='50%' class="border-2 border-black text-base p-2">{{ $cutiDetail ? $cutiDetail->alamat : '' }}</td>
                        <td width='20%' class="border-2 border-black text-base p-2">TELPON</td>
                        <td width='30%' class="border-2 border-black text-base p-2">{{ $cutiDetail ? $cutiDetail->no_telepon : '' }}</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='50%' class="border-2 border-black text-base p-2">&nbsp;</td>
                        <td colspan='2' class="border-2 border-black text-center text-base p-2">Hormat Saya,<br><br><br>({{$data_pegawai ? nama_gelar($data_pegawai->nama_pegawai,$data_pegawai->gelar_depan,$data_pegawai->gelar_belakang):''}})<br>NIP. {{$data_pegawai ? str_replace(' ', '', $data_pegawai->nip):'' }}</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='3' width='85%' class='border-2 border-t-black  px-3'>&nbsp;</td>
                    </tr>
                </table>
                <table class="w-200 border-collapse border-2 mx-15 border-black">
                    <tr class="border-2 border-black">
                        <td colspan='4' class="font-bold text-base p-2">VII. PERTIMBANGAN ATASAN LANGSUNG**</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='25%' class="border-2 border-black text-base p-2">DISETUJUI</td>
                        <td width='25%' class="border-2 border-black text-base p-2">PERUBAHAN****</td>
                        <td width='25%' class="border-2 border-black text-base p-2">DITANGGUHKAN****</td>
                        <td width='25%' class="border-2 border-black text-base p-2">TIDAK DISETUJUI****</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='25%' class="border-2 border-black text-base p-2">&nbsp;</td>
                        <td width='25%' class="border-2 border-black text-base p-2">&nbsp;</td>
                        <td width='25%' class="border-2 border-black text-base p-2">&nbsp;</td>
                        <td width='25%' class="border-2 border-black text-base p-2">&nbsp;</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='2' class="border-2 border-black text-base p-2">&nbsp;</td>
                        <td colspan='2'
                            class="border-2 border-black text-center text-base p-2"
                            x-show="ttdMode == 'elektronik'">
                            Atasan Langsung,<br><br><br><br><br>
                        </td>

                        <td colspan='2'
                            class="border-2 border-black text-center text-base p-2"
                            x-show="ttdMode == 'basah'">
                            Atasan Langsung,<br><br><br>({{$atasan_data ? nama_gelar($atasan_data->nama_pegawai,$atasan_data->gelar_depan,$atasan_data->gelar_belakang):''}})<br>NIP. {{$atasan_data ? str_replace(' ', '', $atasan_data->nip):'' }}
                        </td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='4' width='85%' class='border-2 border-t-black  px-3'>&nbsp;</td>
                    </tr>
                </table>
                <table class="w-200 border-collapse border-2 mx-15 border-black">
                    <tr class="border-2 border-black">
                        <td colspan='4' class="font-bold text-base p-2">VIII. PERTIMBANGAN PEJABAT BERWENANG YANG MEMBERIKAN CUTI**</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='25%' class="border-2 border-black text-base p-2">DISETUJUI</td>
                        <td width='25%' class="border-2 border-black text-base p-2">PERUBAHAN****</td>
                        <td width='25%' class="border-2 border-black text-base p-2">DITANGGUHKAN****</td>
                        <td width='25%' class="border-2 border-black text-base p-2">TIDAK DISETUJUI****</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td width='25%' class="border-2 border-black text-base p-2">&nbsp;</td>
                        <td width='25%' class="border-2 border-black text-base p-2">&nbsp;</td>
                        <td width='25%' class="border-2 border-black text-base p-2">&nbsp;</td>
                        <td width='25%' class="border-2 border-black text-base p-2">&nbsp;</td>
                    </tr>
                    <tr class="border-2 border-black">
                        <td colspan='2' class="border-2 border-black text-base p-2">&nbsp;</td>
                        <!-- <td colspan='2' class="border-2 border-black text-center text-base p-2">Pejabat Yang Berwenang Memberikan Cuti,<br><br><br>({{$kaopd_data ? $kaopd_data->nama_pegawai:''}})<br>NIP. {{$kaopd_data ? str_replace(' ', '', $kaopd_data->nip):'' }}</td> -->
                        <td colspan='2' class="border-2 border-black text-center text-base p-2">Pejabat Yang Berwenang Memberikan Cuti,<br><br><br><br><br></td>
                    </tr>
                </table>

                <div class="mt-8 flex justify-end items-center w-full">
                    <!-- <button
                        @click="tte()"
                        class="ml-6 px-6 py-2 bg-blue-300 rounded-xl text-center">
                        Tanda tangani Elektronik
                    </button> -->
                    <button
                        @click="printFormulir()"
                        class="ml-6 px-6 py-2 bg-green-300 rounded-xl text-center">
                        Unduh Formulir
                    </button>
                </div>

            </div>

        </div>
    </div>
    <div class="mt-8 flex justify-between items-center w-full">
        <button
            @click="prevStep()"
            class="ml-6 px-6 py-2 bg-gray-300 rounded-xl text-center">
            Sebelumnya
        </button>

        <button
            @click="nextFromStep(0, 3)"
            class="px-6 py-2 bg-blue-600 text-white text-right rounded-xl hover:bg-blue-700 disabled:opacity-40"
            :disabled="!selectedJenis">
            Lanjut
        </button>
    </div>
</div>