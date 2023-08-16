<?php

namespace App\Exports;

use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KelasExport implements ShouldAutoSize, FromQuery, WithMapping, WithHeadings, WithStyles

{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Kelas::query();
    }

    public function map($kelas): array
    {
        return [
            $kelas->nama_kelas,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Kelas',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],

            // Styling an entire column.
            'A'  => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'font' => ['size' => 12]
            ]
        ];
    }
}