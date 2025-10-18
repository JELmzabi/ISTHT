<?php
// app/Http/Controllers/SortieStockController.php

namespace App\Http\Controllers;

use App\Http\Resources\IndexSortieStockResource;
use App\Models\SortieStock;
use App\Models\LigneSortieStock;
use App\Models\Article;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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
        try {
            $sortieStock->load([
                'client',
                'lignesSortie.article',
                'createdBy'
            ]);

            return inertia('Stock/SortieStocks/Show', [
                'sortieStock' => $sortieStock
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Erreur lors du chargement: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SortieStock $sortieStock)
    {
        try {
            $sortieStock->load(['lignesSortie.article']);

            $articles = Article::where('est_actif', true)
                ->orderBy('designation')
                ->get();

            $clients = Client::where('est_actif', true)
                ->orderBy('nom')
                ->get();

            return inertia('Stock/SortieStocks/Edit', [
                'sortieStock' => $sortieStock,
                'articles' => $articles,
                'clients' => $clients,
                'types_sortie' => [
                    SortieStock::TYPE_VENTE => 'Vente',
                    SortieStock::TYPE_TRANSFERT => 'Transfert',
                    SortieStock::TYPE_PERTE => 'Perte/Dommage',
                    SortieStock::TYPE_AJUSTEMENT => 'Ajustement'
                ]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Erreur lors du chargement: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SortieStock $sortieStock)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'type_sortie' => 'required|string|in:vente,transfert,perte,ajustement',
                'client_id' => 'nullable|exists:clients,id',
                'date_sortie' => 'required|date',
                'motif' => 'required|string|max:500',
                'lignes_sortie' => 'required|array|min:1',
                'lignes_sortie.*.id' => 'nullable|exists:ligne_sortie_stocks,id',
                'lignes_sortie.*.article_id' => 'required|exists:articles,id',
                'lignes_sortie.*.quantite' => 'required|numeric|min:0.01',
                'lignes_sortie.*.prix_unitaire' => 'required|numeric|min:0',
                'notes' => 'nullable|string'
            ]);

            // Mettre à jour la sortie de stock
            $sortieStock->update([
                'type_sortie' => $validated['type_sortie'],
                'client_id' => $validated['client_id'] ?? null,
                'date_sortie' => $validated['date_sortie'],
                'motif' => $validated['motif'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Gérer les lignes de sortie
            $ligneIds = [];
            foreach ($validated['lignes_sortie'] as $ligneData) {
                if (isset($ligneData['id'])) {
                    // Mettre à jour la ligne existante
                    $ligne = LigneSortieStock::find($ligneData['id']);
                    if ($ligne) {
                        $ligne->update($ligneData);
                        $ligneIds[] = $ligne->id;
                    }
                } else {
                    // Créer une nouvelle ligne
                    $newLigne = LigneSortieStock::create(array_merge($ligneData, [
                        'sortie_stock_id' => $sortieStock->id
                    ]));
                    $ligneIds[] = $newLigne->id;
                }
            }

            // Supprimer les lignes qui n'existent plus
            LigneSortieStock::where('sortie_stock_id', $sortieStock->id)
                ->whereNotIn('id', $ligneIds)
                ->delete();

            DB::commit();

            return redirect()->route('sortie-stocks.show', $sortieStock->id)
                ->with('success', 'Sortie de stock modifiée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la modification: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SortieStock $sortieStock)
    {
        try {
            DB::beginTransaction();

            // Vérifier si la sortie peut être supprimée
            if ($sortieStock->statut !== SortieStock::STATUT_VALIDE) {
                throw new \Exception('Seules les sorties avec statut "valide" peuvent être supprimées.');
            }

            $sortieStock->delete();

            DB::commit();

            return redirect()->route('sortie-stocks.index')
                ->with('success', 'Sortie de stock supprimée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }

    /**
     * Download PDF for sortie stock
     */
    public function downloadPdf(SortieStock $sortieStock)
    {
        try {
            $sortieStock->load([
                'client',
                'lignesSortie.article',
                'createdBy'
            ]);

            $pdf = Pdf::loadView('pdf.sortie-stock', [
                'sortieStock' => $sortieStock
            ]);

            return $pdf->download("sortie-stock-{$sortieStock->numero_affichage}.pdf");

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Annuler une sortie de stock
     */
    public function annuler(SortieStock $sortieStock)
    {
        try {
            DB::beginTransaction();

            $sortieStock->update([
                'statut' => SortieStock::STATUT_ANNULE
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Sortie de stock annulée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'error' => 'Erreur lors de l\'annulation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Vérifier la disponibilité du stock pour un article
     */
    public function checkDisponibilite(Request $request)
    {
        try {
            $request->validate([
                'article_id' => 'required|exists:articles,id',
                'quantite' => 'required|numeric|min:0.01'
            ]);

            $article = Article::find($request->article_id);
            $disponible = $article->quantite_stock >= $request->quantite;

            return response()->json([
                'disponible' => $disponible,
                'stock_actuel' => $article->quantite_stock,
                'article' => $article->designation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la vérification: ' . $e->getMessage()
            ], 500);
        }
    }
}