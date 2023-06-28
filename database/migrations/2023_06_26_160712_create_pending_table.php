<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('sesi_id');
            $table->date('tanggal');
            $table->date('tanggal_baru')->nullable();
            $table->time('mulai_absen_baru')->nullable();
            $table->time('akhir_absen_baru')->nullable();
            $table->enum('status', ['Belum', 'Selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending');
    }
};
