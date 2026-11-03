@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
$totalPengajuan = $activeCount + $completedCount + $rejectedCount;
$statusLabels = ['diajukan' => 'Diajukan', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'Revisi' => 'Revisi'];
$statusColors = ['#2563eb', '#f59e0b', '#16a34a', '#dc2626', '#7c3aed'];
$stepIcons = ['clipboard-check.svg', 'accept.svg', 'check-mark.png', 'arrow.png', 'download.svg'];
$stepTones = [
['panel' => 'bg-blue-50', 'icon' => 'bg-blue-600', 'text' => 'text-blue-700'],
['panel' => 'bg-amber-50', 'icon' => 'bg-amber-500', 'text' => 'text-amber-700'],
['panel' => 'bg-cyan-50', 'icon' => 'bg-cyan-600', 'text' => 'text-cyan-700'],
['panel' => 'bg-violet-50', 'icon' => 'bg-violet-600', 'text' => 'text-violet-700'],
['panel' => 'bg-emerald-50', 'icon' => 'bg-emerald-600', 'text' => 'text-emerald-700'],
];
@endphp

<div class="bg-white border rounded-2xl shadow-sm p-5 mb-6">

    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Monitoring</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Proses Pensiun</h1>

        </div>
        <div class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-600 shadow-sm">
            Keadaan: <span class="font-semibold text-slate-900">{{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

</div>
<div class="bg-white border rounded-2xl shadow-sm">
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8 mt-5">


        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-slate-200 border-l-4 border-l-blue-600 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Total pengajuan</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalPengajuan) }}</p>
                <p class="mt-2 text-xs text-slate-500">Seluruh pengajuan masuk</p>
            </div>
            <div class="rounded-xl border border-slate-200 border-l-4 border-l-amber-500 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Dalam Proses</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($activeCount) }}</p>
                <p class="mt-2 text-xs text-amber-700">Proses Lanjutan di Admin</p>
            </div>
            <div class="rounded-xl border border-slate-200 border-l-4 border-l-emerald-600 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Selesai</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($completedCount) }}</p>
                <p class="mt-2 text-xs text-emerald-700">{{ $completionRate }}% dari total pengajuan</p>
            </div>
            <div class="rounded-xl border border-slate-200 border-l-4 border-l-red-500 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Ditolak/Revisi</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($rejectedCount) }}</p>
                <p class="mt-2 text-xs text-red-700">Pengajuan yang perlu diperbaiki</p>
            </div>
        </div>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-start">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Tahapan Proses Pensiun</h2>
                    <p class="mt-1 text-sm text-slate-500">Jumlah pengajuan yang sedang berada di setiap tahapan.</p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ count($steps) }} tahapan</span>
            </div>

            <div class="mt-8 overflow-x-auto pb-3">
                <div class="flex min-w-[980px] items-stretch gap-3">
                    @forelse($steps as $index => $step)
                    @php
                    $stage = $stepCounts->firstWhere('step', $step->urutan_step);
                    $stageTotal = $stage['total'] ?? 0;
                    $stagePercent = $totalPengajuan > 0 ? round(($stageTotal / $totalPengajuan) * 100) : 0;
                    $tone = $stepTones[$index % count($stepTones)];
                    $icon = $stepIcons[$index % count($stepIcons)];
                    @endphp
                    <div class="flex min-w-[180px] flex-1 flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                        <div class="{{ $tone['panel'] }} relative flex h-10 items-center justify-center">
                            <div class="absolute left-3 top-3 flex h-2 w-8 items-center justify-center rounded-full bg-white text-sm font-bold text-slate-700 shadow-sm">{{ $index + 1 }}</div>
                            <!-- <div class="{{ $tone['icon'] }} flex h-16 w-16 items-center justify-center rounded-sm shadow-sm"> -->
                            <!-- <img src="{{ asset('image/pengajuan-pensiun.png') }}" alt="" class="h-25 w-9 object-contain"> -->
                            <!-- </div> -->
                        </div>
                        <div class="flex flex-1 flex-col p-4">
                            <p class="min-h-[42px] text-sm font-bold leading-snug text-slate-800">{{ $step->nama_step }} {{ $step->urutan_step == 1 ? '(Proses Revisi)' : '' }}</p>
                            <div class="mt-4 flex items-end justify-between gap-2">
                                <p class="text-xs text-slate-500"><strong class="text-lg text-slate-900">{{ $stageTotal }}</strong><br>pengajuan</p>
                                <span class="text-xs font-semibold {{ $tone['text'] }}">{{ $stagePercent }}%</span>
                            </div>
                            <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $tone['icon'] }}" style="width: {{ $stagePercent }}%"></div>
                            </div>
                        </div>
                    </div>
                    @if($index < count($steps) - 1)
                        <div class="mt-[132px] flex shrink-0 items-center">
                        <img src="{{ asset('icons/chevron-right.svg') }}" alt="" class="h-5 w-5 opacity-40">
                </div>
                @endif
                @empty
                <p class="w-full py-6 text-center text-sm text-slate-500">Belum ada konfigurasi tahapan proses.</p>
                @endforelse
            </div>
    </div>
    </section>
    </section>

    <!-- <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 lg:col-span-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Distribusi per tahapan</h2>
                <p class="mt-1 text-sm text-slate-500">Beban pengajuan pada setiap titik proses.</p>
            </div>
            <div id="pensiunStageChart" class="mt-4 min-h-[280px]"></div>
        </section>
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 lg:col-span-2">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Status akhir</h2>
                <p class="mt-1 text-sm text-slate-500">Komposisi status seluruh pengajuan.</p>
            </div>
            <div id="pensiunStatusChart" class="mt-4 min-h-[280px]"></div>
            <div class="mt-2 grid grid-cols-2 gap-2 text-xs text-slate-600">
                @foreach($statusLabels as $status => $label)
                <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full" style="background: {{ $statusColors[$loop->index] }}"></span>{{ $label }} <strong class="text-slate-900">{{ $statusCount->get($status, 0) }}</strong></div>
                @endforeach
            </div>
        </section>
    </div> -->
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stageLabels = @json($labels);
        const stageTotals = @json($totals);
        const statusLabels = @json(array_values($statusLabels));
        const statusTotals = @json(array_values($statusCounts));

        if (window.ApexCharts) {
            new ApexCharts(document.querySelector('#pensiunStageChart'), {
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'inherit'
                },
                series: [{
                    name: 'Pengajuan',
                    data: stageTotals
                }],
                xaxis: {
                    categories: stageLabels,
                    labels: {
                        trim: true
                    }
                },
                colors: ['#2563eb'],
                plotOptions: {
                    bar: {
                        borderRadius: 5,
                        columnWidth: '48%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                grid: {
                    borderColor: '#e2e8f0',
                    strokeDashArray: 4
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true
                }
            }).render();

            new ApexCharts(document.querySelector('#pensiunStatusChart'), {
                chart: {
                    type: 'donut',
                    height: 280,
                    fontFamily: 'inherit'
                },
                series: statusTotals,
                labels: statusLabels,
                colors: @json($statusColors),
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '68%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function() {
                                        return {
                                            {
                                                $totalPengajuan
                                            }
                                        };
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: {
                    width: 3,
                    colors: ['#ffffff']
                }
            }).render();
        }
    });
</script>

@endsection