@extends('layouts.app')
@section('title', 'Commande #' . $commande->id)

@section('content')
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('gestionnaire.commandes.index') }}" class="text-gray-400 hover:text-gray-600">← Retour</a>
        <h1 class="text-2xl font-bold text-gray-800">Commande #{{ $commande->id }}</h1>
        @php
            $couleurs = [
                'en_attente' => 'bg-yellow-100 text-yellow-800',
                'en_preparation' => 'bg-blue-100 text-blue-800',
                'prete' => 'bg-green-100 text-green-800',
                'payee' => 'bg-gray-100 text-gray-700',
                'annulee' => 'bg-red-100 text-red-700',
            ];
        @endphp
        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $couleurs[$commande->statut] ?? '' }}">
            {{ $commande->statut_label }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Détail articles --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="font-bold text-gray-700 mb-4">Articles commandés</h2>
                <table class="w-full text-sm">
                    <thead class="text-gray-400 text-xs uppercase border-b">
                        <tr>
                            <th class="pb-2 text-left">Burger</th>
                            <th class="pb-2 text-center">Qté</th>
                            <th class="pb-2 text-right">Prix unitaire</th>
                            <th class="pb-2 text-right">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($commande->burgers as $burger)
                            <tr>
                                <td class="py-3 font-medium">{{ $burger->nom }}</td>
                                <td class="py-3 text-center">{{ $burger->pivot->quantite }}</td>
                                <td class="py-3 text-right text-gray-600">
                                    {{ number_format($burger->pivot->prix_unitaire, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="py-3 text-right font-semibold text-orange-600">
                                    {{ number_format($burger->pivot->prix_unitaire * $burger->pivot->quantite, 0, ',', ' ') }}
                                    FCFA
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t">
                            <td colspan="3" class="pt-3 font-bold text-right">Total</td>
                            <td class="pt-3 text-right font-bold text-orange-600 text-lg">
                                {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Info client --}}
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="font-bold text-gray-700 mb-3">Client</h2>
                <p class="text-gray-800 font-medium">{{ $commande->user->name }}</p>
                <p class="text-gray-500 text-sm">{{ $commande->user->email }}</p>
                <p class="text-gray-400 text-xs mt-1">Commande passée le {{ $commande->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="space-y-4">

            {{-- Changer statut --}}
            @if (!in_array($commande->statut, ['payee', 'annulee']))
                <div class="bg-white rounded-xl border shadow-sm p-5">
                    <h2 class="font-bold text-gray-700 mb-3">Changer le statut</h2>
                    <form method="POST" action="{{ route('gestionnaire.commandes.statut', $commande) }}">
                        @csrf @method('PATCH')
                        <select name="statut"
                            class="w-full border rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-orange-400">
                            @foreach ($statuts as $key => $label)
                                <option value="{{ $key }}" {{ $commande->statut === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg text-sm font-semibold transition">
                            Mettre à jour
                        </button>
                    </form>
                </div>
            @endif

            {{-- Paiement --}}
            @if ($commande->estPayable())
                <div class="bg-white rounded-xl border shadow-sm p-5">
                    <h2 class="font-bold text-gray-700 mb-3">Enregistrer le paiement</h2>
                    <form method="POST" action="{{ route('gestionnaire.paiements.store', $commande) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="text-xs text-gray-500 block mb-1">Montant reçu (FCFA)</label>
                            <input type="number" name="montant" value="{{ $commande->total }}"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                        </div>
                        <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm font-semibold transition">
                            Confirmer le paiement
                        </button>
                    </form>
                </div>
            @endif

            {{-- Paiement déjà effectué --}}
            @if ($commande->paiement)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm">
                    <p class="font-semibold text-green-700">✅ Payée</p>
                    <p class="text-green-600 mt-1">
                        {{ number_format($commande->paiement->montant, 0, ',', ' ') }} FCFA
                        le {{ $commande->paiement->date_paiement->format('d/m/Y à H:i') }}
                    </p>
                </div>
            @endif

            @if ($commande->paiement)
                <a href="{{ route('gestionnaire.factures.telecharger', $commande) }}"
                    class="block text-center bg-gray-800 hover:bg-gray-900 text-white py-2 rounded-lg text-sm font-semibold transition">
                    Télécharger la facture PDF
                </a>
            @endif

            {{-- Annuler --}}
            @if (!in_array($commande->statut, ['payee', 'annulee']))
                <form method="POST" action="{{ route('gestionnaire.commandes.annuler', $commande) }}"
                    onsubmit="return confirm('Confirmer l\'annulation ?')">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 py-2 rounded-lg text-sm font-semibold transition">
                        Annuler la commande
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
