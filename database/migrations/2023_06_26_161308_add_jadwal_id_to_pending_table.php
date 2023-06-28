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
        Schema::table('pending', function (Blueprint $table) {
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('cascade');
            $table->foreign('jadwal_id')->references('id')->on('jadwal')->onDelete('cascade');
            $table->foreign('sesi_id')->references('id')->on('sesi')->onDelete('cascade');
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
            $table->dropForeign(['nip']);
            $table->dropForeign(['jadwal_id']);
            $table->dropForeign(['sesi_id']);
        });
    }
};
