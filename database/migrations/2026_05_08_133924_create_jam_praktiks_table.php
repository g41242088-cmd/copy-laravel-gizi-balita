<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jam_praktiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ahli_id')->constrained('users')->onDelete('cascade');
            $table->string('hari'); // Senin, Selasa, dst.
            $table->boolean('is_aktif')->default(true);
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jam_praktiks');
    }
};