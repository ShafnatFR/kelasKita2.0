<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            // Gunakan NO ACTION atau restrict, jangan cascade delete untuk history keuangan
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->foreignId('id_mp')->nullable()->constrained('metode_pembayaran', 'id_mp')->onDelete('set null');

            $table->string('kode_invoice')->unique();
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->string('bukti_bayar')->nullable();
            $table->dateTime('tgl_transaksi')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
};
