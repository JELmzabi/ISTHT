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
            1 => 'JANVIER', 2 => 'FEVRIER', 3 => 'MARS', 4 => 'AVRIL',
            5 => 'MAI', 6 => 'JUIN', 7 => 'JUILLET', 8 => 'AOUT',
            9 => 'SEPTEMBRE', 10 => 'OCTOBRE', 11 => 'NOVEMBRE', 12 => 'DECEMBRE'
        ];

        $cardex = [];
        $monthTotals = [];
        $stockPreviousDay = 0;

        // Loop through months
        for ($month = 1; $month <= 12; $month++) {
            $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

            // Initialize monthly totals
            $totalEntrees = 0;
            $totalSorties = 0;
            $stockFinalMonth = 0;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);

                // Entrées du jour
                $entrees = MouvementStock::parArticle($article->id)
                    ->whereDate('date_mouvement', $date)
                    ->where('type', MouvementStock::TYPE_ENTREE)
                    ->sum('quantite_entree');

                // Sorties du jour
                $sorties = MouvementStock::parArticle($article->id)
                    ->whereDate('date_mouvement', $date)
                    ->where('type', MouvementStock::TYPE_SORTIE)
                    ->sum('quantite_sortie');

                // Stock final
                $stockFinal = $stockPreviousDay + $entrees - $sorties;

                // Save daily data
                $cardex[$month][$day] = [
                    'entrees' => $entrees,
                    'sorties' => $sorties,
                    'stock_final' => $stockFinal,
                ];

                // Update monthly totals
                $totalEntrees += $entrees;
                $totalSorties += $sorties;
                $stockFinalMonth = $stockFinal; // last day stock = monthly final

                // Update for next day
                $stockPreviousDay = $stockFinal;
            }

            // Save month totals
            $monthTotals[$month] = [
                'total_entrees' => $totalEntrees,
                'total_sorties' => $totalSorties,
                'stock_final' => $stockFinalMonth,
            ];
        }

        $firstHalfMonths = array_slice($months, 0, 6, true); // Jan → Jun
        $secondHalfMonths = array_slice($months, 6, 6, true); // Jul → Dec


        $fileName = "cardex-{$article->reference}-{$year}.pdf";
        
        return Pdf::view('pdf.cardex', [
            'article' => $article,
            'year' => $year,
            'months' => $months,
            'firstHalfMonths' => $firstHalfMonths,
            'secondHalfMonths' => $secondHalfMonths,
            'cardex' => $cardex,
            'monthTotals' => $monthTotals
        ])
        ->format(Format::A4)
        ->orientation(Orientation::Landscape)
        ->margins(10,0,10,0)
        ->download($fileName)
        ;
    }
}
