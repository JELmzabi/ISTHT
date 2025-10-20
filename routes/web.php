<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BonCommandeController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\BonReceptionController;
use App\Http\Controllers\CardexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\EntreeStockController;
use App\Http\Controllers\FicheTechniqueController;
use App\Http\Controllers\SortieStockController;
use App\Http\Controllers\MouvementStockController;
use App\Http\Controllers\UserManagementController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Route API pour les détails des commandes
    Route::get('api/commandes/{id}/details', [BonReceptionController::class, 'getCommandeDetails'])
        ->name('bon-receptions.commande-details');
    
    // Route pour les statistiques
    Route::get('api/bon-receptions/stats', [BonReceptionController::class, 'stats'])
        ->name('bon-receptions.stats');
    // Routes pour les articles
    Route::resource('articles', ArticleController::class);

    // Routes supplémentaires pour les autres fonctionnalités
    Route::prefix('articles')->group(function () {
        // Catégories
        Route::post('/categories/store', [ArticleController::class, 'storeCategorie'])->name('categories.store');
        Route::put('/categories/{categorie}', [ArticleController::class, 'updateCategorie'])->name('categories.update');
        Route::delete('/categories/{categorie}', [ArticleController::class, 'destroyCategorie'])->name('categories.destroy');
        
        // Catégories principales
        Route::post('/categorie-principales/store', [ArticleController::class, 'storeCategoriePrincipale'])->name('categorie-principales.store');
        Route::put('/categorie-principales/{categoriePrincipale}', [ArticleController::class, 'updateCategoriePrincipale'])->name('categorie-principales.update');
        Route::delete('/categorie-principales/{categoriePrincipale}', [ArticleController::class, 'destroyCategoriePrincipale'])->name('categorie-principales.destroy');
        
        // Natures de prestation
        Route::post('/nature-prestations/store', [ArticleController::class, 'storeNaturePrestation'])->name('nature-prestations.store');
        Route::put('/nature-prestations/{naturePrestation}', [ArticleController::class, 'updateNaturePrestation'])->name('nature-prestations.update');
        Route::delete('/nature-prestations/{naturePrestation}', [ArticleController::class, 'destroyNaturePrestation'])->name('nature-prestations.destroy');
    });

    // Module Achats
    Route::prefix('achats')->group(function () {
        // Bons de commande
        Route::resource('bon-commandes', BonCommandeController::class);
        Route::post('bon-commandes/{bonCommande}/statut', [BonCommandeController::class, 'updateStatut'])->name('bon-commandes.statut');
        Route::post('bon-commandes/fournisseurs/store', [BonCommandeController::class, 'storeFournisseur'])->name('bon-commandes.fournisseurs.store');
        Route::get('bon-commandes/{bonCommande}/pdf', [BonCommandeController::class, 'generatePdf'])->name('bon-commandes.pdf');
        Route::get('/bon-commandes/{bonCommande}/debug', [BonCommandeController::class, 'debugBonCommande'])->name('bon-commandes.debug');

      // Routes pour les bons de réception
Route::resource('bon-receptions', BonReceptionController::class);
Route::get('bon-receptions/create/{bonCommande}', [BonReceptionController::class, 'create'])
    ->name('bon-receptions.create-from-commande');
Route::get('bon-receptions/{bonReception}/details', [BonReceptionController::class, 'showDetails'])
    ->name('bon-receptions.show-details');
Route::get('bon-receptions/{bonReception}/download-pdf', [BonReceptionController::class, 'downloadPdf'])
    ->name('bon-receptions.download-pdf');
Route::get('bon-receptions/{bonReception}/download-bon-livraison', [BonReceptionController::class, 'downloadBonLivraison'])
    ->name('bon-receptions.download-bon-livraison');
Route::get('bon-receptions/{bonReception}/download-facture', [BonReceptionController::class, 'downloadFacture'])
    ->name('bon-receptions.download-facture');
Route::get('bon-receptions/commande-details/{id}', [BonReceptionController::class, 'getCommandeDetails'])
    ->name('bon-receptions.commande-details');

    

    Route::get('/debug-commande/{id}', function ($id) {
    $commande = \App\Models\BonCommande::with(['articles.article'])->find($id);
    
    if (!$commande) {
        return response()->json(['error' => 'Commande non trouvée'], 404);
    }

    $debugData = [
        'commande_id' => $commande->id,
        'reference' => $commande->reference,
        'articles_count' => $commande->articles->count(),
        'articles' => $commande->articles->map(function ($article) {
            return [
                'article_id' => $article->article_id,
                'designation' => $article->article->designation,
                'quantite_commandee' => $article->quantite_commandee,
                'prix_unitaire_ht' => $article->prix_unitaire_ht,
                'taux_tva' => $article->taux_tva,
                'montant_ht' => $article->montant_ht,
                'montant_tva' => $article->montant_tva,
                'montant_ttc' => $article->montant_ttc,
            ];
        })
    ];

    return response()->json($debugData);
});
        // Fournisseurs
        Route::get('fournisseurs', [FournisseurController::class, 'index'])->name('fournisseurs.index');
        Route::post('fournisseurs', [FournisseurController::class, 'store'])->name('fournisseurs.store');
        Route::get('fournisseurs/{fournisseur}', [FournisseurController::class, 'show'])->name('fournisseurs.show');
        Route::put('fournisseurs/{fournisseur}', [FournisseurController::class, 'update'])->name('fournisseurs.update');
        Route::delete('fournisseurs/{fournisseur}', [FournisseurController::class, 'destroy'])->name('fournisseurs.destroy');
        Route::patch('fournisseurs/{fournisseur}/toggle-statut', [FournisseurController::class, 'toggleStatut'])->name('fournisseurs.toggle-statut');
        Route::get('fournisseurs/stats', [FournisseurController::class, 'stats'])->name('fournisseurs.stats');
    });

    // Routes pour la Gestion du Stock
    Route::prefix('stock')->group(function () {
        // Routes des Entrées en Stock
        Route::get('/entrees', [EntreeStockController::class, 'index'])->name('entree-stocks.index');
        // Route::get('/entrees/create', [EntreeStockController::class, 'create'])->name('entree-stocks.create');
        // Route::post('/entrees', [EntreeStockController::class, 'store'])->name('entree-stocks.store');
        Route::get('/entrees/{entreeStock}', [EntreeStockController::class, 'show'])->name('entree-stocks.show');
        Route::get('/entrees/{entreeStock}/edit', [EntreeStockController::class, 'edit'])->name('entree-stocks.edit');
        Route::put('/entrees/{entreeStock}', [EntreeStockController::class, 'update'])->name('entree-stocks.update');
        Route::delete('/entrees/{entreeStock}', [EntreeStockController::class, 'destroy'])->name('entree-stocks.destroy');
        Route::get('/entrees/{entreeStock}/download-pdf', [EntreeStockController::class, 'downloadPdf'])->name('entree-stocks.download-pdf');
        // Routes pour la gestion des statuts des entrées de stock
Route::post('/entrees/{entreeStock}/valider', [EntreeStockController::class, 'valider'])
    ->name('entree-stocks.valider');
Route::post('/entrees/{entreeStock}/annuler', [EntreeStockController::class, 'annuler'])
    ->name('entree-stocks.annuler');
        // Routes des Sorties de Stock
        Route::get('/sorties', [SortieStockController::class, 'index'])->name('sortie-stocks.index');
        Route::get('/sorties/create', [SortieStockController::class, 'create'])->name('sortie-stocks.create');
        Route::post('/sorties', [SortieStockController::class, 'store'])->name('sortie-stocks.store');
        Route::get('/sorties/{sortieStock}', [SortieStockController::class, 'show'])->name('sortie-stocks.show');
        Route::get('/sorties/{sortieStock}/edit', [SortieStockController::class, 'edit'])->name('sortie-stocks.edit');
        Route::put('/sorties/{sortieStock}', [SortieStockController::class, 'update'])->name('sortie-stocks.update');
        Route::delete('/sorties/{sortieStock}', [SortieStockController::class, 'destroy'])->name('sortie-stocks.destroy');
        Route::get('/sorties/{sortieStock}/download-pdf', [SortieStockController::class, 'downloadPdf'])->name('sortie-stocks.download-pdf');

        // Routes des Mouvements de Stock
        Route::get('/mouvements', [MouvementStockController::class, 'index'])->name('mouvement-stocks.index');
        Route::get('/mouvements/export', [MouvementStockController::class, 'export'])->name('mouvement-stocks.export');
        Route::get('/mouvements/stats', [MouvementStockController::class, 'stats'])->name('mouvement-stocks.stats');
        Route::get('/mouvements/article/{article}', [MouvementStockController::class, 'byArticle'])->name('mouvement-stocks.by-article');
    });



    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');


    Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
    Route::get('/mes-demandes', [DemandeController::class, 'mesDemandes'])->name('demandes.mes-demandes');
    Route::get('/demandes/create', [DemandeController::class, 'create'])->name('demandes.create');
    Route::post('/demandes', [DemandeController::class, 'store'])->name('demandes.store');
    Route::get('/demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
    Route::get('/demandes/{demande}/edit', [DemandeController::class, 'edit'])->name('demandes.edit');
    Route::put('/demandes/{demande}', [DemandeController::class, 'update'])->name('demandes.update');
    Route::delete('/demandes/{demande}', [DemandeController::class, 'destroy'])->name('demandes.destroy');
    
    Route::delete('/demandes/{demande}/annuler', [DemandeController::class, 'cancel'])->name('demandes.cancel');
    Route::get('/demandes/{demande}/approve', [DemandeController::class, 'showApprove'])->name('demandes.show.approve');
    Route::put('/demandes/{demande}/approve', [DemandeController::class, 'approve'])->name('demandes.approve');
    Route::put('/demandes/{demande}/reject', [DemandeController::class, 'reject'])->name('demandes.reject');
    

    ##### Fiches Techniques ####
    Route::get('/fiches-techniques/collectivite', [FicheTechniqueController::class, 'collectivite'])->name('fiches-techniques.collectivite');
    Route::get('/fiches-techniques/pedagogique', [FicheTechniqueController::class, 'pedagogique'])->name('fiches-techniques.pedagogique');
    Route::get('/fiches-techniques/create', [FicheTechniqueController::class, 'create'])->name('fiches-techniques.create');
    Route::post('/fiches-techniques', [FicheTechniqueController::class, 'store'])->name('fiches-techniques.store');
    Route::get('/fiches-techniques/{fiche}', [FicheTechniqueController::class, 'show'])->name('fiches-techniques.show');
    Route::get('/fiches-techniques/{fiche}/edit', [FicheTechniqueController::class, 'edit'])->name('fiches-techniques.edit');
    Route::put('/fiches-techniques/{fiche}', [FicheTechniqueController::class, 'update'])->name('fiches-techniques.update');
    Route::delete('/fiches-techniques/{fiche}', [FicheTechniqueController::class, 'destroy'])->name('fiches-techniques.destroy');
    Route::get('/fiches-techniques/{fiche}/export', [FicheTechniqueController::class, 'export'])->name('fiches-techniques.export');

    ##### Sortie Stock #####
    Route::get('/sorties/{sortieStock}/approve', [SortieStockController::class, 'showApprove'])->name('sortie-stocks.show.approve');
    Route::put('/sorties/{sortieStock}/approve', [SortieStockController::class, 'approve'])->name('sortie-stocks.approve');
    Route::put('/sorties/{sortieStock}/reject', [SortieStockController::class, 'reject'])->name('sortie-stocks.reject');
    Route::put('/sorties/{sortieStock}/livrer', [SortieStockController::class, 'livrer'])->name('sortie-stocks.livrer');
    
    ##### Exports #####
    Route::get('fournisseurs/export', [FournisseurController::class, 'export'])->name('fournisseurs.export');

    Route::get('bon-commandes/export', [BonCommandeController::class, 'export'])->name('bon-commandes.export');

    Route::get('entree-stocks/export/create', [EntreeStockController::class, 'createExport'])->name('entree-stocks.export.create');
    Route::get('entree-stocks/export', [EntreeStockController::class, 'export'])->name('entree-stocks.export');

    Route::get('cardex/{article}', [CardexController::class, 'index'])->name('cardex.index');
    
});

require __DIR__.'/auth.php';