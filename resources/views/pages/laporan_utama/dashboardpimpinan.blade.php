@extends('layouts.app')
@section('title', 'Dashboard Pimpinan')
@section('content')

@php
$reportDate = tglindonesia(now()->toDateString());
@endphp

<div class="max-w-7xl mx-auto p-4">

    <main class="flex-1 p-2 lg:p-2 space-y-6">

        <!-- Page Title Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Dashboard Data Kepegawaian</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Laporan resmi data statistik ASN per <span class="font-bold text-slate-700 dark:text-slate-200">{{ $reportDate }}</span></p>
            </div>

            <!--<div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Cetak PDF
                </button>
                <button class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition shadow-lg shadow-brand-500/20 flex items-center gap-2">
                    <i class="fa-solid fa-file-excel"></i> Ekspor Data
                </button>
            </div>-->
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Card 1: Total ASN -->
            <div class="bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total ASN Aktif</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-brand-600 flex items-center justify-center">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $akumulasi->total_asn ? number_format($akumulasi->total_asn, 0, ',', '.') : '0' }}</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-full">
                        Aktif
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-2">Termasuk PNS, CPNS, & PPPK</p>
            </div>

            <!-- Card 2: Net Mutasi -->
            <div class="bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Mutasi Pindah {{ date('Y') }}</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-right-left text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $data['pindah_masuk']-$data['pindah_keluar'] >0 ? '+': '-' }}{{ $data['pindah_masuk']-$data['pindah_keluar'] }}</span>
                    <span class="text-xs font-bold text-indigo-600">Net Growth</span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-2">{{ $data['pindah_masuk'] }} Masuk <span class="text-slate-300 dark:text-slate-600">|</span> {{ $data['pindah_keluar'] }} Keluar</p>
            </div>

            <!-- Card 3: Pemberhentian ASN -->
            <div class="bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pemberhentian ASN</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-user-xmark text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $akumulasi->total_berhenti ? number_format($akumulasi->total_berhenti, 0, ',', '.') : '0' }}</span>
                    <span class="text-[11px] font-bold text-amber-600 bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 rounded-full">{{ $data['pensiun_bup'] }} Pensiun</span>
                </div>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-2">BUP, Wafat, Resign, & Sanksi</p>
            </div>

            <!-- Card 4: Total Hukuman Disiplin -->
            <div class="bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hukuman Disiplin</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-500/10 text-rose-600 flex items-center justify-center">
                        <i class="fa-solid fa-scale-unbalanced text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $akumulasi->total_hukuman ? number_format($akumulasi->total_hukuman, 0, ',', '.') : '0' }}</span>
                    <span class="text-[11px] font-bold text-rose-600 bg-rose-50 dark:bg-rose-500/10 px-2 py-0.5 rounded-full">Total Kasus</span>
                </div>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-2">{{ $data['hukdis_ringan'] ?? 0 }} Ringan | {{ $data['hukdis_sedang'] ?? 0 }} Sedang | {{ $data['hukdis_berat'] ?? 0 }} Berat</p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Chart 1: Komposisi ASN (Donut Chart) -->
            <div class="bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-base font-bold text-slate-800 dark:text-white">Komposisi Jenis ASN</h3>
                        <span class="text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-2.5 py-1 rounded-lg">{{ $reportDate }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">Total {{ $data['total'] ?? 0 }} Pegawai Terdaftar</p>
                    <div id="asnCompositionChart" class="w-full flex items-center justify-center py-2"></div>
                </div>

                <!-- Sub Breakdown Grid -->
                <div class="grid grid-cols-2 gap-2 pt-4 border-t border-slate-100 dark:border-dark-border text-xs">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-500 flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> PNS</span>
                        <span class="font-bold text-slate-800 dark:text-white">{{ $data['pns'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-500 flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-400"></span> PPPK PW</span>
                        <span class="font-bold text-slate-800 dark:text-white">{{ $data['pppk_pw'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-500 flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#f16384]"></span> PPPK</span>
                        <span class="font-bold text-slate-800 dark:text-white">{{ $data['pppk'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-500 flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> CPNS</span>
                        <span class="font-bold text-slate-800 dark:text-white">{{ $data['cpns'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card p-5 rounded-xl border border-gray-200 dark:border-dark-border shadow-sm">
                <h2 class="font-bold text-base mb-1">Komposisi Jenis Kelamin</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Sebaran Pegawai Laki-Laki vs Perempuan</p>
                <div id="chartJenisKelamin" class="h-64"></div>
            </div>

            <div class="bg-white dark:bg-dark-card p-5 rounded-xl border border-gray-200 dark:border-dark-border shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                        <div>
                            <h2 class="font-bold text-base">Golongan PNS / CPNS</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Distribusi Pegawai Negeri Sipil</p>
                        </div>
                        <!-- UI Tab Switch -->
                        <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-medium self-start sm:self-auto">
                            <button id="tabGolUtama" class="px-3 py-1.5 rounded-md bg-white dark:bg-dark-card shadow text-blue-600 dark:text-blue-400 transition-all">
                                Per Golongan (I-IV)
                            </button>
                            <button id="tabGolRuang" class="px-3 py-1.5 rounded-md text-gray-500 hover:text-gray-900 dark:hover:text-gray-200 transition-all">
                                Per Golongan Ruang (a/b/c/d/e)
                            </button>
                        </div>
                    </div>
                </div>
                <div id="chartGolonganPNS" class="h-64"></div>
            </div>

            <!-- Chart Golongan PPPK (Gol I - XVII) -->
            <div class="bg-white dark:bg-dark-card p-5 rounded-xl border border-gray-200 dark:border-dark-border shadow-sm">
                <h2 class="font-bold text-base mb-1">Golongan PPPK (Gol I s/d XVII)</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Sebaran Pegawai PPPK & PPPK Paruh Waktu berdasarkan Golongan</p>
                <div id="chartGolonganPPPK" class="h-64"></div>
            </div>

            <div class="bg-white dark:bg-dark-card p-5 rounded-xl border border-gray-200 dark:border-dark-border shadow-sm">
                <h2 class="font-bold text-base mb-1">Per Tingkat Pendidikan</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Distribusi Kualifikasi Pendidikan Pegawai (SD s/d S3)</p>
                <div id="chartPendidikan" class="h-64"></div>
            </div>

            <div class="bg-white dark:bg-dark-card p-5 rounded-xl border border-gray-200 dark:border-dark-border shadow-sm">
                <h2 class="font-bold text-base mb-1">Per Kelompok Jabatan</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Sebaran Tenaga Guru, Tenaga Kesehatan, dan Teknis/Lainnya</p>
                <div id="chartKelompokJabatan" class="h-64"></div>
            </div>

            <div class="bg-white dark:bg-dark-card p-5 rounded-xl border border-gray-200 dark:border-dark-border shadow-sm">
                <h2 class="font-bold text-base mb-1">Per Jenis Jabatan</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Distribusi Jabatan Struktural, Fungsional (JFT), dan Pelaksana (JFU)</p>
                <div id="chartJenisJabatan" class="h-64"></div>
            </div>

            <!-- Chart 2: Hukuman Disiplin Breakdown (Stacked Bar Chart) -->
            <div class="lg:col-span-2 bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border rounded-2xl p-5 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white">Distribusi Hukuman Disiplin ({{ $akumulasi->total_hukuman ? number_format($akumulasi->total_hukuman) : 0 }} Kasus)</h3>
                        <p class="text-xs text-slate-500">Rincian penjatuhan sanksi tingkat Ringan, Sedang, dan Berat</p>
                    </div>
                    <span class="text-xs font-bold text-rose-600 bg-rose-50 dark:bg-rose-500/10 px-3 py-1 rounded-xl">Evaluasi Disiplin</span>
                </div>
                <div id="disciplinaryChart" class="w-full h-72"></div>
            </div>

        </div>

        <div class="bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border rounded-2xl p-5 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Tabel Master Rekapitulasi Data Kepegawaian</h3>
                    <p class="text-xs text-slate-500">Rincian 15 kategori data kepegawaian ASN Kota Medan per {{ $reportDate }}</p>
                </div>

                <!-- Category Filter Tabs -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                    <button onclick="filterCategory('all')" class="filter-tab active px-3 py-1.5 rounded-lg text-xs font-bold bg-brand-600 text-white transition">Semua (15)</button>
                    <button onclick="filterCategory('ASN')" class="filter-tab px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 transition">ASN</button>
                    <button onclick="filterCategory('Mutasi')" class="filter-tab px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 transition">Mutasi</button>
                    <button onclick="filterCategory('Pemberhentian')" class="filter-tab px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 transition">Pemberhentian</button>
                    <button onclick="filterCategory('Hukuman')" class="filter-tab px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 transition">Hukuman</button>
                </div>
            </div>

            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table id="summary-table" class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-dark-border">
                        <tr>
                            <th class="py-3.5 px-4 w-16">NO</th>
                            <th class="py-3.5 px-4">JENIS DATA KEPEGAWAIAN</th>
                            <th class="py-3.5 px-4">KATEGORI</th>
                            <th class="py-3.5 px-4 text-right">JUMLAH PEGAWAI</th>
                            <!-- <th class="py-3.5 px-4 text-center">STATUS DATA</th> -->
                        </tr>
                    </thead>
                    <tbody id="table-body" class="divide-y divide-slate-100 dark:divide-dark-border">

                    </tbody>
                </table>
            </div>
        </div>

    </main>

</div>

<style>
    #asnCompositionChart .apexcharts-tooltip,
    #asnCompositionChart .apexcharts-tooltip * {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    #chartJenisKelamin .apexcharts-tooltip,
    #chartJenisKelamin .apexcharts-tooltip * {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    #chartKelompokJabatan .apexcharts-tooltip,
    #chartKelompokJabatan .apexcharts-tooltip * {
        color: #ffffff !important;
        font-weight: 700 !important;
    }
</style>

<script>
    const summaryData = @json($report).map((item, index) => ({
        no: index + 1,
        id: item.id ?? '',
        name: `${item.nama_data ?? ''} ${Number(item.id) === 1 ? @json(tglindonesia(now()->toDateString())) : @json(now()->format('Y'))}`,
        category: item.kategori ?? '',
        count: Number(item.nilai_data ?? 0).toLocaleString('id-ID')
        //status: item.id_status ?? ''
    }));

    const datum = @json($data);

    // Render Table Function
    function renderTable(data) {
        const tbody = document.getElementById('table-body');
        tbody.innerHTML = '';

        data.forEach(item => {
            const tr = document.createElement('tr');
            const detailUrl = @json(route('detail.report', ['id_data' => '__ID__']))
                .replace('__ID__', encodeURIComponent(item.id));
            tr.className = 'hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition';
            tr.innerHTML = `
                    <td class="py-3.5 px-4 font-bold text-slate-400">${item.no}</td>
                    <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100"><a href="${detailUrl}">${item.name}</a></td>
                    <td class="py-3.5 px-4">
                        <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">${item.category}</span>
                    </td>
                    <td class="py-3.5 px-4 text-right font-extrabold text-slate-900 dark:text-white text-sm">
                        ${item.count}
                    </td>
                    <!-- <td class="py-3.5 px-4 text-center">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold ${item.badge}">${item.status}</span>
                    </td>
                `;
            tbody.appendChild(tr);
        });
    }

    // Search Filter
    function filterSummaryTable() {
        const query = document.getElementById('global-search').value.toLowerCase();
        const filtered = summaryData.filter(item =>
            item.name.toLowerCase().includes(query) ||
            item.category.toLowerCase().includes(query)
        );
        renderTable(filtered);
    }

    // Category Filter Tabs
    function filterCategory(cat) {
        document.querySelectorAll('.filter-tab').forEach(btn => {
            btn.classList.remove('bg-brand-600', 'text-white', 'active');
            btn.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
        });
        event.target.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
        event.target.classList.add('bg-brand-600', 'text-white', 'active');

        if (cat === 'all') {
            renderTable(summaryData);
        } else {
            const filtered = summaryData.filter(item => item.category.includes(cat));
            renderTable(filtered);
        }
    }

    // Navigation Sidebar Toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    // Dark Mode Toggle
    function toggleDarkMode() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        renderCharts();
    }

    // Dropdown Toggle
    function toggleDropdown(id) {
        const el = document.getElementById(id);
        el.classList.toggle('hidden');
    }





    let chartAsn, chartDiscipline, chartGolongan;

    function renderCharts() {
        const isDark = document.documentElement.classList.contains('dark');
        const fontColor = isDark ? '#94a3b8' : '#64748b';

        if (chartAsn) chartAsn.destroy();
        if (chartDiscipline) chartDiscipline.destroy();
        if (chartGolongan) chartGolongan.destroy();

        // 1. Donut Chart: Komposisi ASN
        const optionsAsn = {
            series: [datum.pns, datum.pppk_pw, datum.pppk, datum.cpns],
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: 'Plus Jakarta Sans',
                background: 'transparent'
            },
            labels: ['PNS', 'PPPK Paruh Waktu', 'PPPK', 'CPNS'],
            colors: ['#2563eb', '#38bdf8', '#f16384', '#fbbf24'],
            legend: {
                show: false
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '13px',
                    fontFamily: 'Plus Jakarta Sans'
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (percentage) => `${percentage.toFixed(1)}%`,
                style: {
                    colors: ['#ffffff'],
                    fontSize: 12,
                    fontWeight: 800
                },
                dropShadow: {
                    enabled: false
                }
            },
            stroke: {
                colors: [isDark ? '#111112' : '#e7e0e0'],
                width: 2
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total ASN',
                                color: fontColor,
                                formatter: () => datum.total.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            }
        };

        chartAsn = new ApexCharts(document.querySelector("#asnCompositionChart"), optionsAsn);
        chartAsn.render();

        //2. Bar chart komposisi Golongan Ruang ASN

        const dataGolUtama = {
            categories: ['Gol I', 'Gol II', 'Gol III', 'Gol IV'],
            series: [{
                name: 'Jumlah PNS',
                data: [datum.asn_gol_1, datum.asn_gol_2, datum.asn_gol_3, datum.asn_gol_4]
            }]
        };

        const dataGolRuang = {
            categories: [
                'I/a', 'I/b', 'I/c', 'I/d',
                'II/a', 'II/b', 'II/c', 'II/d',
                'III/a', 'III/b', 'III/c', 'III/d',
                'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e'
            ],
            series: [{
                name: 'Jumlah PNS',
                data: [datum.asn_1a, datum.asn_1b, datum.asn_1c, datum.asn_1d, datum.asn_2a, datum.asn_2b, datum.asn_2c, datum.asn_2d, datum.asn_3a, datum.asn_3b, datum.asn_3c, datum.asn_3d, datum.asn_4a, datum.asn_4b, datum.asn_4c, datum.asn_4d, datum.asn_4e]
            }]
        };

        const optionsGolongan = {
            series: dataGolUtama.series,
            chart: {
                type: 'bar',
                height: 280,
                toolbar: {
                    show: false
                }
            },
            colors: ['#2563eb'],
            plotOptions: {
                bar: {
                    borderRadius: 5,
                    horizontal: false
                }
            },
            xaxis: {
                categories: dataGolUtama.categories
            }
        };
        chartGolongan = new ApexCharts(document.querySelector("#chartGolonganPNS"), optionsGolongan);
        chartGolongan.render();

        // Tab Event Listeners
        const tabGolUtama = document.getElementById('tabGolUtama');
        const tabGolRuang = document.getElementById('tabGolRuang');

        tabGolUtama.addEventListener('click', () => {
            tabGolUtama.className = "px-3 py-1.2 rounded-md bg-white dark:bg-dark-card shadow text-blue-600 dark:text-blue-400 transition-all";
            tabGolRuang.className = "px-3 py-1.2 rounded-md text-gray-500 hover:text-gray-900 dark:hover:text-gray-200 transition-all";

            chartGolongan.updateOptions({
                xaxis: {
                    categories: dataGolUtama.categories
                },
                series: dataGolUtama.series
            });
        });

        tabGolRuang.addEventListener('click', () => {
            tabGolRuang.className = "px-3 py-1.5 rounded-md bg-white dark:bg-dark-card shadow text-blue-600 dark:text-blue-400 transition-all";
            tabGolUtama.className = "px-3 py-1.5 rounded-md text-gray-500 hover:text-gray-900 dark:hover:text-gray-200 transition-all";

            chartGolongan.updateOptions({
                xaxis: {
                    categories: dataGolRuang.categories,
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                series: dataGolRuang.series
            });
        });

        // 3. PPPK
        new ApexCharts(document.querySelector("#chartGolonganPPPK"), {
            series: [{
                name: 'Jumlah PPPK',
                data: [datum.p3k_gol_i, datum.p3k_gol_ii, datum.p3k_gol_iii, datum.p3k_gol_iv,
                    datum.p3k_gol_v, datum.p3k_gol_vi, datum.p3k_gol_vii, datum.p3k_gol_viii,
                    datum.p3k_gol_ix, datum.p3k_gol_x, datum.p3k_gol_xi, datum.p3k_gol_xii,
                    datum.p3k_gol_xiii, datum.p3k_gol_xiv, datum.p3k_gol_xv, datum.p3k_gol_xvi, datum.p3k_gol_xvii
                ]
            }],
            chart: {
                type: 'bar',
                height: 260,
                toolbar: {
                    show: false
                }
            },
            colors: ['#059669'],
            plotOptions: {
                bar: {
                    borderRadius: 2,
                    horizontal: false
                }
            },
            xaxis: {
                categories: ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII'],
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            }
        }).render();

        // 4. Gender
        new ApexCharts(document.querySelector("#chartJenisKelamin"), {
            series: [datum.laki2, datum.perempuan],
            labels: ['Laki-Laki', 'Perempuan'],
            dataLabels: {
                enabled: true,
                style: {
                    colors: ['#ffffff'],
                    fontSize: 12,
                    fontWeight: 800
                },
                dropShadow: {
                    enabled: false
                }
            },
            chart: {
                type: 'pie',
                height: 260
            },
            colors: ['#6c94ec', '#e899c0'],
            legend: {
                position: 'bottom'
            }
        }).render();



        //5. Pendidikan
        new ApexCharts(document.querySelector("#chartPendidikan"), {
            series: [{
                name: 'Jumlah Pegawai',
                data: [datum.sd, datum.smp, datum.sma, datum.d2d3, datum.s1, datum.s2, datum.s3]
            }],
            chart: {
                type: 'bar',
                height: 260,
                toolbar: {
                    show: false
                }
            },
            colors: ['#10b981'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false
                }
            },
            xaxis: {
                categories: ['SD', 'SMP', 'SMA', 'D2/D3', 'S1/D4', 'S2', 'S3']
            }
        }).render();

        // 6.Kelompok Jabatan
        new ApexCharts(document.querySelector("#chartKelompokJabatan"), {
            series: [datum.guru, datum.nakes, datum.teknis],
            labels: ['Guru', 'Nakes (Kesehatan)', 'Teknis / Lainnya'],
            chart: {
                type: 'donut',
                height: 260
            },
            colors: ['#f59e0b', '#06b6d4', '#64748b'],
            legend: {
                position: 'bottom'
            }
        }).render();

        // 7. Jenis Jabatan
        new ApexCharts(document.querySelector("#chartJenisJabatan"), {
            series: [{
                name: 'Jumlah ASN',
                data: [datum.struktural, datum.fungsional, datum.pelaksana]
            }],
            chart: {
                type: 'bar',
                height: 260,
                toolbar: {
                    show: false
                }
            },
            colors: ['#3b82f6'],
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: true
                }
            },
            xaxis: {
                categories: ['Struktural', 'Fungsional (JFT)', 'Pelaksana (JFU)'],
                labels: {
                    show: false
                },
                axisBorder: {
                    show: true
                },
                axisTicks: {
                    show: false
                }
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false
                    }
                }
            }
        }).render();

        // 8. Bar Chart: Hukuman Disiplin Breakdown
        const optionsDiscipline = {
            series: [{
                    name: 'Ringan (' + datum.hukdis_ringan + ')',
                    data: [datum.teguran_lisan, datum.teguran_tertulis, datum.perny_tidak_puas, 0, 0, 0, 0, 0, 0]
                },
                {
                    name: 'Sedang (' + datum.hukdis_sedang + ')',
                    data: [0, 0, 0, datum.tunda_gb1, datum.tunda_pangkat1, datum.turun_pangkat1, datum.potong_gaji, 0, 0]
                },
                {
                    name: 'Berat (' + datum.hukdis_berat + ')',
                    data: [0, 0, 0, 0, 0, 0, 0, datum.pemberhentian, datum.sanksi_berat_lain]
                }
            ],
            chart: {
                type: 'bar',
                height: 280,
                stacked: true,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans',
                background: 'transparent'
            },
            colors: ['#facc15', '#f59e0b', '#e11d48'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '45%',
                    borderRadius: 6
                }
            },
            xaxis: {
                categories: ['Teguran Lisan', 'Teguran Tertulis', 'Pernyataan Tdk Puas', 'Penundaan KGB 1Thn', 'Penundaan Pangkat', 'Penurunan Pangkat', 'Potong Gaji PPPK', 'Pemberhentian PNS', 'Sanksi Berat Lain'],
                labels: {
                    style: {
                        colors: fontColor,
                        fontSize: '10px'
                    },
                    rotate: -25
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: fontColor
                    }
                }
            },
            grid: {
                borderColor: isDark ? '#334155' : '#f1f5f9'
            },
            legend: {
                position: 'top',
                labels: {
                    colors: fontColor
                }
            }
        };
        chartDiscipline = new ApexCharts(document.querySelector("#disciplinaryChart"), optionsDiscipline);
        chartDiscipline.render();
    }

    // Initialize App on Load
    window.onload = function() {
        renderTable(summaryData);
        renderCharts();
    };
</script>
@endsection