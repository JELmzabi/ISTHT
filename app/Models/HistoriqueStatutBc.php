<?php
// app/Models/HistoriqueStatutBc.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueStatutBc extends Model
{
    use HasFactory;

    protected $table = 'historique_statut_bcs';

    protected $fillable = [
        'bon_commande_id',
        'ancien_statut',
        'nouveau_statut',
        'raison',
        'changed_by',
    ];

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}