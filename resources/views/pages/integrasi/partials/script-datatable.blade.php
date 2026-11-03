{{-- Inisialisasi DataTables di Dashboard Integrasi (library DataTables 2 dimuat di layouts.app) --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bahasa = {
            search: 'Cari:',
            lengthMenu: '_MENU_ data per halaman',
            zeroRecords: 'Data tidak ditemukan',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data tersedia',
            infoFiltered: '(difilter dari _MAX_ total data)',
            loadingRecords: 'Memuat...',
            processing: 'Memproses...',
            emptyTable: 'Tidak ada data dalam tabel',
        };
        const teks = (nilai) => String(nilai ?? '-').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        const angka = (nilai) => Number(nilai).toLocaleString('en-US');

        // rincian pegawai: server-side karena bisa belasan ribu baris
        const urlDaftar = @js(route('integrasi.siasn.daftar'));
        const urlDetail = @js(route('integrasi.siasn.pegawai'));
        document.querySelectorAll('.tabel-pegawai-siasn').forEach((tabel) => {
            const denganAtribut = tabel.dataset.atribut === '1';
            new DataTable(tabel, {
                serverSide: true,
                processing: true,
                searchDelay: 400,
                pageLength: 25,
                order: [],
                language: bahasa,
                ajax: {
                    url: urlDaftar,
                    data: (d) => { d.jenis = tabel.dataset.jenis; },
                },
                columns: [
                    { data: 'no', orderable: false, width: '3rem' },
                    { data: 'nip', render: (d) => `<span class="font-mono text-xs">${teks(d)}</span>` },
                    { data: 'nama', render: (d) => `<span class="font-medium text-slate-800">${teks(d)}</span>` },
                    ...(denganAtribut
                        ? [{ data: 'atribut', render: (d) => (d ?? []).map((a) => `<span class="mb-0.5 mr-0.5 inline-block rounded-full bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700">${teks(a)}</span>`).join('') }]
                        : []),
                    {
                        data: 'nip',
                        orderable: false,
                        className: 'dt-right',
                        render: (d) => `<a href="${urlDetail}?nip=${encodeURIComponent(d)}" class="rounded-lg border border-blue-200 px-3 py-1 text-xs font-semibold text-blue-700 hover:bg-blue-50">Detail</a>`,
                    },
                ],
            });
        });

        // rekap per OPD: client-side, default diurutkan dari selisih terbesar
        const tabelUnit = document.getElementById('tabel-unit-kerja');
        if (tabelUnit) {
            const adaSiasn = tabelUnit.dataset.adaSiasn === '1';
            const dataUnit = JSON.parse(document.getElementById('data-unit-kerja').textContent);
            const tabel = new DataTable(tabelUnit, {
                data: dataUnit,
                pageLength: 10,
                lengthChange: false,
                language: bahasa,
                order: [[3, 'desc']],
                createdRow: (row, item) => {
                    if (adaSiasn && item.simpeg !== item.siasn) row.classList.add('bg-red-50/60');
                },
                columns: [
                    { data: 'kategori', render: (d) => `<span class="font-medium text-slate-800">${teks(d)}</span>` },
                    { data: 'simpeg', className: 'dt-right', render: (d, type) => type === 'display' ? angka(d) : d },
                    { data: 'siasn', className: 'dt-right', render: (d, type) => type === 'display' ? angka(d) : d },
                    {
                        data: null,
                        className: 'dt-right',
                        searchable: false,
                        render: (item, type) => {
                            const selisih = item.simpeg - item.siasn;
                            if (type !== 'display') return Math.abs(selisih);
                            if (!adaSiasn) return '<span class="text-xs text-slate-400">&ndash;</span>';
                            return selisih === 0
                                ? '<span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700">Sesuai</span>'
                                : `<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">${selisih > 0 ? '+' : ''}${angka(selisih)}</span>`;
                        },
                    },
                ],
            });

            document.getElementById('unit-kerja-hanya-selisih')?.addEventListener('change', (e) => {
                tabel.search.fixed('selisih', e.target.checked ? (teksBaris, item) => item.simpeg !== item.siasn : null);
                tabel.draw();
            });
        }

        // tabel di tab yang tersembunyi perlu dihitung ulang lebar kolomnya saat ditampilkan
        window.sesuaikanTabelSiasn = () => DataTable.tables({ visible: true, api: true }).columns.adjust();
    });
</script>
