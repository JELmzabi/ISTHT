<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\BonCommande;
use App\Models\Demande;
use App\Models\Fournisseur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\LaravelPdf\Facades\Pdf;

class DashboardController extends Controller
{
    public function index() {
        
        // ---- KPI Cards ----
        $totalUsers = User::count();
        $activeFournisseurs = Fournisseur::where('est_actif', true)->count();
        $totalArticles = Article::count();
        $totalBCs = BonCommande::count();
        $pendingDemandes = Demande::where('statut', 'en_attente_validation')->count();

        
        // Example stock value (sum of quantite_stock * prix_unitaire)
        // $totalStockValue = Article::sum(DB::raw('quantite_stock * prix_unitaire'));

        // ---- Bon Commande Status distribution ----
        $bonCommandeStatus = BonCommande::selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        // ---- Top 5 Articles in Stock ----
        $topArticles = Article::orderByDesc('quantite_stock')
            ->take(5)
            ->get(['designation', 'quantite_stock']);
        

        // ---- Low Stock Articles ----
        $lowStockArticles = Article::whereColumn('quantite_stock', '<', 'seuil_minimal')
            ->get(['reference', 'designation', 'quantite_stock', 'seuil_minimal']);

        
        // ---- Recent Demandes ----
        $recentDemandes = Demande::latest()
            ->take(5)
            ->get(['numero', 'motif', 'statut', 'created_at'])
            ->map(fn($d) => [
                'numero' => $d->numero,
                'demandeur' => $d->user->name ?? 'N/A',
                'motif' => $d->motif,
                'statut' => $d->statut,
                'date' => $d->created_at->format('Y-m-d')
            ]);


        $fournisseurSpending = Fournisseur::select('fournisseurs.id', 'fournisseurs.nom')
            ->leftJoin('entree_stocks', 'entree_stocks.fournisseur_id', '=', 'fournisseurs.id')
            ->leftJoin('ligne_entree_stocks', 'ligne_entree_stocks.entree_stock_id', '=', 'entree_stocks.id')
            ->selectRaw('ROUND(COALESCE(SUM(ligne_entree_stocks.prix_total * (1 + (ligne_entree_stocks.taux_tva / 100))), 0), 2) as total_spent')
            ->groupBy('fournisseurs.id', 'fournisseurs.nom')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalUsers' => $totalUsers,
                'activeFournisseurs' => $activeFournisseurs,
                'totalArticles' => $totalArticles,
                'totalBCs' => $totalBCs,
                'pendingDemandes' => $pendingDemandes,
                // 'totalStockValue' => $totalStockValue,
            ],
            'bonCommandeStatus' => $bonCommandeStatus,
            'topArticles' => $topArticles,
            'lowStockArticles' => $lowStockArticles,
            'recentDemandes' => $recentDemandes,
            'fournisseurSpending' => $fournisseurSpending,
        ]);
    }
}
