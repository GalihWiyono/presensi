<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {

        $dataDosen = Dosen::select('nip')->get();

        foreach ($rows as $row) {
            $checknip = $dataDosen->where('nip', $row['nip'])->first();

            if (!$checknip) {
                $dosen = new Dosen([
                    'nip' => $row['nip'],
                    'nama_dosen' => $row['nama_dosen'],
                    'tanggal_lahir' => $row['tanggal_lahir'],
                    'gender' => $row['jenis_kelamin'],
                ]);

                User::create([
                    'username' => $row['nip'],
                    'password' => bcrypt("dosen"),
                    'role' => "Dosen",
                ]);

                $dosen->user_id = User::where('username', $row['nip'])->first()->id;
                $dosen->save();
            }
        }
    }
}
