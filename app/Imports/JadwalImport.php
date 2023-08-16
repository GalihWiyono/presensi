<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Sesi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class JadwalImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

        $dataJadwal = Jadwal::with('kelas', 'matkul', 'dosen')->get();
        $dataMatkul = MataKuliah::select('id', 'nama_matkul')->get();
        $dataKelas = Kelas::select('id', 'nama_kelas')->get();
        $dataDosen = Dosen::select('nip', 'nama_dosen')->get();
        foreach ($rows as $row) {
            // dd();
            $checkMatkul = $dataMatkul->where('nama_matkul', $row['mata_kuliah'])->first();
            $checkDosen = $dataDosen->where('nama_dosen', $row['dosen'])->first();
            $checkKelas = $dataKelas->where('nama_kelas',  $row['kelas'])->first();

            // $arrayCheck = ['matkul_id' => $checkMatkul->id, 'nip' => $checkDosen->nip, 'kelas_id' => $checkKelas->id];
            // $checkJadwal = $dataJadwal->where('kelas_id', $checkKelas->id)
            //     ->where('matkul_id', $checkMatkul->id)
            //     ->where('nip', $checkDosen->nip)
            //     ->first();

            if ($checkDosen && $checkKelas && $checkMatkul) {
                $jadwal = new Jadwal([
                    "matkul_id" => $checkMatkul->id,
                    'kelas_id' => $checkKelas->id,
                    'nip' => $checkDosen->nip,
                    'tanggal_mulai' => Carbon::instance(Date::excelToDateTimeObject($row['tanggal_mulai_jadwal']))->toDateString(),
                    'jam_mulai' => Carbon::instance(Date::excelToDateTimeObject($row['waktu_mulai_kelas']))->toTimeString('minute'),
                    'jam_berakhir' => Carbon::instance(Date::excelToDateTimeObject($row['waktu_akhir_kelas']))->toTimeString('minute')
                ]);

                if ($jadwal->save()) {
                    $jadwal = $jadwal->fresh();

                    for ($i = 1; $i < 19; $i++) {
                        $this->generateSesi($jadwal, $i);
                    }
                }
            }
        }
    }

    public function generateSesi($jadwal, $sesi)
    {
        $sesi = Sesi::create([
            'jadwal_id' => $jadwal->id,
            'sesi' => $sesi,
            'tanggal' => $jadwal->tanggal_mulai->addDays(($sesi - 1) * 7),
            'status' => 'Belum'
        ]);
    }
}
