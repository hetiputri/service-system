{{-- Rekap per OPD dengan DataTables (client-side, jumlah OPD hanya puluhan), diinisialisasi di script-datatable --}}
@php $unitBerselisih = $adaDataSiasn ? collect($perUnitKerja)->filter(fn($item) => $item['simpeg'] != $item['siasn'])->count() : 0; @endphp
<section class="flex h-[36rem] flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-start justify-between gap-2">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Per Unit Kerja (OPD)</h2>
            @if ($adaDataSiasn)
            <p class="mt-1 text-xs text-slate-500">
                <span class="font-semibold text-red-700">{{ number_format($unitBerselisih) }}</span> dari
                {{ number_format(count($perUnitKerja)) }} unit kerja berselisih
            </p>
            @endif
        </div>
        @if ($adaDataSiasn)
        <label class="inline-flex shrink-0 cursor-pointer items-center gap-2 text-xs text-slate-600">
            <input type="checkbox" id="unit-kerja-hanya-selisih" class="rounded border-slate-300">
            Hanya yang berselisih
        </label>
        @endif
    </div>

    <div class="mt-3 min-h-0 flex-1 overflow-y-auto">
        <table id="tabel-unit-kerja" class="display w-full text-sm" data-ada-siasn="{{ $adaDataSiasn ? '1' : '0' }}">
            <thead>
                <tr>
                    <th>Unit Kerja</th>
                    <th>SIMPEG</th>
                    <th>SIASN</th>
                    <th>Selisih</th>
                </tr>
            </thead>
        </table>
        <script type="application/json" id="data-unit-kerja">@json($perUnitKerja)</script>
    </div>
</section>
