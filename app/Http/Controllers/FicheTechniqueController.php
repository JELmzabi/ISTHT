<?php

namespace App\Http\Controllers;

use App\Enums\FicheType;
use App\Http\Requests\CreateFicheTechniqueRequest;
use App\Http\Requests\UpdateFicheTechniqueRequest;
use App\Http\Resources\EditFicheTechniqueResource;
use App\Http\Resources\ShowFicheTechniqueResource;
use App\Models\Article;
use App\Models\Etape;
use App\Models\FicheTechnique;
use App\Models\MouvementStock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\LaravelPdf\Facades\Pdf;

class FicheTechniqueController extends Controller
{
    public function pedagogique(Request $request)
    {
        $search = $request->search;

        $fiches = FicheTechnique::pedagogique()
        ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('plat', 'like', "%{$search}%")
                    ->orWhere('responsable', 'like', "%{$search}%");
                });
            })
        ->paginate(10)
        ->withQueryString();


        return Inertia::render('Fiches/PedagogiqueIndex', [
            'fiches' => $fiches,
            'filters' => request()->all('search', 'trashed'),
        ]);
    }

    public function collectivite(Request $request)
    {
        $search = $request->search;

        $fiches = FicheTechnique::collectivite()
        ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('plat', 'like', "%{$search}%")
                    ->orWhere('responsable', 'like', "%{$search}%");
                });
            })
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Fiches/CollectiveIndex', [
            'fiches' => $fiches,
            'filters' => request()->all('search', 'trashed'),
        ]);
    }

    public function create()
    {
        $articles = Article::actives()->get(['id', 'designation']);

        $data = [
            'articles' => $articles,
            'types' => FicheType::toSelect()
        ];


        if (auth()->user()->isAdmin()) {
            $data['demandeurs'] = User::where('role', 'DEMANDEUR')->get(['id', 'name']);
        }

        return Inertia::modal('Fiches/CreateFicheModal', $data)->baseUrl(url()->previous());
    }

    public function store(UpdateFicheTechniqueRequest $request)
    {

        DB::transaction(function () use ($request) {

            $created_by = auth()->user()->isAdmin() ? $request->demandeur : auth()->user()->id;
            ## Create fiche technique
            $fiche = FicheTechnique::create([
                'type' => $request->type,
                'nom' => $request->nom,
                'responsable' => $request->responsable,
                'plat' => $request->plat,
                'effectif' => $request->effectif,
                'created_by' => $created_by ?? auth()->user()->id
            ]);

            # Create fiche technique etapes
            collect($request->etapes)->each(function ($ficheEtape) use ($fiche) {
                $etape = $fiche->etapes()->create([
                    'title' => $ficheEtape['title'],
                ]);

                # Create etape ingredients
                foreach ($ficheEtape['articles'] as $ingredient) {
                    $articleFromLastEntree = MouvementStock::entrees()->where('article_id', $ingredient['article_id'])->latest('date_mouvement')->first();

                    $etape->ingredients()->create([
                        'article_id' => $ingredient['article_id'],
                        'quantite' => $ingredient['quantite'],
                        'prix_unitaire' => $articleFromLastEntree->prix_unitaire,
                        'taux_tva' => $articleFromLastEntree->taux_tva,
                    ]);
                }
            });
            
            
        });
        
    }

    public function show(FicheTechnique $fiche)
    {
        $fiche->load(['etapes', 'etapes.ingredients', 'etapes.ingredients.article', 'user']);

        return Inertia::modal('Fiches/ShowFicheModal', [
            'fiche' => ShowFicheTechniqueResource::make($fiche)
        ])->baseUrl(url()->previous());
    }

    public function edit(FicheTechnique $fiche)
    {
        $fiche->load(['etapes', 'etapes.ingredients', 'etapes.ingredients.article', 'user']);

        $articles = Article::actives()->get(['id', 'designation']);

        $data = [
            'articles' => $articles,
            'types' => FicheType::toSelect(),
            'fiche' => EditFicheTechniqueResource::make($fiche)
        ];


        if (auth()->user()->isAdmin()) {
            $data['demandeurs'] = User::where('role', 'DEMANDEUR')->get(['id', 'name']);
        }

        return Inertia::render('Fiches/EditFicheModal', $data);
        // ])->baseUrl(url()->previous());
    }

    public function update(UpdateFicheTechniqueRequest $request, FicheTechnique $fiche)
    {
        DB::transaction(function () use ($request, $fiche) {

            $updated_by = auth()->user()->isAdmin() ? $request->demandeur : auth()->user()->id;

            // Update fiche technique main fields
            $fiche->update([
                'type' => $request->type,
                'nom' => $request->nom,
                'responsable' => $request->responsable,
                'plat' => $request->plat,
                'effectif' => $request->effectif,
                'created_by' => $updated_by ?? auth()->user()->id,
            ]);

            // --- Sync etapes ---
            // Remove etapes that were deleted
            $requestEtapeIds = collect($request->etapes)->pluck('id')->filter()->all();
            $fiche->etapes()->whereNotIn('id', $requestEtapeIds)->each(function ($etape) {
                $etape->ingredients()->delete();
                $etape->delete();
            });

            // Loop through request etapes
            foreach ($request->etapes as $ficheEtape) {

                if (!empty($ficheEtape['id'])) {
                    // Existing etape → update
                    $etape = $fiche->etapes()->find($ficheEtape['id']);
                    $etape->update(['title' => $ficheEtape['title']]);

                    // Sync ingredients: remove missing, update existing, add new
                    $requestIngredientIds = collect($ficheEtape['articles'])->pluck('id')->filter()->all();
                    $etape->ingredients()->whereNotIn('id', $requestIngredientIds)->delete();

                    foreach ($ficheEtape['articles'] as $ingredient) {
                        $articleFromLastEntree = MouvementStock::entrees()
                            ->where('article_id', $ingredient['article_id'])
                            ->latest('date_mouvement')
                            ->first();

                        if (!empty($ingredient['id'])) {
                            // Update existing ingredient
                            $etapeIngredient = $etape->ingredients()->find($ingredient['id']);
                            $etapeIngredient->update([
                                'article_id' => $ingredient['article_id'],
                                'quantite' => $ingredient['quantite'],
                                'prix_unitaire' => $articleFromLastEntree->prix_unitaire,
                                'taux_tva' => $articleFromLastEntree->taux_tva,
                            ]);
                        } else {
                            // New ingredient
                            $etape->ingredients()->create([
                                'article_id' => $ingredient['article_id'],
                                'quantite' => $ingredient['quantite'],
                                'prix_unitaire' => $articleFromLastEntree->prix_unitaire,
                                'taux_tva' => $articleFromLastEntree->taux_tva,
                            ]);
                        }
                    }

                } else {
                    // New etape → create
                    $etape = $fiche->etapes()->create([
                        'title' => $ficheEtape['title'],
                    ]);

                    foreach ($ficheEtape['articles'] as $ingredient) {
                        $articleFromLastEntree = MouvementStock::entrees()
                            ->where('article_id', $ingredient['article_id'])
                            ->latest('date_mouvement')
                            ->first();

                        $etape->ingredients()->create([
                            'article_id' => $ingredient['article_id'],
                            'quantite' => $ingredient['quantite'],
                            'prix_unitaire' => $articleFromLastEntree->prix_unitaire,
                            'taux_tva' => $articleFromLastEntree->taux_tva,
                        ]);
                    }
                }
            }

        });
    }


    public function destroy(FicheTechnique $fiche)
    {
        $fiche->delete();

        return redirect()->back();
    }


    public function export(FicheTechnique $fiche)
    {
        $totalTtc = $fiche->etapes->sum(function ($etape) {
            return $etape->ingredients->sum('total_ttc');
        });

        return Pdf::view('pdf.fiche-pedagogique', [
            'fiche' => $fiche,
            'totalTtc' => $totalTtc,
            'total_effectif' => round($totalTtc / $fiche->effectif)
        ])->download('fiche-pedagogique-' . $fiche->nom . '.pdf');
    }
}
