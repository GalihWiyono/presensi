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
        Schema::create('log_admin', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->enum('affected', ['Mahasiswa','Dosen','Admin','Kelas','Mata Kuliah', 'Jadwal', 'Presensi']);
            $table->text('activity');
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
        Schema::dropIfExists('log_admin');
    }
};
