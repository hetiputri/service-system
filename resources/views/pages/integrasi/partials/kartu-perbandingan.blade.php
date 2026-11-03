<section class="h-full rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <h2 class="text-lg font-bold text-slate-900">Per {{ $judul }}</h2>
    <div class="mt-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-wide text-slate-500">
                    <th class="py-2 pr-3">Kategori</th>
                    <th class="py-2 px-3 text-right">SIMPEG</th>
                    <th class="py-2 px-3 text-right">SIASN</th>
                    <th class="py-2 pl-3 text-right">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($baris as $item)
                @php $selisih = $item['simpeg'] - $item['siasn']; @endphp
                <tr class="border-b border-slate-100 {{ $selisih != 0 && $adaDataSiasn ? 'bg-red-50/60' : '' }}">
                    <td class="py-2 pr-3 font-medium text-slate-800">{{ $item['kategori'] }}</td>
                    <td class="py-2 px-3 text-right text-slate-700">{{ number_format($item['simpeg']) }}</td>
                    <td class="py-2 px-3 text-right text-slate-700">{{ number_format($item['siasn']) }}</td>
                    <td class="py-2 pl-3 text-right">@include('pages.integrasi.partials.badge-selisih', ['selisih' => $selisih])</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
