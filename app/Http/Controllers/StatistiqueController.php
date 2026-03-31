<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Burger;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index()
    {
        // Commandes en cours aujourd'hui
        $commandesEnCours = Commande::whereDate('created_at', today())
            ->whereNotIn('statut', ['payee', 'annulee'])
            ->count();

        // Commandes validées (payées) aujourd'hui
        $commandesValidees = Commande::whereDate('created_at', today())
            ->where('statut', 'payee')
            ->count();

        // Recettes journalières
        $recettesJour = Paiement::whereDate('date_paiement', today())
            ->sum('montant');

        // Commandes par mois (12 derniers mois) pour Chart.js
        $commandesParMois = Commande::select(
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('annee', 'mois')
            ->orderBy('annee')
            ->orderBy('mois')
            ->get();

        // Préparer labels et data pour le graphique commandes
        $labelsCommandes = [];
        $dataCommandes   = [];
        $moisFr = ['', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun',
                        'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];

        foreach ($commandesParMois as $item) {
            $labelsCommandes[] = $moisFr[$item->mois] . ' ' . $item->annee;
            $dataCommandes[]   = $item->total;
        }

        // Burgers par stock (top 5)
        $topBurgers = Burger::where('archive', false)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        $labelsBurgers = $topBurgers->pluck('nom')->toArray();
        $dataBurgers   = $topBurgers->pluck('stock')->toArray();

        return view('gestionnaire.statistiques', compact(
            'commandesEnCours',
            'commandesValidees',
            'recettesJour',
            'labelsCommandes',
            'dataCommandes',
            'labelsBurgers',
            'dataBurgers'
        ));
    }
}