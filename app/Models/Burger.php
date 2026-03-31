<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Burger extends Model
{
    protected $fillable = ['nom', 'description', 'prix', 'image', 'stock', 'archive'];

    protected $casts = ['archive' => 'boolean'];

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_burger')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }

    public function isDisponible(): bool
    {
        return !$this->archive && $this->stock > 0;
    }

    public function scopeDisponible($query)
    {
        return $query->where('archive', false)->where('stock', '>', 0);
    }
}