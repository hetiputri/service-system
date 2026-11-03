{{-- Daftar pegawai bisa ribuan baris: DataTables server-side (IntegrasiController::daftarPegawai), diinisialisasi di dashboard.
     $jenis: hanyaDiSiasn|hanyaDiSimpeg|atributBerbeda, $denganAtribut: tampilkan kolom "Data yang Berbeda" --}}
@php $denganAtribut = $denganAtribut ?? false; @endphp
<table class="tabel-pegawai-siasn display w-full text-sm" data-jenis="{{ $jenis }}" data-atribut="{{ $denganAtribut ? '1' : '0' }}">
    <thead>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama</th>
            @if ($denganAtribut)
            <th>Data yang Berbeda</th>
            @endif
            <th>Aksi</th>
        </tr>
    </thead>
</table>
