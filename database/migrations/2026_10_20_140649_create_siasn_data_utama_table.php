<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database SIASN (koneksi yang sama dengan tabel web_params)
    protected $connection = 'mysql_third';

    /**
     * Run the migrations.
     *
     * Data utama ASN hasil penarikan API SIASN BKN, semua kolom nullable karena diisi dari API.
     * Panjang string dibatasi per jenis isian (ID, kode, nama) supaya total ukuran baris
     * tidak melewati batas MySQL 65.535 byte bila semuanya varchar(255).
     * Nama unit organisasi, instansi, jabatan dan pendidikan bertipe text karena di SIASN bisa lebih dari 255 karakter.
     * Kolom tanggal bertipe date: nilai API berformat dd-mm-yyyy harus dikonversi ke Y-m-d saat disimpan.
     */
    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('siasn_data_utama')) {
            return;
        }

        Schema::connection($this->connection)->create('siasn_data_utama', function (Blueprint $table) {
            // ID ASN dari SIASN (field "id" di respons API / kolom ID di file export), dipakai untuk memanggil endpoint SIASN lain
            $table->string('id', 64)->primary();

            // identitas
            // bukan unique: patokan data adalah id, NIP hanya untuk pencarian/pencocokan dengan SIMPEG
            $table->string('nipBaru', 30)->nullable()->index();
            $table->string('nipLama', 30)->nullable();
            $table->string('nama', 255)->nullable();
            $table->string('gelarDepan', 100)->nullable();
            $table->string('gelarBelakang', 100)->nullable();
            $table->string('tempatLahir', 100)->nullable();
            $table->string('tempatLahirId', 64)->nullable();
            $table->date('tglLahir')->nullable();
            $table->string('agama', 50)->nullable();
            $table->string('agamaId', 64)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('emailGov', 150)->nullable();
            $table->string('nik', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->string('noHp', 30)->nullable();
            $table->string('noTelp', 30)->nullable();

            // kepegawaian
            $table->string('jenisPegawaiId', 64)->nullable();
            $table->integer('mkTahun')->nullable();
            $table->integer('mkBulan')->nullable();
            $table->string('jenisPegawaiNama', 100)->nullable();
            $table->string('kedudukanPnsId', 64)->nullable();
            $table->string('kedudukanPnsNama', 100)->nullable();
            $table->string('statusPegawai', 50)->nullable();
            $table->string('jenisKelamin', 20)->nullable();
            $table->string('jenisIdDokumenId', 64)->nullable();
            $table->string('jenisIdDokumenNama', 100)->nullable();
            $table->string('nomorIdDocument', 50)->nullable();
            $table->string('noSeriKarpeg', 50)->nullable();

            // pendidikan
            $table->string('tkPendidikanTerakhirId', 64)->nullable();
            $table->string('tkPendidikanTerakhir', 100)->nullable();
            $table->string('pendidikanTerakhirId', 64)->nullable();
            $table->text('pendidikanTerakhirNama')->nullable();
            $table->string('tahunLulus', 10)->nullable();

            // CPNS, PNS dan pensiun
            $table->string('tmtPns', 20)->nullable();
            $table->string('tmtPensiun', 20)->nullable();
            $table->integer('bupPensiun')->nullable();
            $table->date('tglSkPns')->nullable();
            $table->date('tmtCpns')->nullable();
            $table->date('tglSkCpns')->nullable();

            // instansi dan unit organisasi
            $table->string('instansiIndukId', 64)->nullable();
            $table->text('instansiIndukNama')->nullable();
            $table->string('satuanKerjaIndukId', 64)->nullable();
            $table->text('satuanKerjaIndukNama')->nullable();
            $table->string('kanregId', 64)->nullable();
            $table->text('kanregNama')->nullable();
            $table->string('instansiKerjaId', 64)->nullable();
            $table->text('instansiKerjaNama')->nullable();
            $table->string('instansiKerjaKodeCepat', 20)->nullable();
            $table->string('satuanKerjaKerjaId', 64)->nullable();
            $table->text('satuanKerjaKerjaNama')->nullable();
            $table->string('unorId', 64)->nullable();
            $table->text('unorNama')->nullable();
            $table->string('unorIndukId', 64)->nullable();
            $table->text('unorIndukNama')->nullable();

            // jabatan
            $table->string('jenisJabatanId', 64)->nullable();
            $table->string('jenisJabatan', 100)->nullable();
            $table->text('jabatanNama')->nullable();
            $table->string('jabatanStrukturalId', 64)->nullable();
            $table->text('jabatanStrukturalNama')->nullable();
            $table->string('jabatanFungsionalId', 64)->nullable();
            $table->text('jabatanFungsionalNama')->nullable();
            $table->string('jabatanFungsionalUmumId', 64)->nullable();
            $table->text('jabatanFungsionalUmumNama')->nullable();
            $table->date('tmtJabatan')->nullable();
            $table->string('lokasiKerjaId', 64)->nullable();
            $table->text('lokasiKerja')->nullable();

            // golongan, eselon dan gaji
            $table->string('golRuangAwalId', 64)->nullable();
            $table->string('golRuangAwal', 20)->nullable();
            $table->string('golRuangAkhirId', 64)->nullable();
            $table->string('golRuangAkhir', 20)->nullable();
            $table->date('tmtGolAkhir')->nullable();
            $table->string('masaKerja', 50)->nullable();
            $table->string('eselon', 50)->nullable();
            $table->string('eselonId', 64)->nullable();
            $table->string('eselonLevel', 50)->nullable();
            $table->date('tmtEselon')->nullable();
            $table->string('gajiPokok', 50)->nullable();
            $table->string('kpknId', 64)->nullable();
            $table->text('kpknNama')->nullable();
            $table->string('ktuaId', 64)->nullable();
            $table->text('ktuaNama')->nullable();
            $table->string('taspenId', 64)->nullable();
            $table->text('taspenNama')->nullable();

            // keluarga dan dokumen
            $table->string('jenisKawinId', 64)->nullable();
            $table->string('statusPerkawinan', 50)->nullable();
            $table->string('statusHidup', 50)->nullable();
            $table->date('tglSuratKeteranganDokter')->nullable();
            $table->string('noSuratKeteranganDokter', 100)->nullable();
            $table->string('jumlahIstriSuami', 10)->nullable();
            $table->string('jumlahAnak', 10)->nullable();
            $table->string('noSuratKeteranganBebasNarkoba', 100)->nullable();
            $table->string('tglSuratKeteranganBebasNarkoba', 20)->nullable();
            $table->string('skck', 100)->nullable();
            $table->string('tglSkck', 20)->nullable();
            $table->string('akteKelahiran', 100)->nullable();
            $table->string('akteMeninggal', 100)->nullable();
            $table->string('tglMeninggal', 20)->nullable();
            $table->string('noNpwp', 50)->nullable();
            $table->string('tglNpwp', 20)->nullable();
            $table->string('noAskes', 50)->nullable();
            $table->string('bpjs', 50)->nullable();
            $table->string('kodePos', 10)->nullable();
            $table->string('noSpmt', 100)->nullable();
            $table->string('tglSpmt', 20)->nullable();
            $table->string('noTaspen', 50)->nullable();
            $table->string('bahasa', 100)->nullable();
            $table->string('kppnId', 64)->nullable();
            $table->text('kppnNama')->nullable();
            $table->string('pangkatAkhir', 100)->nullable();
            $table->date('tglSttpl')->nullable();
            $table->string('nomorSttpl', 100)->nullable();
            $table->string('nomorSkCpns', 100)->nullable();
            $table->string('nomorSkPns', 100)->nullable();

            // jenjang dan atribut ASN lainnya
            $table->string('jenjang', 100)->nullable();
            $table->text('jabatanAsn')->nullable();
            $table->string('kartuAsn', 100)->nullable();
            $table->boolean('validNik')->nullable();
            $table->boolean('validKk')->nullable();
            $table->string('pangkatAwal', 100)->nullable();
            $table->string('asnJenjangJabatan', 100)->nullable();
            $table->string('kode_jenjang_jabatan', 50)->nullable();
            $table->string('levelJabatan', 50)->nullable();
            $table->string('tanggal_taspen', 20)->nullable();
            $table->string('tabrum2', 100)->nullable();
            $table->string('kelas_jabatan', 20)->nullable();
            $table->string('karis_karsu', 100)->nullable();
            $table->text('path')->nullable();
            $table->string('genEmailAsn', 150)->nullable();
            $table->string('pertekCpnsPnsl2thNomor', 100)->nullable();
            $table->string('pertekCpnsPnsl2thTanggal', 20)->nullable();
            $table->string('nomorSuratKeteranganDokter', 100)->nullable();
            $table->string('subJftId', 64)->nullable();
            $table->text('subJftNama')->nullable();
            $table->string('gelarDepanProfesi', 100)->nullable();
            $table->string('gelarBelakangProfesi', 100)->nullable();
            $table->string('gelarDepanSertifikasi', 100)->nullable();
            $table->string('gelarBelakangSertifikasi', 100)->nullable();
            $table->string('tglAwalKontrak', 20)->nullable();
            $table->string('tglAkhirKontrak', 20)->nullable();

            // waktu data ditarik/diperbarui dari SIASN
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('siasn_data_utama');
    }
};
