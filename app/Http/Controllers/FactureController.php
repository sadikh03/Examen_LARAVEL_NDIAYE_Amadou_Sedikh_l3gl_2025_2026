<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureController extends Controller
{
    public function telecharger(Commande $commande)
    {
        // Sécurité : seul le client propriétaire ou le gestionnaire peut télécharger
        $user = auth()->user();

        if ($user->isClient() && $commande->user_id !== $user->id) {
            abort(403);
        }

        $commande->load(['user', 'burgers', 'paiement']);

        $pdf = Pdf::loadView('pdf.facture', compact('commande'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('facture-commande-' . $commande->id . '.pdf');
    }
}