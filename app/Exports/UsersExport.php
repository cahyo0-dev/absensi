<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        $query = User::query();

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
            'Email',
            'Jabatan',
            'Unit Kerja',
            'Role',
            'Tanggal Bergabung',
            'Status'
        ];
    }

    public function map($user): array
    {
        return [
            $user->nip ?? 'N/A',
            $user->nama ?? $user->name ?? 'N/A',
            $user->email ?? 'N/A',
            $user->jabatan ?? 'N/A',
            $user->unit_kerja ?? 'N/A',
            $user->role ?? 'user',
            $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A',
            'Aktif'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
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