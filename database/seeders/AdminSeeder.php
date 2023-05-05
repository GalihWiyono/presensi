<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'nip' => "30001",
            'nama_admin' => "Nama Admin",
            'tanggal_lahir' => "22 Jan 2000",
            'user_id' => 1,
            'gender' => "L",
        ]);

        DB::table('admins')->insert([
            'nip' => "30002",
            'nama_admin' => "Nama Admin 2",
            'tanggal_lahir' => "22 Jan 2000",
            'user_id' => 2,
            'gender' => "P",
        ]);
    }
}
