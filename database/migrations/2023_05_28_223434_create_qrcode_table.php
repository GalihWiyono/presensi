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
        Schema::create('qrcode', function (Blueprint $table) {
            $table->id();
            $table->string("unique");
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('sesi_id');
            $table->date('tanggal');
            $table->time("mulai_absen");
            $table->time("akhir_absen");
            $table->enum("status", ['Inactive', 'Active']);
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
        Schema::dropIfExists('qrcode');
    }
};
