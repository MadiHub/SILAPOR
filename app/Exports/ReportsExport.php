<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected array $filters;
    protected int $no = 1;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Data Laporan';
    }

    public function query()
    {
        $q = Report::with(['user', 'category'])
            ->withCount('votes');

        if (!empty($this->filters['search'])) {
            $q->where(function ($query) {
                $query->where('title', 'like', '%' . $this->filters['search'] . '%')
                      ->orWhere('address', 'like', '%' . $this->filters['search'] . '%');
            });
        }

        if (!empty($this->filters['status'])) {
            $q->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['category_id'])) {
            $q->where('category_id', $this->filters['category_id']);
        }

        if (!empty($this->filters['date_from'])) {
            $q->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        $sort = $this->filters['sort'] ?? 'latest';
        match ($sort) {
            'oldest' => $q->oldest(),
            'votes'  => $q->orderByDesc('votes_count'),
            default  => $q->latest(),
        };

        return $q;
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Laporan',
            'Alamat',
            'Pelapor',
            'Kategori',
            'Status',
            'Jumlah Vote',
            'Tanggal Lapor',
        ];
    }

    public function map($report): array
    {
        $statusLabel = match ($report->status) {
            'active'   => 'Aktif',
            'process'  => 'Diproses',
            'done'     => 'Selesai',
            'rejected' => 'Ditolak',
            default    => $report->status,
        };

        return [
            $this->no++,
            $report->title,
            $report->address,
            $report->user->name ?? '-',
            $report->category->name ?? '-',
            $statusLabel,
            $report->votes_count,
            $report->created_at->format('d/m/Y'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 40,
            'C' => 35,
            'D' => 20,
            'E' => 18,
            'F' => 14,
            'G' => 12,
            'H' => 16,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Header row styling
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 11,
                'name'  => 'Arial',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF2563EB'], // biru primary
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFBFDBFE'],
                ],
            ],
        ]);

        // Data rows
        $sheet->getStyle("A2:H{$lastRow}")->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => 10,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFE2E8F0'],
                ],
            ],
        ]);

        // Zebra striping (baris genap abu-abu muda)
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF8FAFC'],
                    ],
                ]);
            }
        }

        // Center alignment untuk kolom No, Status, Vote, Tanggal
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Row height header
        $sheet->getRowDimension(1)->setRowHeight(22);

        return [];
    }
}