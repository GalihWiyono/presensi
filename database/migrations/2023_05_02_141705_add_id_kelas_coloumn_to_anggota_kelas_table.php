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
        Schema::table('anggota_kelas', function (Blueprint $table) {
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
        Schema::table('anggota_kelas', function (Blueprint $table) {
            $table->dropForeign(['id_kelas']);
        });
    }
};
