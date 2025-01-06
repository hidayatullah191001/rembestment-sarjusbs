<?php

namespace App\Exports;

use App\Models\UserEntertain;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserEntertainExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $start_date;
    protected $end_date;    
    protected $counter = 0;

    public function __construct($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        $query = UserEntertain::query();

        // Filter berdasarkan tanggal jika diberikan
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('tanggal', [$this->start_date, $this->end_date]);
        }

        return $query->with('peserta')->get();
    }

    public function map($row): array
    {
        $this->counter++;
        return [
            $this->counter,
            $row->user->name,
            $row->user->province->name,
            $row->nama_account_manager,
            $row->nama_kanwil_manager,
            $row->hari,
            $row->tanggal,
            $row->waktu,
            $row->type_id,
            $row->nilai_entertain,
            $row->revenue,
            $row->pelanggan,
            $row->topik,
            $row->aktivitas,
            $row->target_pelaksanaan,
            // Ambil data peserta terkait
            $row->peserta->pluck('nama_pelanggan')->implode(', '),
            $row->peserta->pluck('internal_icon')->implode(', '),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'User Name',
            'User Province From',
            'Nama Account Manager',
            'Nama Kanwil Manager',
            'Hari',
            'Tanggal',
            'Waktu',
            'Type ID',
            'Nilai Entertain',
            'Revenue',
            'Pelanggan',
            'Topik',
            'Aktivitas',
            'Target Pelaksanaan',
            'Nama Peserta',
            'Internal Icon',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:Q1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4CAF50'], // Warna hijau
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Style seluruh tabel
        $sheet->getStyle('A1:Q' . ($this->collection()->count() + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);

        // Mengatur lebar kolom otomatis
        foreach (range('A', 'Q') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Merapikan isi sel
        $sheet->getStyle('A2:Q' . ($this->collection()->count() + 1))->applyFromArray([
            'alignment' => [
                'horizontal' => 'left',
                'vertical' => 'center',
            ],
        ]);

        return $sheet;
    }
}
