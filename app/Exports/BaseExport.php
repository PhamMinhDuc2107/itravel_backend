<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    use RegistersEventListeners;

    abstract public function collection(): Collection;

    abstract public function headings(): array;

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8E8E8'],
                ],
            ],
        ];
    }

    public static function afterSheet(AfterSheet $event): void
    {
        $event->sheet->freezePane('A2');
    }

    public function getFileName(): string
    {
        return 'export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
    }
}
