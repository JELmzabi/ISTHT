<?php

namespace App\Http\Controllers;

use App\Enums\DemandeStatut;
use App\Http\Resources\EditDemendeResource;
use App\Http\Resources\MesDemendesResource;
use App\Http\Resources\ShowDemendeResource;
use App\Models\Article;
use App\Models\Demande;
use App\Models\MouvementStock;
use App\Models\SortieStock;
use App\Models\User;
use App\Rules\InStockRule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class DemandeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('list', Demande::class);
        
        $user = auth()->user();

        $search = $request->get('search');
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $demandes = Demande::query()
            ->with(['valideur'])
            ->withCount('articles')
            ->when(!$user->isAdmin(), function ($query) use ($user) {
                $query->where('demandeur_id', $user->id);
            })
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

        return Inertia::render('Demandes/Index', [
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
        $this->authorize('create', Demande::class);
        
        $articles = Article::all(['id', 'designation']);

        $data = [
            'articles' => $articles
        ];

        if (auth()->user()->isAdmin()) {
            $data['demandeurs'] = User::where('role', 'DEMANDEUR')->get(['id', 'name']);
        }

        return Inertia::render('Demandes/CreateDemandeModal', $data);
    }
    
    public function store(Request $request) {
        $this->authorize('create', Demande::class);
        
        $request->validate([
            'demandeur' => ['nullable', Rule::requiredIf(fn () => auth()->user()->isAdmin()), 'integer', 'exists:users,id'],
            'fiche_technique' => 'required|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
            'articles' => 'required|array|min:1',
            'articles.*.article_id' => ['required', 'exists:articles,id'],
            'articles.*.quantite' => ['required', 'numeric', 'min:1', new InStockRule],
            'motif' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            
            $user_id = auth()->user()->isAdmin() ? $request->demandeur : auth()->user()->id;
            $demande = Demande::create([
                'numero' => Demande::generateNumero(),
                'demandeur_id' => auth()->user()->id,
                'motif' => $request->input('motif'),
                'statut' => DemandeStatut::CREE,
                'user_id' => $user_id,
            ]);
            
            foreach ($request->input('articles') as $article) {
                $demande->articles()->create([
                    'article_id' => $article['article_id'],
                    'quantite_demandee' => $article['quantite'],
                ]);
            }

            $demande->addMediaFromRequest('fiche_technique')->toMediaCollection('fiches_techniques');
            
        });

        return redirect()->back();
    }

    public function edit(Demande $demande) {
        $this->authorize('update', $demande);
        
        $articles = Article::all(['id', 'designation']);
        $demande->load(['articles']);

        $data = [
            'articles' => $articles,
            'demande' => EditDemendeResource::make($demande)
        ];

        if (auth()->user()->isAdmin()) {
            $data['demandeurs'] = User::where('role', 'DEMANDEUR')->get(['id', 'name']);
        }


        return Inertia::modal('Demandes/EditDemandeModal', $data)->baseRoute('demandes.index');
    }

    public function update(Request $request, Demande $demande) {
        
        $this->authorize('update', $demande);
        
        $request->validate([
            'demandeur' => ['nullable', Rule::requiredIf(fn () => auth()->user()->isAdmin()), 'integer', 'exists:users,id'],
            'fiche_technique' => 'nullable|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
            'articles' => 'required|array|min:1',
            'articles.*.article_id' => ['required', 'exists:articles,id'],
            'articles.*.quantite' => ['required', 'numeric', 'min:1', new InStockRule],
            'motif' => 'nullable|string|max:500',
        ]);

        
        DB::transaction(function () use ($request, $demande) {

            $user_id = auth()->user()->isAdmin() ? $request->demandeur : auth()->user()->id;
            $demande->update([
                'motif' => $request->input('motif'),
                'user_id' => $user_id,
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

            if ($request->hasFile('fiche_technique')) {
                $demande->addMediaFromRequest('fiche_technique')->toMediaCollection('fiches_techniques');
            }
            
        });

        return redirect()->back();
    }

    public function show(Demande $demande) {
        $this->authorize('show', $demande);
        
        $demande->load(['articles', 'valideur']);

        return Inertia::modal('Demandes/ShowDemandeModal', [
            'demande' => ShowDemendeResource::make($demande)
        ])->baseRoute('demandes.index');
    }

    public function cancel(Demande $demande) {
        $this->authorize('cancel', $demande);

        $demande->update(['statut' => DemandeStatut::ANNULEE]);
        return redirect()->back();
    }

    public function showApprove(Demande $demande) {
        $this->authorize('approve', $demande);

        return Inertia::modal('Demandes/ApproveModal', [
            'demande' => ShowDemendeResource::make($demande)
        ])->baseRoute('demandes.index');
    } 

    public function approve(Request $request, Demande $demande) {
        $this->authorize('approve', $demande);

        $request->validate([
            'commentaire_validation' => 'nullable|string|max:500',
        ]);

        $errors = [];

        foreach ($demande->articles as $ligne) {

            if ($ligne->article->quantite_stock < $ligne->quantite_demandee) {
                $errors[] = "La quantité demandée pour l'article « {$ligne->article->designation} » ({$ligne->quantite_demandee}) dépasse le stock disponible ({$ligne->article->quantite_stock}).";
            }

        }

        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'articlesError' => [$errors],
            ]);
        }


        
        DB::transaction(function () use ($demande, $request) {
            
            $demande->update([
                'statut' => DemandeStatut::EN_ATTENTE_LIVRAISON,
                'commentaire_validation' => $request->input('commentaire_validation'),
                'date_validation' => now(),
            ]);

            $sortieStock = SortieStock::create([
                'numero' => SortieStock::genererNumero(),
                'type_sortie' => SortieStock::TYPE_DEMANDE,
                'demandeur_id' => $demande->demandeur_id,
                'date_sortie' => now(),
                'demande_id' => $demande->id,
                'motif' => "Cette sortie est générée automatiquement à partir de la demande n° {$demande->numero}",
                'statut' => SortieStock::STATUT_ATTENTE_LIVRAISON,
            ]);
            
            foreach ($demande->articles as $articleLine) {

                # Article line from the last entree stock
                $lastEntreeArticle = MouvementStock::entrees()->where('article_id', $articleLine->article_id)
                                    ->orderBy('date_mouvement', 'desc')
                                    ->orderBy('id', 'desc')
                                    ->first();
                
                $sortieStock->lignesSortie()->create([
                    'article_id' => $articleLine->article_id,
                    'quantite' => $articleLine->quantite_demandee,
                    'prix_unitaire' => $lastEntreeArticle->prix_unitaire,
                    'taux_tva' => $lastEntreeArticle->taux_tva
                ]);
            }
            
        });
        
        return redirect()->back();
    } 


    public function reject(Request $request, Demande $demande) {
        $this->authorize('approve', $demande);

         $request->validate([
            'commentaire_validation' => 'nullable|string|max:500',
        ]);

        $demande->update([
            'statut' => DemandeStatut::ANNULEE,
            'commentaire_validation' => $request->input('commentaire_validation'),
            'date_validation' => now(),
        ]);

        
        return redirect()->back();
    } 

}
