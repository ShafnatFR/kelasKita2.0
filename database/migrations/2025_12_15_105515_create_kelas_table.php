<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->foreignId('id_mentor')->constrained('mentors', 'id_mentor')->onDelete('cascade');
            $table->string('nama_kelas');
            $table->string('slug')->unique();
            $table->string('kategori');
            $table->decimal('harga', 12, 2)->default(0);
            $table->text('catatan_admin')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('description');
            $table->enum('status_publikasi', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
};
