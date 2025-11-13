<?php

namespace App\Exports;

use App\Models\Inspeksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InspeksiPresetExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $inspeksis;
    protected $presetName;
    protected $startDate;
    protected $endDate;

    public function __construct($inspeksis, $presetName, $startDate, $endDate)
    {
        $this->inspeksis = $inspeksis;
        $this->presetName = $presetName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->inspeksis;
    }

    public function headings(): array
    {
        return [
            'LAPORAN INSPEKSI - ' . strtoupper($this->presetName),
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

        // Header
        $data[] = ['LAPORAN INSPEKSI - ' . strtoupper($this->presetName)];
        $data[] = ['Periode: ' . $this->startDate->format('d/m/Y') . ' - ' . $this->endDate->format('d/m/Y')];
        $data[] = ['Total Data: ' . $this->inspeksis->count()];
        $data[] = ['Tanggal Export: ' . now()->format('d/m/Y H:i')];
        $data[] = [];

        // Header Tabel
        $data[] = ['NO', 'TANGGAL', 'KATEGORI', 'PENGAWAS', 'JUMLAH PERTANYAAN', 'TANDA TANGAN', 'KETERANGAN'];

        // Data
        $no = 1;
        foreach ($this->inspeksis as $inspeksi) {
            $data[] = [
                $no++,
                $inspeksi->tanggal->format('d/m/Y'),
                $inspeksi->kategori->nama ?? '-',
                $inspeksi->pengawas->name,
                $inspeksi->jawaban->count(),
                $inspeksi->tanda_tangan ? 'Ada' : 'Tidak',
                $inspeksi->keterangan ?? '-'
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Judul utama
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => 'center']
            ],

            // Info periode
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],

            // Header tabel
            6 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE6E6FA']
                ]
            ],

            // Auto size columns
            'A' => ['width' => 8],
            'B' => ['width' => 15],
            'C' => ['width' => 20],
            'D' => ['width' => 25],
            'E' => ['width' => 18],
            'F' => ['width' => 15],
            'G' => ['width' => 30],
        ];
    }

    public function title(): string
    {
        return 'Laporan ' . $this->presetName;
    }
}
