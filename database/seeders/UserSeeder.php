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
        DB::table('users')->insert([
            'id' => 1,
            "username" => "admin1",
            "password" => bcrypt('admin'),
            "role_id" => 1
        ]);

        DB::table('users')->insert([
            'id' => 2,
            "username" => "admin2",
            "password" => bcrypt('admin'),
            "role_id" => 1
        ]);

        DB::table('users')->insert([
            'id' => 3,
            "username" => "mahasiswa1",
            "password" => bcrypt('mahasiswa'),
            "role_id" => 2
        ]);


        DB::table('users')->insert([
            'id' => 4,
            "username" => "mahasiswa2",
            "password" => bcrypt('mahasiswa'),
            "role_id" => 2
        ]);

        DB::table('users')->insert([
            'id' => 5,
            "username" => "dosen1",
            "password" => bcrypt('dosen'),
            "role_id" => 3
        ]);

        DB::table('users')->insert([
            'id' => 6,
            "username" => "dosen2",
            "password" => bcrypt('dosen'),
            "role_id" => 3
        ]);
    }
}
