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
        Schema::create('log_dosen', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->string('nim')->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->enum('affected', ['Mahasiswa','Kelas']);
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
        Schema::dropIfExists('log_dosen');
    }
};
