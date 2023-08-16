<?php

namespace App\Exports;

use App\Models\Jadwal;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalExport implements ShouldAutoSize, FromQuery, WithMapping, WithHeadings, WithStyles
{

    public function query()
    {
        return Jadwal::query();
    }

    public function map($jadwal): array
    {
        return [
            $jadwal->matkul->nama_matkul,
            $jadwal->kelas->nama_kelas,
            $jadwal->dosen->nama_dosen,
            Carbon::parse( $jadwal->tanggal_mulai)->toDateString(),
            $jadwal->jam_mulai,
            $jadwal->jam_berakhir,            
            $jadwal->mulai_absen,
            $jadwal->akhir_absen,
        ];
    }

    public function headings(): array
    {
        return [
            'Mata Kuliah',
            'Kelas',
            'Dosen',
            'Tanggal Mulai Jadwal',
            'Waktu Mulai Kelas',
            'Waktu Akhir Kelas',
            'Waktu Mulai Absen',
            'Waktu Akhir Absen'
        ];
    }

    public function styles(Worksheet $sheet)
    {

        return [
            // Styling an entire column.
            'A'  => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'font' => ['size' => 12]
            ],
            'B'  => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'font' => ['size' => 12]
            ],
            'C'  => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'font' => ['size' => 12]
            ],
            'D'  => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'font' => ['size' => 12]
            ],
            'E'  => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'font' => ['size' => 12]
            ],
            'F'  => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'font' => ['size' => 12]
            ],
            'G'  => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'font' => ['size' => 12]
            ],
            'H'  => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'font' => ['size' => 12]
            ],

            1    => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }
}
