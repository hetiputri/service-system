{{-- Riwayat sync satu pegawai di halaman detail (DataTables server-side, IntegrasiController::riwayatSyncData)
     dengan filter periode. $nip: pegawai yang sedang dibuka --}}
<div class="flex flex-wrap items-end gap-3 text-sm" id="filter-riwayat-sync">
    <label class="flex flex-col gap-1 text-xs font-semibold text-slate-600">
        Periode
        <select data-periode class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-normal">
            <option value="semua">Semua</option>
            <option value="hari_ini">Hari ini</option>
            <option value="7_hari">7 hari terakhir</option>
            <option value="30_hari">30 hari terakhir</option>
            <option value="bulan_ini">Bulan ini</option>
            <option value="bulan_lalu">Bulan lalu</option>
            <option value="tahun_ini">Tahun ini</option>
            <option value="rentang">Pilih rentang tanggal</option>
        </select>
    </label>
    <label class="hidden flex-col gap-1 text-xs font-semibold text-slate-600" data-rentang>
        Dari tanggal
        <input type="date" data-dari class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-normal">
    </label>
    <label class="hidden flex-col gap-1 text-xs font-semibold text-slate-600" data-rentang>
        Sampai tanggal
        <input type="date" data-sampai class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-normal">
    </label>
    <span class="pb-2 text-xs text-slate-500" data-keterangan-periode></span>
</div>

<div class="mt-4 overflow-x-auto">
    <table id="tabel-riwayat-sync" class="display w-full text-sm" data-nip="{{ $nip }}">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Data</th>
                <th>Nilai Lama</th>
                <th>Nilai Baru</th>
                <th>Oleh</th>
            </tr>
        </thead>
    </table>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('tabel-riwayat-sync');
        const filter = document.getElementById('filter-riwayat-sync');
        const pilihPeriode = filter.querySelector('[data-periode]');
        const inputDari = filter.querySelector('[data-dari]');
        const inputSampai = filter.querySelector('[data-sampai]');
        const keterangan = filter.querySelector('[data-keterangan-periode]');

        const teks = (nilai) => String(nilai ?? '-').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        // tanggal lokal Y-m-d (toISOString memakai UTC dan bisa mundur sehari)
        const ymd = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
        const tampilTanggal = (s) => s ? s.split('-').reverse().join('-') : '';

        const rentangPeriode = (periode) => {
            const hari = new Date();
            const geser = (n) => { const d = new Date(hari); d.setDate(d.getDate() - n); return d; };
            switch (periode) {
                case 'hari_ini': return [ymd(hari), ymd(hari)];
                case '7_hari': return [ymd(geser(6)), ymd(hari)];
                case '30_hari': return [ymd(geser(29)), ymd(hari)];
                case 'bulan_ini': return [ymd(new Date(hari.getFullYear(), hari.getMonth(), 1)), ymd(hari)];
                case 'bulan_lalu': return [ymd(new Date(hari.getFullYear(), hari.getMonth() - 1, 1)), ymd(new Date(hari.getFullYear(), hari.getMonth(), 0))];
                case 'tahun_ini': return [ymd(new Date(hari.getFullYear(), 0, 1)), ymd(hari)];
                default: return ['', ''];
            }
        };
        const periodeAktif = () => pilihPeriode.value === 'rentang'
            ? [inputDari.value, inputSampai.value]
            : rentangPeriode(pilihPeriode.value);

        const tabel = new DataTable(el, {
            serverSide: true,
            processing: true,
            searchDelay: 400,
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                search: 'Cari:',
                lengthMenu: '_MENU_ data per halaman',
                zeroRecords: 'Riwayat tidak ditemukan pada periode ini',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ riwayat',
                infoEmpty: 'Tidak ada riwayat',
                infoFiltered: '(difilter dari _MAX_ total riwayat)',
                loadingRecords: 'Memuat...',
                processing: 'Memproses...',
                emptyTable: 'Belum ada riwayat sync untuk pegawai ini',
            },
            ajax: {
                url: @js(route('integrasi.siasn.riwayat.data')),
                data: (d) => {
                    const [dari, sampai] = periodeAktif();
                    d.nip = el.dataset.nip;
                    d.dari = dari;
                    d.sampai = sampai;
                },
            },
            columns: [
                { data: 'waktu', render: (d) => `<span class="whitespace-nowrap">${teks(d)}</span>` },
                { data: 'data', orderable: false, render: (d, type, row) => `<span class="font-medium">${teks(d)}</span><br><span class="font-mono text-xs text-slate-400">${teks(row.kolom)}</span>` },
                { data: 'nilai_lama', orderable: false, render: (d) => `<span class="text-slate-500 line-through">${teks(d)}</span>` },
                { data: 'nilai_baru', orderable: false, render: (d) => `<span class="font-semibold text-emerald-700">${teks(d)}</span>` },
                { data: 'pengguna', orderable: false, render: (d) => teks(d) },
            ],
        });

        const terapkan = () => {
            const rentang = pilihPeriode.value === 'rentang';
            filter.querySelectorAll('[data-rentang]').forEach((label) => {
                label.classList.toggle('hidden', !rentang);
                label.classList.toggle('flex', rentang);
            });
            const [dari, sampai] = periodeAktif();
            keterangan.textContent = dari || sampai
                ? `${tampilTanggal(dari) || '…'} s.d. ${tampilTanggal(sampai) || '…'}`
                : '';
            tabel.draw();
        };

        pilihPeriode.addEventListener('change', terapkan);
        [inputDari, inputSampai].forEach((input) => input.addEventListener('change', terapkan));
    });
</script>
@endpush
