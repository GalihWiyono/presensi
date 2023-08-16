<?php

namespace App\Imports;

use App\Models\MataKuliah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MataKuliahImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $dataMatkul = MataKuliah::all();

        foreach ($rows as $row) {
            $checkMatkul = $dataMatkul->where('nama_matkul', $row['mata_kuliah'])->first();
            if (!$checkMatkul) {
                $matkul = new MataKuliah([
                    'nama_matkul' => $row['mata_kuliah'],
                ]);

                $matkul->save();
            }
        }
    }
}
