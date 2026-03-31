<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function store(Request $request, Commande $commande)
    {
        // Vérifications
        if ($commande->statut !== 'prete') {
            return redirect()->back()
                             ->with('error', 'La commande doit être "Prête" avant d\'être payée.');
        }

        if ($commande->paiement) {
            return redirect()->back()
                             ->with('error', 'Cette commande a déjà été payée.');
        }

        $request->validate([
            'montant' => 'required|numeric|min:0',
        ]);

        // Enregistrer le paiement
        Paiement::create([
            'commande_id'   => $commande->id,
            'montant'       => $request->montant,
            'date_paiement' => now(),
        ]);

        // Passer la commande en "payée"
        $commande->update(['statut' => 'payee']);

        return redirect()->route('gestionnaire.commandes.show', $commande)
                         ->with('success', 'Paiement enregistré avec succès !');
    }
}