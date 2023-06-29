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
        Schema::create('tb_riwayat_anggaran', function (Blueprint $table) {
            $table->id();
            $table->integer('id_anggaran')->default(0);
            $table->integer('id_realisasi')->default(0);
            $table->dateTime('tgl_transaksi')->nullable();
            $table->integer('awal')->default(0);
            $table->integer('keluar')->default(0);
            $table->integer('masuk')->default(0);
            $table->integer('sisa')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat_anggaran');
    }
};
