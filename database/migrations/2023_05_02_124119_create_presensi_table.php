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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('sesi_id')->require();
            $table->unsignedBigInteger('jadwal_id')->require();
            $table->string('nim')->require();
            $table->time('waktu_presensi')->nullable();
            $table->enum('status', ['Hadir','Terlambat',"Izin","Tidak Hadir"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presensi');
    }
};
