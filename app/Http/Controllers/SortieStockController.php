<?php
// app/Http/Controllers/SortieStockController.php

namespace App\Http\Controllers;

use App\Http\Resources\IndexSortieStockResource;
use App\Http\Resources\ShowSortieStockResource;
use App\Models\SortieStock;
use App\Models\LigneSortieStock;
use App\Models\Article;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class SortieStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SortieStock::with([
                'demandeur',
                'lignesSortie.article',
            ])->orderBy('created_at', 'desc');

            // Filtrage par statut
            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }

            // Filtrage par date
            if ($request->filled('date_debut')) {
                $query->where('date_sortie', '>=', $request->date_debut);
            }

            if ($request->filled('date_fin')) {
                $query->where('date_sortie', '<=', $request->date_fin);
            }

            // Recherche
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('numero', 'like', '%' . $search . '%')
                      ->orWhere('motif', 'like', '%' . $search . '%')
                      ->orWhereHas('demandeur', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
                });
            }

            $sortieStocks = $query->paginate(20)->withQueryString();

            // Statistiques
            $stats = [
                'total' => SortieStock::count(),
                'en_attente' => SortieStock::where('statut', 'attente_validation')->count(),
                'validee' => SortieStock::where('statut', 'validee')->count(),
                'annulee' => SortieStock::where('statut', 'annulee')->count(),

            ];

            return inertia('Stock/SortieStocks/Index', [
                'sorties' => IndexSortieStockResource::collection($sortieStocks),
                'stats' => $stats,
                'filters' => $request->only(['statut', 'date_debut', 'date_fin', 'search'])
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SortieStock $sortieStock)
    {
        $sortieStock->load([
            'lignesSortie.article',
            'demandeur',
            'createdBy'
        ]);

        return Inertia::render('Stock/SortieStocks/ShowSortieModal', [
            'sortie' => ShowSortieStockResource::make($sortieStock)
        ]);
    }

    
}