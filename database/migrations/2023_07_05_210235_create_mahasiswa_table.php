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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim')->primary();
            $table->string('nama_mahasiswa')->require();
            $table->date('tanggal_lahir')->require();
            $table->unsignedBigInteger('user_id')->require();
            $table->enum('gender', ['L','P']);
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pending', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['kelas_id']);
        });
        Schema::dropIfExists('mahasiswa');
    }
};
