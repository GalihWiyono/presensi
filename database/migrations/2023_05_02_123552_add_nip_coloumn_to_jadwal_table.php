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
        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreign('id_matkul')->references('id_matkul')->on('matakuliah')->onDelete('restrict');
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('restrict');
            $table->unsignedBigInteger('id_kelas')->after('id_matkul')->require();
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['id_matkul','nip','id_kelas']);
        });
    }
};
