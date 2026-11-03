<form method="GET" action="{{ route('integrasi.siasn.pegawai') }}" class="flex gap-2">
    <input type="text" name="nip" value="{{ $nip ?? '' }}" inputmode="numeric" maxlength="18" required
        placeholder="Cek per pegawai, masukkan NIP"
        class="w-64 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
    <button type="submit"
        class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
        <x-icon name="magnifying-glass" />
        Cek
    </button>
</form>
