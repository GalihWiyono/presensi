<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mahasiswa')->insert([
            'nim' => "10001",
            'nama_mahasiswa' => "Nama Mahasiswa",
            'tanggal_lahir' => "22 Jan 2000",
            'user_id' => 3,
            'gender' => "L",
        ]);

        DB::table('mahasiswa')->insert([
            'nim' => "10002",
            'nama_mahasiswa' => "Nama Mahasiswa 2",
            'tanggal_lahir' => "22 Jan 2000",
            'user_id' => 4,
            'gender' => "P",
        ]);
    }
}
