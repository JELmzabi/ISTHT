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

        // ---- Fournisseur Spending (sum bon commande total) ----
        $fournisseurSpending = Fournisseur::withSum('bonCommandes as total_spent', 'montant_ttc')
            ->orderByDesc('total_spent')
            ->take(4)
            ->get(['nom', 'total_spent']);

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalUsers' => $totalUsers,
                'activeFournisseurs' => $activeFournisseurs,
                'totalArticles' => $totalArticles,
                'totalBCs' => $totalBCs,
                'pendingDemandes' => $pendingDemandes,
                'totalStockValue' => $totalStockValue,
            ],
            'bonCommandeStatus' => $bonCommandeStatus,
            'topArticles' => $topArticles,
            'lowStockArticles' => $lowStockArticles,
            'recentDemandes' => $recentDemandes,
            'fournisseurSpending' => $fournisseurSpending,
        ]);
    }
}
