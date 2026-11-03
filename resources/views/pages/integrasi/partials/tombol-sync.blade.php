{{-- Tombol sync untuk baris yang dicentang. $arah: tujuan sync (siasn|simpeg), $alasan: sebab tombol nonaktif untuk seluruh pegawai (null = ikut centangan) --}}
@php
$keSiasn = $arah === 'siasn';
$warnaAktif = $keSiasn ? 'border-blue-600 bg-blue-600 text-white hover:bg-blue-700' : 'border-emerald-600 bg-emerald-600 text-white hover:bg-emerald-700';
$warnaNonaktif = 'cursor-not-allowed border-slate-200 bg-slate-50 text-slate-400';
@endphp
<button type="button"
    @if ($alasan)
    disabled title="{{ $alasan }}"
    class="inline-flex shrink-0 items-center whitespace-nowrap rounded-lg border px-2 py-1 text-xs font-semibold normal-case tracking-normal {{ $warnaNonaktif }}"
    @else
    :disabled="alasanSync('{{ $arah }}') !== ''"
    :title="alasanSync('{{ $arah }}')"
    @click="buka('{{ $arah }}', terpilih())"
    class="inline-flex shrink-0 items-center whitespace-nowrap rounded-lg border px-2 py-1 text-xs font-semibold normal-case tracking-normal"
    :class="alasanSync('{{ $arah }}') === '' ? '{{ $warnaAktif }}' : '{{ $warnaNonaktif }}'"
    @endif>
    {{ $keSiasn ? '' : '← ' }}{{ $label }}{{ $keSiasn ? ' →' : '' }}
    @unless ($alasan)
    <span class="ml-1 rounded-full bg-white/20 px-1.5" x-show="dipilih.length" x-text="dipilih.length"></span>
    @endunless
</button>
