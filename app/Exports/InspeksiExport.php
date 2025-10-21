<?php

namespace App\Exports;

use App\Models\Inspeksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InspeksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $inspeksi;

    public function __construct(Inspeksi $inspeksi)
    {
        $this->inspeksi = $inspeksi;
    }

    public function collection()
    {
        // Mengembalikan collection untuk data utama
        return collect([$this->inspeksi]);
    }

    public function headings(): array
    {
        return [
            'LAPORAN HASIL INSPEKSI',
            '',
            '',
            '',
            '',
            ''
        ];
    }

    public function map($inspeksi): array
    {
        $data = [];

        $createdAt = $inspeksi->created_at->timezone('Asia/Kuala_Lumpur');

        // Header Laporan
        $data[] = ['LAPORAN HASIL INSPEKSI'];
        $data[] = []; // Empty row

        // Informasi Inspeksi
        $data[] = ['Tanggal Inspeksi', $inspeksi->created_at->format('d/m/Y H:i')];
        $data[] = ['Kategori Inspeksi', $inspeksi->kategori->nama ?? 'Semua Kategori'];
        $data[] = ['Lokasi', $inspeksi->lokasi ?? '-'];
        $data[] = ['Keterangan', $inspeksi->keterangan ?? '-'];
        $data[] = ['Pengawas', $inspeksi->pengawas->name ?? '-'];
        $data[] = []; // Empty row

        // Header Tabel Jawaban
        $data[] = ['NO', 'PERTANYAAN', 'JAWABAN'];

        // Data Jawaban
        $no = 1;
        foreach ($inspeksi->jawaban as $jawaban) {
            $data[] = [
                $no++,
                $jawaban->pertanyaan->pertanyaan ?? 'Pertanyaan tidak ditemukan',
                $jawaban->jawaban
            ];
        }

        $data[] = []; // Empty row
        $data[] = ['Total Pertanyaan', $inspeksi->jawaban->count()];
        $data[] = ['Tanggal Export', now()->format('d/m/Y H:i')];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk judul utama
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => 'center']
            ],
            
            // Style untuk header informasi
            3 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]],
            7 => ['font' => ['bold' => true]],
            
            // Style untuk header tabel
            9 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE6E6FA']
                ]
            ],
            
            // Auto size columns
            'A' => ['width' => 10],
            'B' => ['width' => 50],
            'C' => ['width' => 20],
        ];
    }

    public function title(): string
    {
        return 'Laporan Inspeksi';
    }
}