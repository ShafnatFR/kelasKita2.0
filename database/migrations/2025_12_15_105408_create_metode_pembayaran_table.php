<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id('id_mp');
            $table->string('metode');
            $table->string('no_rek')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('metode_pembayaran');
    }
};
