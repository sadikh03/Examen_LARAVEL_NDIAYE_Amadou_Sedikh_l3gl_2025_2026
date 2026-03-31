@extends('layouts.app')
@section('title', 'Commande #' . $commande->id)

@section('content')
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('client.commandes.index') }}" class="text-gray-400 hover:text-gray-600">← Mes commandes</a>
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

        {{-- Articles --}}
        <div class="lg:col-span-2">
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
        </div>

        {{-- Résumé --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="font-bold text-gray-700 mb-3">Résumé</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Commande</span>
                        <span class="font-medium">#{{ $commande->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date</span>
                        <span>{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Statut</span>
                        <span class="font-semibold">{{ $commande->statut_label }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="font-bold">Total</span>
                        <span class="font-bold text-orange-600">
                            {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
            </div>

            {{-- Paiement --}}
            @if ($commande->paiement)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm">
                    <p class="font-semibold text-green-700">✅ Commande payée</p>
                    <p class="text-green-600 mt-1">
                        Le {{ $commande->paiement->date_paiement->format('d/m/Y à H:i') }}
                    </p>
                </div>
            @endif

            @if ($commande->paiement)
                <a href="{{ route('client.factures.telecharger', $commande) }}"
                    class="block text-center bg-gray-800 hover:bg-gray-900 text-white py-2.5 rounded-xl font-semibold text-sm transition mt-2">
                    Télécharger ma facture PDF
                </a>
            @endif

            <a href="{{ route('client.catalogue') }}"
                class="block text-center bg-orange-500 hover:bg-orange-600 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                Passer une nouvelle commande
            </a>
        </div>
    </div>
@endsection
