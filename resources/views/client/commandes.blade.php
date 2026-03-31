@extends('layouts.app')
@section('title', 'Mes commandes')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Mes commandes</h1>

@if($commandes->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <div class="text-5xl mb-3">📋</div>
        <p>Vous n'avez pas encore de commande.</p>
        <a href="{{ route('client.catalogue') }}"
           class="mt-4 inline-block bg-orange-500 text-white px-6 py-2 rounded-xl font-semibold hover:bg-orange-600 transition">
            Voir le catalogue
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach($commandes as $commande)
            @php
                $couleurs = [
                    'en_attente'     => 'bg-yellow-100 text-yellow-800',
                    'en_preparation' => 'bg-blue-100 text-blue-800',
                    'prete'          => 'bg-green-100 text-green-800',
                    'payee'          => 'bg-gray-100 text-gray-700',
                    'annulee'        => 'bg-red-100 text-red-700',
                ];
            @endphp
            <div class="bg-white rounded-xl border shadow-sm p-4 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <p class="font-bold text-gray-800">Commande #{{ $commande->id }}</p>
                    <p class="text-sm text-gray-500">{{ $commande->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $commande->burgers->count() }} article(s)
                    </p>
                </div>

                <div class="text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $couleurs[$commande->statut] ?? 'bg-gray-100' }}">
                        {{ $commande->statut_label }}
                    </span>
                </div>

                <div class="text-right">
                    <p class="font-bold text-orange-600 text-lg">
                        {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                    </p>
                    <a href="{{ route('client.commandes.show', $commande) }}"
                       class="text-sm text-orange-500 hover:underline">
                        Voir le détail →
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $commandes->links() }}</div>
@endif
@endsection