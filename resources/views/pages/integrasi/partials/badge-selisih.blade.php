@if (!($adaDataSiasn ?? true))
<span class="text-xs text-slate-400">&ndash;</span>
@elseif ($selisih == 0)
<span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700">Sesuai</span>
@else
<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">{{ $selisih > 0 ? '+' : '' }}{{ number_format($selisih) }}</span>
@endif
