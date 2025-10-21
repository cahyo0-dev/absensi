<?php

namespace App\Exports;

use App\Models\Inspeksi;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AllInspeksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Inspeksi::with(['kategori', 'jawaban', 'pengawas'])
            ->where('pengawas_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'TANGGAL',
            'KATEGORI',
            'LOKASI',
            'JUMLAH PERTANYAAN',
            'PENGAWAS',
            'KETERANGAN'
        ];
    }

    public function map($inspeksi): array
    {
        return [
            $inspeksi->created_at->format('d/m/Y H:i'),
            $inspeksi->kategori->nama ?? 'Semua Kategori',
            $inspeksi->lokasi ?? '-',
            $inspeksi->jawaban->count(),
            $inspeksi->pengawas->name ?? '-',
            $inspeksi->keterangan ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE6E6FA']
                ]
            ],
        ];
    }
}