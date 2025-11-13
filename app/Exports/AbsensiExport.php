<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Absensi::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Jabatan',
            'Unit Kerja',
            'Provinsi',
            'Tanggal Absensi'
        ];
    }

    public function map($absensi): array
    {
        return [
            $absensi->nip ?? 'N/A',
            $absensi->nama ?? 'N/A',
            $absensi->jabatan ?? 'N/A',
            $absensi->unit_kerja ?? 'N/A',
            $absensi->provinsi ?? 'N/A',
            $absensi->created_at ? $absensi->created_at->format('d/m/Y H:i') : 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0']
                ]
            ],
        ];
    }
}