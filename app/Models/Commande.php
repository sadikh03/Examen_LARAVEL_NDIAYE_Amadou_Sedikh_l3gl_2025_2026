<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = ['user_id', 'statut', 'total'];

    const STATUTS = [
        'en_attente'    => 'En attente',
        'en_preparation'=> 'En préparation',
        'prete'         => 'Prête',
        'payee'         => 'Payée',
        'annulee'       => 'Annulée',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function burgers()
    {
        return $this->belongsToMany(Burger::class, 'commande_burger')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }

    public function getStatutLabelAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    public function estPayable(): bool
    {
        return $this->statut === 'prete' && !$this->paiement;
    }
}