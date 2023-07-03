<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_inventaris', function (Blueprint $table) {
            $table->id();
            $table->integer('id_perencanaan')->default(0)->nullable();
            $table->integer('id_pengajuan')->default(0)->nullable();
            $table->integer('id_realisasi')->default(0)->nullable();
            $table->integer('id_penerima')->default(0)->nullable();
            $table->integer('id_kategori')->default(0)->nullable();
            $table->integer('id_user_create')->default(0)->nullable();
            $table->integer('id_unit_kerja')->default(0)->nullable();
            $table->integer('id_user_approve')->default(0)->nullable();
            $table->integer('id_user_batal')->default(0)->nullable();
            $table->integer('id_anggaran')->default(0)->nullable();
            $table->string('nama_item', 250)->nullable();
            $table->integer('anggaran')->default(0)->nullable();
            $table->enum('jenis', ['bahan', 'aset', 'jasa'])->nullable();
            $table->string('nama_kategori', 250)->nullable();
            $table->integer('harga_perolehan')->default(0)->nullable();
            $table->integer('harga_saat_ini')->default(0)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('nama_user_create', 250)->nullable();
            $table->dateTime('tgl_beli')->nullable();
            $table->integer('umur_efektif')->default(0)->nullable();
            $table->integer('stok')->default(0)->nullable();
            $table->integer('penyusutan_bulanan')->default(0)->nullable();
            $table->enum('status_inventaris', ['aktif', 'rusak','hilang'])->default('aktif');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_inventaris');
    }
};
