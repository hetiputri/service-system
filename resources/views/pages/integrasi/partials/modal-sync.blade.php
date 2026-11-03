{{-- Modal upload file export SIASN, dibuka dari tombol Sync Data SIASN (state modalSync di dashboard) --}}
<div x-show="modalSync" x-transition.opacity style="display: none"
    class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 p-4">
    <form method="POST" action="{{ route('integrasi.siasn.import') }}" enctype="multipart/form-data"
        class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-xl" @click.outside="modalSync = false"
        x-data="{ namaFile: '', kirim: false }" @submit="kirim = true">
        @csrf
        <h3 class="text-lg font-bold text-slate-900">Sync Data SIASN</h3>
        <p class="mt-1 text-sm text-slate-600">Import file hasil <strong>export data ASN dari web SIASN</strong> (CSV atau Excel). File diproses di background, halaman ini bisa ditinggalkan.</p>

        <label class="mt-4 flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center hover:border-blue-400 hover:bg-blue-50">
            <span class="text-slate-400"><x-icon name="download" /></span>
            <span class="mt-2 text-sm font-semibold text-slate-700" x-text="namaFile || 'Pilih file CSV / Excel'"></span>
            <span class="mt-1 text-xs text-slate-500">.csv, .xlsx, .xls &middot; maksimal 50 MB</span>
            <input type="file" name="file" accept=".csv,.txt,.xlsx,.xls" required class="hidden"
                @change="namaFile = $event.target.files[0]?.name ?? ''">
        </label>
        @error('file')
        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
        @enderror

        <div class="mt-4 rounded-xl bg-amber-50 p-3 text-xs text-amber-800">
            <p class="font-semibold">Perhatikan sebelum upload:</p>
            <ul class="mt-1 list-disc space-y-0.5 pl-4">
                <li>Baris pertama file harus berisi judul kolom, dan wajib ada kolom <strong>ID</strong> (PNS ID) dan <strong>NIP</strong> (NIP Baru).</li>
                <li>Data dicocokkan berdasarkan <strong>ID</strong>: ID yang sudah ada diperbarui, ID yang belum ada ditambahkan.</li>
                <li>Jika file Excel, kolom NIP harus berformat <strong>teks</strong>. NIP yang tersimpan sebagai angka kehilangan 3 digit terakhir dan akan ditolak.</li>
                <li>Judul kolom yang tidak dikenali akan dilewati dan ditampilkan setelah import selesai.</li>
            </ul>
        </div>

        <div class="mt-5 flex justify-end gap-2">
            <button type="button" @click="modalSync = false"
                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
            <button type="submit" :disabled="kirim"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60">
                <span x-text="kirim ? 'Mengupload...' : 'Upload & Proses'"></span>
            </button>
        </div>
    </form>
</div>
