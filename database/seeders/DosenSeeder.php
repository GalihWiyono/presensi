<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dosen')->insert([
            'nip' => "20001",
            'nama_dosen' => "Nama Dosen",
            'tanggal_lahir' => "22 Jan 2000",
            'user_id' => 5,
            'gender' => "L",
        ]);

        DB::table('dosen')->insert([
            'nip' => "20002",
            'nama_dosen' => "Nama Dosen 2",
            'tanggal_lahir' => "22 Jan 2000",
            'user_id' => 6,
            'gender' => "P",
        ]);
    }
}
