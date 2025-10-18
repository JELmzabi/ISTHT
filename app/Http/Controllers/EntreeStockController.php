<?php
// app/Http/Controllers/EntreeStockController.php

namespace App\Http\Controllers;

use App\Exports\EntreeExport;
use App\Exports\FournisseursExport;
use App\Http\Resources\ExportEntreeStockRecource;
use App\Models\EntreeStock;
use App\Models\LigneEntreeStock;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\BonReception;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\LaravelPdf\Facades\Pdf;

class EntreeStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // Dans EntreeStockController.php
public function index(Request $request)
{
    $query = EntreeStock::with(['bonReception', 'fournisseur', 'lignesEntree.article'])
        ->orderBy('created_at', 'desc');

    // Filtrage par statut
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    // Filtrage par date
    if ($request->filled('date_debut')) {
        $query->where('date_entree', '>=', $request->date_debut);
    }
    if ($request->filled('date_fin')) {
        $query->where('date_entree', '<=', $request->date_fin);
    }

    $entreeStocks = $query->paginate(20)->withQueryString();

    // Statistiques
    $stats = [
        'total' => EntreeStock::count(),
        'attente_validation' => EntreeStock::where('statut', 'attente_validation')->count(),
        'valide' => EntreeStock::where('statut', 'valide')->count(),
        'annule' => EntreeStock::where('statut', 'annule')->count(),
    ];

    return inertia('Stock/EntreeStocks/Index', [
        'entreeStocks' => $entreeStocks,
        'stats' => $stats,
        'filters' => $request->only(['statut', 'date_debut', 'date_fin'])
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $fournisseurs = Fournisseur::where('est_actif', true)
                ->orderBy('nom')
                ->get();

            $articles = Article::where('est_actif', true)
                ->orderBy('designation')
                ->get();

            $bonReceptions = BonReception::whereDoesntHave('entreeStock')
                ->with('fournisseur')
                ->orderBy('created_at', 'desc')
                ->get();

            return inertia('Stock/EntreeStocks/Create', [
                'fournisseurs' => $fournisseurs,
                'articles' => $articles,
                'bonReceptions' => $bonReceptions
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Erreur lors du chargement: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'fournisseur_id' => 'required|exists:fournisseurs,id',
                'bon_reception_id' => 'nullable|exists:bon_receptions,id',
                'date_entree' => 'required|date',
                'lignes_entree' => 'required|array|min:1',
                'lignes_entree.*.article_id' => 'required|exists:articles,id',
                'lignes_entree.*.quantite' => 'required|numeric|min:0.01',
                'lignes_entree.*.prix_unitaire' => 'required|numeric|min:0',
                'lignes_entree.*.taux_tva' => 'required|numeric|min:0',
                'notes' => 'nullable|string'
            ]);

            // Créer l'entrée en stock
            $entreeStock = EntreeStock::create([
                'numero' => EntreeStock::genererNumero(),
                'bon_reception_id' => $validated['bon_reception_id'] ?? null,
                'fournisseur_id' => $validated['fournisseur_id'],
                'date_entree' => $validated['date_entree'],
                'statut' => EntreeStock::STATUT_VALIDE,
                'notes' => $validated['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Créer les lignes d'entrée
            foreach ($validated['lignes_entree'] as $ligneData) {
                LigneEntreeStock::create([
                    'entree_stock_id' => $entreeStock->id,
                    'article_id' => $ligneData['article_id'],
                    'quantite' => $ligneData['quantite'],
                    'prix_unitaire' => $ligneData['prix_unitaire'],
                    'taux_tva' => $ligneData['taux_tva'],
                ]);
            }

            DB::commit();

            return redirect()->route('entree-stocks.show', $entreeStock->id)
                ->with('success', 'Entrée en stock créée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la création: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EntreeStock $entreeStock)
    {
        try {
            $entreeStock->load([
                'fournisseur',
                'bonReception',
                'lignesEntree.article',
                'createdBy'
            ]);

            return inertia('Stock/EntreeStocks/Show', [
                'entreeStock' => $entreeStock
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
    public function edit(EntreeStock $entreeStock)
    {
        try {
            $entreeStock->load(['lignesEntree.article']);

            $fournisseurs = Fournisseur::where('est_actif', true)
                ->orderBy('nom')
                ->get();

            $articles = Article::where('est_actif', true)
                ->orderBy('designation')
                ->get();

            return inertia('Stock/EntreeStocks/Edit', [
                'entreeStock' => $entreeStock,
                'fournisseurs' => $fournisseurs,
                'articles' => $articles
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
    public function update(Request $request, EntreeStock $entreeStock)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'fournisseur_id' => 'required|exists:fournisseurs,id',
                'date_entree' => 'required|date',
                'lignes_entree' => 'required|array|min:1',
                'lignes_entree.*.id' => 'nullable|exists:ligne_entree_stocks,id',
                'lignes_entree.*.article_id' => 'required|exists:articles,id',
                'lignes_entree.*.quantite' => 'required|numeric|min:0.01',
                'lignes_entree.*.prix_unitaire' => 'required|numeric|min:0',
                'lignes_entree.*.taux_tva' => 'required|numeric|min:0',
                'notes' => 'nullable|string'
            ]);

            // Mettre à jour l'entrée en stock
            $entreeStock->update([
                'fournisseur_id' => $validated['fournisseur_id'],
                'date_entree' => $validated['date_entree'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Gérer les lignes d'entrée
            $ligneIds = [];
            foreach ($validated['lignes_entree'] as $ligneData) {
                if (isset($ligneData['id'])) {
                    // Mettre à jour la ligne existante
                    $ligne = LigneEntreeStock::find($ligneData['id']);
                    if ($ligne) {
                        $ligne->update($ligneData);
                        $ligneIds[] = $ligne->id;
                    }
                } else {
                    // Créer une nouvelle ligne
                    $newLigne = LigneEntreeStock::create(array_merge($ligneData, [
                        'entree_stock_id' => $entreeStock->id
                    ]));
                    $ligneIds[] = $newLigne->id;
                }
            }

            // Supprimer les lignes qui n'existent plus
            LigneEntreeStock::where('entree_stock_id', $entreeStock->id)
                ->whereNotIn('id', $ligneIds)
                ->delete();

            DB::commit();

            return redirect()->route('entree-stocks.show', $entreeStock->id)
                ->with('success', 'Entrée en stock modifiée avec succès');

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
    public function destroy(EntreeStock $entreeStock)
    {
        try {
            DB::beginTransaction();

            // Vérifier si l'entrée peut être supprimée
            if ($entreeStock->statut !== EntreeStock::STATUT_VALIDE) {
                throw new \Exception('Seules les entrées avec statut "valide" peuvent être supprimées.');
            }

            $entreeStock->delete();

            DB::commit();

            return redirect()->route('entree-stocks.index')
                ->with('success', 'Entrée en stock supprimée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }

    /**
     * Download PDF for entree stock
     */
    public function downloadPdf(EntreeStock $entreeStock)
    {
        
        $entreeStock->load([
                'fournisseur',
                'bonReception',
                'lignesEntree.article',
                'createdBy'
            ]);

        $fileName = "entree-stock-{$entreeStock->numero}.pdf";

        // return view('pdf.fiche-entree');
        return Pdf::view('pdf.bon-entree', [
            'entree' => $entreeStock
        ])->download($fileName);
    }

    /**
     * Annuler une entrée en stock
     */// Dans app/Http/Controllers/EntreeStockController.php

/**
 * Valider une entrée de stock
 */
public function valider(EntreeStock $entreeStock)
{
    try {
        DB::transaction(function () use ($entreeStock) {
            
            if (!$entreeStock->peut_etre_valide) {
                return redirect()->back()->withErrors([
                'error' => 'Cette entrée de stock ne peut pas être validée.'
                ]);
            }

        
            $entreeStock->update(['statut' => EntreeStock::STATUT_VALIDE]);
            
            $lignesEntree = $entreeStock->lignesEntree()->with('article')->get();
            // Mettre à jour les stocks des articles
            foreach ($lignesEntree as $ligne) {

                if ($ligne->article) {
                    
                    $ligne->article->increment('quantite_stock', $ligne->quantite);
                    
                    $nouvelleQuantiteActuelle = $ligne->article->quantite_stock;
                    
                    MouvementStock::create([
                        'type' => MouvementStock::TYPE_ENTREE,
                        'article_id' => $ligne->article_id,
                        'created_by' => auth()->id() ?? 1,
                        'date_mouvement' => now(),
                        'prix_unitaire' => $ligne->prix_unitaire,
                        'taux_tva' => $ligne->taux_tva,
                        'type_mouvement' => MouvementStock::TYPE_ENTREE,
                        'quantite_entree' => $ligne->quantite,
                        'quantite_actuelle' => $nouvelleQuantiteActuelle,
                        'motif' => 'Entrée de stock validée',
                    ]);
                }
            }
        });
        
        DB::commit();
        return redirect()->back()->with('success', 'Entrée de stock validée avec succès. Les stocks ont été mis à jour.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withErrors([
            'error' => 'Erreur lors de la validation: ' . $e->getMessage()
        ]);
    }
}

/**
 * Annuler une entrée de stock
 */
public function annuler(EntreeStock $entreeStock)
{
    try {
        if (!$entreeStock->peut_etre_annule) {
            return redirect()->back()->withErrors([
                'error' => 'Cette entrée de stock ne peut pas être annulée.'
            ]);
        }

        $entreeStock->annuler();

        return redirect()->back()->with('success', 'Entrée de stock annulée avec succès.');

    } catch (\Exception $e) {
        return redirect()->back()->withErrors([
            'error' => 'Erreur lors de l\'annulation: ' . $e->getMessage()
        ]);
    }
}

    function createExport() 
    {
        
        return Inertia::modal('Stock/EntreeStocks/CreateExportModal')->baseRoute('entree-stocks.index');
    }


    ############# Expor Fiche entree stock Excel ##################
    // public function export(Request $request) 
    // {
    //     $request->validate([
    //         'start_date' => 'required|date',
    //         'end_date' => 'nullable|date',
    //     ]);

    //     $startDate = Carbon::parse($request->start_date)->startOfDay();

    //     $query = MouvementStock::entrees()->load([
    //         'article'
    //     ]);

    //     if ($request->end_date) {
    //         $endDate = Carbon::parse($request->end_date)->endOfDay();
    //         $data = $query->whereBetween('created_at', [$startDate, $endDate])->get();
    //     } else {
    //         // get data for entire month of start_date
    //         $data = $query->whereYear('created_at', $startDate->year)
    //                     ->whereMonth('created_at', $startDate->month)
    //                     ->get();
    //     }

    //     $data = ExportEntreeStockRecource::collection($data);

    //     return Excel::download(new EntreeExport($data), 'entree_stock.xlsx');
    // }


    public function export(Request $request) 
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();

        $query = MouvementStock::entrees()->with([
            'article'
        ]);

        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;
        if ($request->end_date) {
            $data = $query->whereBetween('created_at', [$startDate, $endDate])->get();
        } else {
            // get data for entire month of start_date
            $data = $query->whereYear('created_at', $startDate->year)
                        ->whereMonth('created_at', $startDate->month)
                        ->get();
        }

        $articles = ExportEntreeStockRecource::collection($data)->toArray($request);

        return Pdf::view('pdf.fiche-entree', 
                    compact('articles', 'startDate', 'endDate')
                )->download('fiche-entree.pdf');
    }
}