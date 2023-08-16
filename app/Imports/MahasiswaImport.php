<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

        $dataMahasiswa = Mahasiswa::select('nim')->get();
        $dataKelas = Kelas::all();

        foreach ($rows as $row) {
            $checkNim = $dataMahasiswa->where('nim', $row['nim'])->first();
            $checkKelas = $dataKelas->where('nama_kelas', $row['kelas'])->first();

            if (!$checkNim && $checkKelas) {
                $mahasiswa = new Mahasiswa([
                    'nim' => $row['nim'],
                    'nama_mahasiswa' => $row['nama_mahasiswa'],
                    'tanggal_lahir' => $row['tanggal_lahir'],
                    'gender' => $row['jenis_kelamin'],
                    'kelas_id' => $checkKelas->id,
                ]);

                User::create([
                    'username' => $row['nim'],
                    'password' => bcrypt("mahasiswa"),
                    'role' => "Mahasiswa",
                ]);

                $mahasiswa->user_id = User::where('username', $row['nim'])->first()->id;
                $mahasiswa->save();
            }
        }
    }
}
