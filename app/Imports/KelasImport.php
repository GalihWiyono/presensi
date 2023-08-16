<?php

namespace App\Imports;

use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelasImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $dataKelas = Kelas::all();

        foreach ($rows as $row) {
            $checkKelas = $dataKelas->where('nama_kelas', $row['nama_kelas'])->first();
            if (!$checkKelas) {
                $kelas = new Kelas([
                    'nama_kelas' => $row['nama_kelas'],
                ]);

                $kelas->save();
            }
        }
    }
}
