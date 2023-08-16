<?php

namespace App\Imports;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdminImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {

        $dataAdmin = Admin::select('nip')->get();

        foreach ($rows as $row) {
            $checknip = $dataAdmin->where('nip', $row['nip'])->first();

            if (!$checknip) {
                $admin = new Admin([
                    'nip' => $row['nip'],
                    'nama_admin' => $row['nama_admin'],
                    'tanggal_lahir' => $row['tanggal_lahir'],
                    'gender' => $row['jenis_kelamin'],
                ]);

                User::create([
                    'username' => $row['nip'],
                    'password' => bcrypt("admin"),
                    'role' => "Admin",
                ]);

                $admin->user_id = User::where('username', $row['nip'])->first()->id;
                $admin->save();
            }
        }
    }
}
