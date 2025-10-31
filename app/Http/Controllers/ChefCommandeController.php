<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChefCommandeRequest;
use App\Models\Article;
use App\Models\ChefCommande;
use App\Models\ChefCommandeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChefCommandeController extends Controller
{
    public function index()
    {
        $chefCommandes = ChefCommande::paginate(10);
        return Inertia::render('ChefCommande/Index', [
            'chefCommandes' => $chefCommandes
        ]);
    }


    public function create()
    {
        return Inertia::modal('ChefCommande/CreateCommandeModal', [
            'articles' => Article::all(['id', 'designation'])
        ])->baseRoute('chef-commandes.index');
    }


    public function store(StoreChefCommandeRequest $request)
    {

            #FIX: it creates multiple chef commandes
            // $chefCommande = ChefCommande::create([
            //     'numero' => ChefCommande::genererNumero(),
            //     'note' => $request->note,
            //     'statut' => ChefCommande::STATUS_CREE,
            //     'user_id' => auth()->user()->id,
            // ]);


            // foreach ($request->articles as $article) {
            //     ChefCommandeItem::create([
            //         'chef_commande_id' => $chefCommande->id,
            //         'article_id' => $article['article_id'],
            //         'quantite_commandee' => $article['quantite_commandee'],
            //     ]);

            // }

        return Inertia::modal('ChefCommande/CreateCommandeModal', [
            'articles' => Article::all(['id', 'designation'])
        ])->baseRoute('chef-commandes.index');
    }
}
