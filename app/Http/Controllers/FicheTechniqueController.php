<?php

namespace App\Http\Controllers;

use App\Enums\FicheType;
use App\Http\Requests\CreateFicheTechniqueRequest;
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
        $articles = Article::all(['id', 'designation']);

        $data = [
            'articles' => $articles,
            'types' => FicheType::toSelect()
        ];


        if (auth()->user()->isAdmin()) {
            $data['demandeurs'] = User::where('role', 'DEMANDEUR')->get(['id', 'name']);
        }

        return Inertia::modal('Fiches/CreateFicheModal', $data);
    }

    public function store(CreateFicheTechniqueRequest $request)
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
}
