<?php

namespace App\Exports;

use App\Models\Fournisseur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FournisseursExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * Export all fournisseurs data
     */
    public function collection()
    {
        return Fournisseur::all([
            'id',
            'nom',
            'contact',
            'telephone',
            'email',
            'adresse',
            'ville',
            'ice',
            'notes',
            'created_at',
        ]);
    }

    /**
     * Define the header row
     */
    public function headings(): array
    {
        return [
            'ID',
            'NOM',
            'CONTACT',
            'TÉLÉPHONE',
            'E-MAIL',
            'ADRESSE',
            'VILLE',
            'ICE',
            'NOTES',
            'AJOUTÉ À',
        ];
    }

    /**
     * Apply styles to the spreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style for the header row
        $sheet->getStyle('A1:J1')->applyFromArray([
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
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:J{$lastRow}")
              ->getAlignment()
              ->setHorizontal('left');

        return [];
    }


    /**
     * Format each row before exporting
     */
    public function map($fournisseur): array
    {
        return [
            $fournisseur->id,
            $fournisseur->nom,
            $fournisseur->contact,
            $fournisseur->telephone,
            $fournisseur->email,
            $fournisseur->adresse,
            $fournisseur->ville,
            $fournisseur->ice,
            $fournisseur->notes,
            optional($fournisseur->created_at)->format('d/m/Y H:i'), // <-- formatted date
        ];
    }
}
