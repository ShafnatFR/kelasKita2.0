<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id_report');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            // Nullable karena bisa report bug sistem umum
            $table->foreignId('id_kelas')->nullable()->constrained('kelas', 'id_kelas')->onDelete('set null');

            $table->string('kategori');
            $table->text('keterangan');
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
