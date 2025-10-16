<?php

namespace App\Exports;

use App\Http\Resources\ExportEntreeStockRecource;
use App\Models\EntreeStock;
use App\Models\MouvementStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;

class EntreeExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }


    /**
     * Define the header row
     */
    public function headings(): array
    {
        return [
            "Date d'entrée",
            "Code d'article",
            "Désignation d'article",
            "Stock initial",
            "Quantité entrée",
            "Référence du bon de réception",
            "Stock actuel",
            "Unité",
            "Prix unitaire (Dhs)",
            "TVA appliqué",
            "Montant total"
        ];
    }

    /**
     * Apply styles to the spreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style for the header row
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => '4F81BD'], // Blue background
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Auto-size all columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:K{$lastRow}")
              ->getAlignment()
              ->setHorizontal('left');

        return [];
    }

}
