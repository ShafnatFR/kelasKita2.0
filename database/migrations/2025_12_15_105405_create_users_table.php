<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user'); // Primary Key custom name
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'mentor', 'student'])->default('student');
            $table->text('deskripsi');
            $table->string('foto_profil')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            // perbedaan status:
            // active: user dapat login dan menggunakan fitur
            // inactive: user tidak dapat login dan menggunakan fitur permanen
            // banned: user tidak dapat login dan menggunakan fitur dengan batas waktu tertentu
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
