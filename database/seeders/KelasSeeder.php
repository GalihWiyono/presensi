<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kelas')->insert([
            'nama_kelas' => "CCIT-8",
        ]);

        DB::table('kelas')->insert([
            'nama_kelas' => "TI-8",
        ]);

        DB::table('kelas')->insert([
            'nama_kelas' => "TMJ-8",
        ]);

        DB::table('kelas')->insert([
            'nama_kelas' => "TMD-8",
        ]);
    }
}
