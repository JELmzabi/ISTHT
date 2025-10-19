<?php
// app/Http/Controllers/SortieStockController.php

namespace App\Http\Controllers;

use App\Enums\DemandeStatut;
use App\Http\Resources\IndexSortieStockResource;
use App\Http\Resources\ShowSortieStockResource;
use App\Models\SortieStock;
use App\Models\LigneSortieStock;
use App\Models\Article;
use App\Models\Client;
use App\Models\Demande;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Spatie\LaravelPdf\Facades\Pdf;

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


    public function showApprove(SortieStock $sortieStock) {

        return Inertia::modal('Stock/SortieStocks/ApproveModal', [
            'sortie' => ShowSortieStockResource::make($sortieStock)
        ])->baseRoute('sortie-stocks.index');
    } 

    public function approve(Request $request, SortieStock $sortieStock) {
        // $this->authorize('approve', $demande);

        $request->validate([
            'commentaire_validation' => 'nullable|string|max:500',
        ]);


        $errors = [];

        foreach ($sortieStock->lignesSortie as $ligne) {

            if ($ligne->article->quantite_stock < $ligne->quantite) {
                $errors[] = "La quantité demandée pour l'article « {$ligne->article->designation} » ({$ligne->quantite}) dépasse le stock disponible ({$ligne->article->quantite_stock}).";
            }
            
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'lignesSortie' => [$errors],
            ]);
        }
        
        DB::transaction(function () use ($sortieStock, $request) {
            
            $sortieStock->update([
                'statut' => SortieStock::STATUT_ATTENTE_LIVRAISON,
                'commentaire_validation' => $request->input('commentaire_validation'),
                'date_validation' => now(),
            ]);

            $sortieStock->demande()->update([
                'statut' => DemandeStatut::EN_ATTENTE_LIVRAISON,
            ]);
            
            // foreach ($sortieStock->lignesSortie as $articleLine) {

            //     # Article line from the last entree stock
            //     $lastEntreeArticle = MouvementStock::entrees()->where('article_id', $articleLine->article_id)
            //                         ->orderBy('date_mouvement', 'desc')
            //                         ->orderBy('id', 'desc')
            //                         ->first();
                
            //     $sortieStock->lignesSortie()->create([
            //         'article_id' => $articleLine->article_id,
            //         'quantite' => $articleLine->quantite_demandee,
            //         'prix_unitaire' => $lastEntreeArticle->prix_unitaire,
            //         'taux_tva' => $lastEntreeArticle->taux_tva
            //     ]);
            // }
            
        });
        
        return redirect()->back();
    } 


    public function reject(Request $request, SortieStock $sortieStock) {
        // $this->authorize('approve', $sortieStock);

         $request->validate([
            'commentaire_validation' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($sortieStock, $request) {
            
            $sortieStock->update([
                'statut' => SortieStock::STATUT_ANNULE,
                'valide_par' => auth()->user()->id,
                'commentaire_validation' => $request->input('commentaire_validation'),
                'date_validation' => now(),
            ]);
            
            $sortieStock->demande()->update([
                'statut' => DemandeStatut::ANNULEE,
            ]);

        });

        
        return redirect()->back();
    } 

    public function livrer(Request $request, SortieStock $sortieStock) {
        // $this->authorize('approve', $sortieStock);

        $errors = [];

        foreach ($sortieStock->lignesSortie as $ligne) {

            if ($ligne->article->quantite_stock < $ligne->quantite) {
                $errors[] = "La quantité demandée pour l'article « {$ligne->article->designation} » ({$ligne->quantite}) dépasse le stock disponible ({$ligne->article->quantite_stock}).";
            }
            
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'lignesSortie' => [$errors],
            ]);
        }

        DB::transaction(function () use ($sortieStock, $request) {
            
            $sortieStock->update([
                'statut' => SortieStock::STATUT_LIVREE,
            ]);
            
            $sortieStock->demande()->update([
                'statut' => DemandeStatut::LIVREE,
            ]);

            
            $lignesEntree = $sortieStock->lignesSortie()->with('article')->get();

            // Mettre à jour les stocks des articles
            foreach ($lignesEntree as $ligne) {

                if ($ligne->article) {
                    
                    $ligne->article->decrement('quantite_stock', $ligne->quantite);
                    
                    $nouvelleQuantiteActuelle = $ligne->article->quantite_stock;
                    
                    MouvementStock::create([
                        'type' => MouvementStock::TYPE_SORTIE,
                        'article_id' => $ligne->article_id,
                        'created_by' => auth()->id(),
                        'date_mouvement' => now(),
                        'prix_unitaire' => $ligne->prix_unitaire,
                        'taux_tva' => $ligne->taux_tva,
                        'type_mouvement' => MouvementStock::TYPE_SORTIE,
                        'quantite_entree' => $ligne->quantite,
                        'quantite_actuelle' => $nouvelleQuantiteActuelle,
                        'motif' => 'Sortie de stock validée',
                        'sourceable_id' => $sortieStock->id,
                        'sourceable_type' => SortieStock::class
                    ]);
                }
            }

        });

        
        return redirect()->back();
    } 
    
    /**
     * Download Bon Sorie
     */
    public function downloadPdf(SortieStock $sortieStock)
    {
        $sortieStock->load([
            'lignesSortie.article',
        ]);

        return Pdf::view('pdf.bon-sortie', [
            'sortieStock' => $sortieStock
        ])->download("bon-sortie-{$sortieStock->numero}.pdf");
    }
}