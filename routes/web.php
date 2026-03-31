<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BurgerController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\FactureController;
use Illuminate\Support\Facades\Route;

// Page d'accueil → catalogue public
Route::get('/', [BurgerController::class, 'catalogue'])->name('home');

Route::get('/check-config', function () {
    return [
        'host' => config('mail.mailers.smtp.host'),
        'username' => config('mail.mailers.smtp.username'),
        'env_host' => env('MAIL_HOST'),
    ];
});

// Routes authentifiées communes
Route::middleware('auth')->group(function () {

    // Redirection post-login selon le rôle
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== GESTIONNAIRE =====
    Route::middleware('gestionnaire')->prefix('gestionnaire')->name('gestionnaire.')->group(function () {
        // Burgers
        Route::resource('burgers', BurgerController::class);
        Route::patch('burgers/{burger}/archive', [BurgerController::class, 'toggleArchive'])->name('burgers.archive');

        // Commandes
        Route::get('commandes', [CommandeController::class, 'index'])->name('commandes.index');
        Route::get('commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
        Route::patch('commandes/{commande}/statut', [CommandeController::class, 'updateStatut'])->name('commandes.statut');
        Route::patch('commandes/{commande}/annuler', [CommandeController::class, 'annuler'])->name('commandes.annuler');

        // Paiements
        Route::post('commandes/{commande}/paiement', [PaiementController::class, 'store'])->name('paiements.store');

        // Statistiques
        Route::get('statistiques', [StatistiqueController::class, 'index'])->name('statistiques.index');

        // Facture PDF
        Route::get('commandes/{commande}/facture', [FactureController::class, 'telecharger'])->name('factures.telecharger');
    });

    // ===== CLIENT =====
    Route::middleware('client')->prefix('client')->name('client.')->group(function () {
        Route::get('catalogue', [BurgerController::class, 'catalogue'])->name('catalogue');
        Route::get('burgers/{burger}', [BurgerController::class, 'show'])->name('burgers.show');
        Route::post('commandes', [CommandeController::class, 'store'])->name('commandes.store');
        Route::get('commandes', [CommandeController::class, 'mesCommandes'])->name('commandes.index');
        Route::get('commandes/{commande}', [CommandeController::class, 'maCommande'])->name('commandes.show');

        // Facture PDF
        Route::get('commandes/{commande}/facture', [FactureController::class, 'telecharger'])->name('client.factures.telecharger');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';