<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatkulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('matakuliah')->insert([
            'nama_matkul' => "Programming Mobile",
        ]);

        DB::table('matakuliah')->insert([
            'nama_matkul' => "Programming Website",
        ]);

        DB::table('matakuliah')->insert([
            'nama_matkul' => "Aljabar Linear",
        ]);

        DB::table('matakuliah')->insert([
            'nama_matkul' => "Desain Grafis",
        ]);

        DB::table('matakuliah')->insert([
            'nama_matkul' => "Jaringan",
        ]);
    }
}
