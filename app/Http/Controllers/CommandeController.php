<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use App\Models\Commande;
use App\Jobs\EnvoyerFacturePdf;
use App\Mail\CommandeConfirmation;
use App\Mail\CommandePrete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CommandeController extends Controller
{
    // ===== GESTIONNAIRE =====

    // Liste toutes les commandes
    public function index(Request $request)
    {
        $query = Commande::with(['user', 'burgers', 'paiement'])
                         ->orderBy('created_at', 'desc');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $commandes = $query->paginate(15);
        $statuts   = Commande::STATUTS;

        return view('gestionnaire.commandes.index', compact('commandes', 'statuts'));
    }

    // Détail d'une commande (gestionnaire)
    public function show(Commande $commande)
    {
        $commande->load(['user', 'burgers', 'paiement']);
        $statuts = Commande::STATUTS;

        return view('gestionnaire.commandes.show', compact('commande', 'statuts'));
    }

    // Changer le statut d'une commande
    public function updateStatut(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,en_preparation,prete,payee,annulee',
        ]);

        $ancienStatut = $commande->statut;
        $commande->update(['statut' => $request->statut]);

        // Si la commande devient "prête" → envoyer email + PDF au client
        if ($request->statut === 'prete' && $ancienStatut !== 'prete') {
            try {
                Mail::to($commande->user->email)
                    ->send(new CommandePrete($commande));
            } catch (\Exception $e) {
                // Log l'erreur sans bloquer
                logger()->error('Erreur envoi mail commande prête : ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    // Annuler une commande
    public function annuler(Commande $commande)
    {
        if (in_array($commande->statut, ['payee'])) {
            return redirect()->back()->with('error', 'Impossible d\'annuler une commande déjà payée.');
        }

        // Restituer le stock
        foreach ($commande->burgers as $burger) {
            $burger->increment('stock', $burger->pivot->quantite);
        }

        $commande->update(['statut' => 'annulee']);

        return redirect()->back()->with('success', 'Commande annulée, stock restitué.');
    }

    // ===== CLIENT =====

    // Passer une commande
    public function store(Request $request)
    {
        $request->validate([
            'burgers'           => 'required|array|min:1',
            'burgers.*.id'      => 'required|exists:burgers,id',
            'burgers.*.quantite'=> 'required|integer|min:1|max:10',
        ]);

        DB::transaction(function () use ($request) {
            $total   = 0;
            $lignes  = [];

            foreach ($request->burgers as $item) {
                $burger = Burger::findOrFail($item['id']);

                if (!$burger->isDisponible()) {
                    throw new \Exception("Le burger \"{$burger->nom}\" n'est plus disponible.");
                }

                if ($burger->stock < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour \"{$burger->nom}\" (stock : {$burger->stock}).");
                }

                $sousTotal = $burger->prix * $item['quantite'];
                $total    += $sousTotal;

                $lignes[$burger->id] = [
                    'quantite'     => $item['quantite'],
                    'prix_unitaire'=> $burger->prix,
                ];

                // Décrémenter le stock
                $burger->decrement('stock', $item['quantite']);
            }

            // Créer la commande
            $commande = Commande::create([
                'user_id' => auth()->id(),
                'statut'  => 'en_attente',
                'total'   => $total,
            ]);

            $commande->burgers()->attach($lignes);

            // Email de confirmation au client
            try {
                Mail::to(auth()->user()->email)
                    ->send(new CommandeConfirmation($commande));
            } catch (\Exception $e) {
                logger()->error('Erreur mail confirmation : ' . $e->getMessage());
            }

            // Notifier le gestionnaire
            // (optionnel : même mail vers l'adresse admin)
            try {
                Mail::to(config('mail.from.address'))
                    ->send(new CommandeConfirmation($commande));
            } catch (\Exception $e) {
                logger()->error('Erreur mail notif gestionnaire : ' . $e->getMessage());
            }
        });

        return redirect()->route('client.commandes.index')
                         ->with('success', 'Commande passée avec succès !');
    }

    // Mes commandes (client)
    public function mesCommandes()
    {
        $commandes = Commande::with(['burgers', 'paiement'])
                             ->where('user_id', auth()->id())
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);

        return view('client.commandes', compact('commandes'));
    }

    // Détail d'une commande (client)
    public function maCommande(Commande $commande)
    {
        // Sécurité : le client ne peut voir que ses propres commandes
        if ($commande->user_id !== auth()->id()) {
            abort(403);
        }

        $commande->load(['burgers', 'paiement']);

        return view('client.commande-show', compact('commande'));
    }
}