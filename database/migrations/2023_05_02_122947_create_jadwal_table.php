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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matkul_id')->require();
            $table->unsignedBigInteger('kelas_id')->require();
            $table->string('nip')->require();
            $table->enum('hari', ['Senin','Selasa',"Rabu","Kamis","Jumat","Sabtu"]);
            $table->time('jam_mulai');
            $table->time('jam_berakhir');
            $table->time('mulai_absen')->nullable();
            $table->time('akhir_absen')->nullable();
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
        Schema::dropIfExists('jadwal');
    }
};
