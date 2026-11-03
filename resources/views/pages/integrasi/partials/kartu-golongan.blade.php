<section class="flex h-[36rem] flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <div>
        <h2 class="text-lg font-bold text-slate-900">Per Golongan/Ruang (PNS & CPNS)</h2>
        <p class="mt-1 text-xs text-slate-500">Klik baris golongan untuk membuka atau menutup rincian ruang.</p>
    </div>
    <div class="mt-4 min-h-0 flex-1 overflow-y-auto">
        <table class="w-full text-sm">
            <thead class="sticky top-0 z-10 bg-white">
                <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-wide text-slate-500">
                    <th class="py-2 pr-3">Golongan/Ruang</th>
                    <th class="py-2 px-3 text-right">SIMPEG</th>
                    <th class="py-2 px-3 text-right">SIASN</th>
                    <th class="py-2 pl-3 text-right">Selisih</th>
                </tr>
            </thead>
            @foreach ($perGolongan as $golongan)
            @php $selisihGolongan = $golongan['simpeg'] - $golongan['siasn']; @endphp
            {{-- golongan yang berselisih langsung terbuka --}}
            <tbody x-data="{ buka: {{ $selisihGolongan != 0 && $adaDataSiasn ? 'true' : 'false' }} }">
                <tr class="cursor-pointer border-b border-slate-200 bg-slate-50 hover:bg-slate-100" @click="buka = !buka">
                    <td class="py-2 pr-3 font-bold text-slate-900">
                        <span class="inline-block w-4 text-slate-400 transition-transform" :class="buka ? 'rotate-90' : ''">&#9656;</span>
                        {{ $golongan['kategori'] }}
                    </td>
                    <td class="py-2 px-3 text-right font-semibold text-slate-800">{{ number_format($golongan['simpeg']) }}</td>
                    <td class="py-2 px-3 text-right font-semibold text-slate-800">{{ number_format($golongan['siasn']) }}</td>
                    <td class="py-2 pl-3 text-right">@include('pages.integrasi.partials.badge-selisih', ['selisih' => $selisihGolongan])</td>
                </tr>
                @foreach ($golongan['ruang'] as $ruang)
                @php $selisih = $ruang['simpeg'] - $ruang['siasn']; @endphp
                <tr class="border-b border-slate-100 {{ $selisih != 0 && $adaDataSiasn ? 'bg-red-50/60' : '' }}" x-show="buka"
                    @if ($selisihGolongan == 0 || !$adaDataSiasn) style="display: none" @endif>
                    <td class="py-2 pr-3 pl-6 text-slate-700">Ruang {{ $ruang['kategori'] }}</td>
                    <td class="py-2 px-3 text-right text-slate-700">{{ number_format($ruang['simpeg']) }}</td>
                    <td class="py-2 px-3 text-right text-slate-700">{{ number_format($ruang['siasn']) }}</td>
                    <td class="py-2 pl-3 text-right">@include('pages.integrasi.partials.badge-selisih', ['selisih' => $selisih])</td>
                </tr>
                @endforeach
            </tbody>
            @endforeach
        </table>
    </div>
</section>
