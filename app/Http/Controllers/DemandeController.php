<?php

namespace App\Http\Controllers;

use App\Enums\DemandeStatut;
use App\Http\Resources\EditDemendeResource;
use App\Http\Resources\MesDemendesResource;
use App\Http\Resources\ShowDemendeResource;
use App\Models\Article;
use App\Models\Demande;
use App\Models\MouvementStock;
use App\Rules\InStockRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DemandeController extends Controller
{


    public function mesDemandes(Request $request)
    {
        $user = auth()->user();

        $search = $request->get('search');
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $demandes = Demande::query()
            ->with(['valideur'])
            ->withCount('articles')
            ->where('demandeur_id', $user->id)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('numero', 'like', "%{$search}%")
                    ->orWhere('objet', 'like', "%{$search}%");
                });
            })
            ->when($status, fn($query) => $query->where('statut', $status))
            ->when($startDate, fn($query) => $query->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($query) => $query->whereDate('created_at', '<=', $endDate))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Demandes/MesDemandes', [
            'demandes' => MesDemendesResource::collection($demandes),
            'filters' => [
                'search' => $search,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    public function create(Request $request) {
        $articles = Article::all(['id', 'designation']);
        return Inertia::render('Demandes/CreateDemandeModal', [
            'articles' => $articles
        ]);
    }
    
    public function store(Request $request) {
        $request->validate([
            'articles' => 'required|array|min:1',
            'articles.*.article_id' => ['required', 'exists:articles,id'],
            'articles.*.quantite' => ['required', 'numeric', 'min:1', new InStockRule],
            'motif' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
  
            $demande = Demande::create([
                'numero' => Demande::generateNumero(),
                'demandeur_id' => auth()->user()->id,
                'motif' => $request->input('motif'),
                'statut' => DemandeStatut::EN_ATTENTE,
            ]);
            
            foreach ($request->input('articles') as $article) {
                $demande->articles()->create([
                    'article_id' => $article['article_id'],
                    'quantite_demandee' => $article['quantite'],
                ]);
            }
            
        });

        return redirect()->back();
    }

    public function edit(Demande $demande) {
        $this->ensureAllowed($demande);
        
        $articles = Article::all(['id', 'designation']);
        $demande->load(['articles']);

        return Inertia::modal('Demandes/EditDemandeModal', [
            'demande' => EditDemendeResource::make($demande),
            'articles' => $articles
        ])->baseRoute('demandes.mes-demandes');
    }

    public function update(Request $request, Demande $demande) {
        
        $this->ensureAllowed($demande);
        
        $request->validate([
            'articles' => 'required|array|min:1',
            'articles.*.article_id' => ['required', 'exists:articles,id'],
            'articles.*.quantite' => ['required', 'numeric', 'min:1', new InStockRule],
            'motif' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $demande) {
  
            $demande->update([
                'motif' => $request->input('motif'),
            ]);
            
            foreach ($request->input('articles') as $article) {
                $demande->articles()->delete();

                // Add new/updated articles
                foreach ($request->input('articles') as $article) {
                    $demande->articles()->create([
                        'article_id' => $article['article_id'],
                        'quantite_demandee' => $article['quantite'],
                    ]);
                }
            }
            
        });

        return redirect()->back();
    }

    public function show(Demande $demande) {
        $this->ensureAllowed($demande);
        
        $demande->load(['articles', 'valideur']);

        return Inertia::modal('Demandes/ShowDemandeModal', [
            'demande' => ShowDemendeResource::make($demande)
        ])->baseRoute('demandes.mes-demandes');
    }

    public function cancel(Demande $demande) {
        $this->ensureAllowed($demande);
        $demande->update(['statut' => DemandeStatut::ANNULEE]);
        return redirect()->back();
    }

    private function ensureAllowed($demande)
    {
        if ($demande->demandeur_id !== auth()->user()->id || $demande->statut !== DemandeStatut::EN_ATTENTE->value) {
            abort(404);
        }
    }

}
