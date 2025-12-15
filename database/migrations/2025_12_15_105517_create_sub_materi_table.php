<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sub_materi', function (Blueprint $table) {
            $table->id('id_sub_materi');
            $table->foreignId('id_materi')->constrained('materi', 'id_materi')->onDelete('cascade');

            // Relasi ke Asset (Video/Dokumen) - Nullable
            $table->foreignId('id_video')->nullable()->constrained('videos', 'id_video')->onDelete('set null');
            $table->foreignId('id_dokumen')->nullable()->constrained('dokumens', 'id_dokumen')->onDelete('set null');

            $table->integer('urutan');
            $table->string('judul_sub');
            $table->text('teks_pembelajaran')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_materi');
    }
};
