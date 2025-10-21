<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Facades\Pdf;

class CardexController extends Controller
{
    public function index(Request $request, Article $article)
    {
        $year = $request->input('year', now()->year);

        $months = [
            1 => 'JANVIER',
            2 => 'FEVRIER',
            3 => 'MARS',
            4 => 'AVRIL',
            5 => 'MAI',
            6 => 'JUIN',
            7 => 'JUILLET',
            8 => 'AOUT',
            9 => 'SEPTEMBRE',
            10 => 'OCTOBRE',
            11 => 'NOVEMBRE',
            12 => 'DECEMBRE'
        ];

        $cardex = [];
        $monthTotals = [];
        $stockPreviousDay = 0;

        // === Build cardex data ===
        for ($month = 1; $month <= 12; $month++) {
            $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

            $totalEntrees = 0;
            $totalSorties = 0;
            $stockFinalMonth = 0;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);

                $entrees = MouvementStock::parArticle($article->id)
                    ->whereDate('date_mouvement', $date)
                    ->where('type', MouvementStock::TYPE_ENTREE)
                    ->sum('quantite_entree');

                $sorties = MouvementStock::parArticle($article->id)
                    ->whereDate('date_mouvement', $date)
                    ->where('type', MouvementStock::TYPE_SORTIE)
                    ->sum('quantite_sortie');

                $stockFinal = $stockPreviousDay + $entrees - $sorties;

                $cardex[$month][$day] = [
                    'entrees' => $entrees,
                    'sorties' => $sorties,
                    'stock_final' => $stockFinal,
                ];

                $totalEntrees += $entrees;
                $totalSorties += $sorties;
                $stockFinalMonth = $stockFinal;
                $stockPreviousDay = $stockFinal;
            }

            $monthTotals[$month] = [
                'total_entrees' => $totalEntrees,
                'total_sorties' => $totalSorties,
                'stock_final' => $stockFinalMonth,
            ];
        }

        // === Split months and days for the PDF structure ===
        $firstHalfMonths = array_slice($months, 0, 6, true);  // 1 → 6
        $secondHalfMonths = array_slice($months, 6, 6, true); // 7 → 12

        // Each “section” for one page of the PDF
        $pages = [
            [
                'months' => $firstHalfMonths,
                'days' => range(1, 15),
            ],
            [
                'months' => $firstHalfMonths,
                'days' => range(16, 31),
            ],
            [
                'months' => $secondHalfMonths,
                'days' => range(1, 15),
            ],
            [
                'months' => $secondHalfMonths,
                'days' => range(16, 31),
            ],
        ];

        $fileName = "cardex-{$article->reference}-{$year}.pdf";

        return Pdf::view('pdf.cardex', [
            'article' => $article,
            'year' => $year,
            'cardex' => $cardex,
            'monthTotals' => $monthTotals,
            'pages' => $pages,
        ])
            ->headerView('pdf.cardex-header', ['article' => $article])
            ->footerView('pdf.F')
            ->format(Format::A4)
            ->orientation(Orientation::Landscape)
            ->margins(0, 5, 0, 5);
        // ->download($fileName);
    }
}
