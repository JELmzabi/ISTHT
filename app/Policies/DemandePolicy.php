<?php

namespace App\Policies;

use App\Enums\DemandeStatut;
use App\Models\Demande;
use App\Models\User;

class DemandePolicy
{

    public function list(User $user): bool
    {
        return $user->isAdmin() || $user->isMagasinier();
    }

    public function show(User $user, Demande $demande): bool
    {
        return $user->id == $demande->demandeur_id;
    }

    public function update(User $user, Demande $demande): bool
    {
        return $user->id == $demande->demandeur_id && $demande->statut == DemandeStatut::EN_ATTENTE->value;
    }

    public function cancel(User $user, Demande $demande): bool
    {
        return $user->id == $demande->demandeur_id && $demande->statut == DemandeStatut::EN_ATTENTE->value;
    }


    public function approve(User $user, Demande $demande): bool
    {
        return $demande->statut == DemandeStatut::EN_ATTENTE->value && $user->isAdmin();
    }

}
