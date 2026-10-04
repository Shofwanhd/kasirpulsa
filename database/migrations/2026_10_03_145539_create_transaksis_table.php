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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_transaksi');
            $table->foreignId('kategori_pulsa_id')->nullable()->constrained('kategori_pulsas');
            $table->foreignId('pulsa_id')->nullable()->constrained('pulsas');
            $table->date('tanggal');
            $table->string('keterangan', 255);
            $table->foreignId('akun_asal_id')->nullable()->constrained('akuns');
            $table->foreignId('akun_tujuan_id')->nullable()->constrained('akuns');
            $table->decimal('nominal', 15, 2)->nullable()->default(0);
            $table->decimal('amount', 15, 2)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
