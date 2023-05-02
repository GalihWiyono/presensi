<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            "username" => "admin1",
            "password" => bcrypt('admin'),
            "role" => "admin"
        ]);

        DB::table('accounts')->insert([
            "username" => "admin2",
            "password" => bcrypt('admin'),
            "role" => "admin"
        ]);

        DB::table('accounts')->insert([
            "username" => "mahasiswa1",
            "password" => bcrypt('mahasiswa'),
            "role" => "mahasiswa"
        ]);


        DB::table('accounts')->insert([
            "username" => "mahasiswa2",
            "password" => bcrypt('mahasiswa'),
            "role" => "mahasiswa"
        ]);

        DB::table('accounts')->insert([
            "username" => "dosen1",
            "password" => bcrypt('dosen'),
            "role" => "dosen"
        ]);

        DB::table('accounts')->insert([
            "username" => "dosen2",
            "password" => bcrypt('dosen'),
            "role" => "dosen"
        ]);
    }
}
