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
            // PERBAIKAN: Gunakan whereBetween dengan format yang benar
            $query->whereBetween('waktu_masuk', [$this->startDate, $this->endDate]);
        }

        return $query->orderBy('waktu_masuk', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Jabatan',
            'Unit Kerja',
            'Provinsi',
            'Waktu Masuk',
            'Waktu Pulang',
            'Status',
            'Durasi Kerja'
        ];
    }

    public function map($absensi): array
    {
        $waktuMasuk = $absensi->waktu_masuk ? $absensi->waktu_masuk->format('d/m/Y H:i') : '-';
        $waktuPulang = $absensi->waktu_pulang ? $absensi->waktu_pulang->format('d/m/Y H:i') : '-';

        // Hitung durasi kerja jika sudah pulang
        $durasi = '-';
        if ($absensi->waktu_masuk && $absensi->waktu_pulang) {
            $diff = $absensi->waktu_masuk->diff($absensi->waktu_pulang);
            $durasi = $diff->format('%h jam %i menit');
        }

        $status = $absensi->waktu_pulang ? 'Selesai' : 'Masuk';

        return [
            $absensi->nip ?? 'N/A',
            $absensi->nama ?? 'N/A',
            $absensi->jabatan ?? 'N/A',
            $absensi->unit_kerja ?? 'N/A',
            $absensi->provinsi ?? 'N/A',
            $waktuMasuk,
            $waktuPulang,
            $status,
            $durasi
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
            // Auto size columns
            'A' => ['width' => 15],
            'B' => ['width' => 25],
            'C' => ['width' => 20],
            'D' => ['width' => 20],
            'E' => ['width' => 15],
            'F' => ['width' => 18],
            'G' => ['width' => 18],
            'H' => ['width' => 12],
            'I' => ['width' => 15],
        ];
    }
}
