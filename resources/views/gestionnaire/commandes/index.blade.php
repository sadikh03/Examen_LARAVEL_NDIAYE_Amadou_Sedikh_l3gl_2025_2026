@extends('layouts.app')
@section('title', 'Gestion des commandes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Toutes les commandes</h1>
</div>

{{-- Filtre statut --}}
<form method="GET" class="mb-4 flex gap-2 flex-wrap">
    @foreach([''=>'Toutes'] + $statuts as $key => $label)
        <a href="{{ request()->fullUrlWithQuery(['statut' => $key]) }}"
           class="px-4 py-1.5 rounded-full text-sm font-medium border transition
                  {{ request('statut') == $key ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-600 border-gray-300 hover:border-orange-400' }}">
            {{ $label }}
        </a>
    @endforeach
</form>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">#</th>
                <th class="px-4 py-3 text-left">Client</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Articles</th>
                <th class="px-4 py-3 text-left">Total</th>
                <th class="px-4 py-3 text-left">Statut</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($commandes as $commande)
                @php
                    $couleurs = [
                        'en_attente'     => 'bg-yellow-100 text-yellow-800',
                        'en_preparation' => 'bg-blue-100 text-blue-800',
                        'prete'          => 'bg-green-100 text-green-800',
                        'payee'          => 'bg-gray-100 text-gray-700',
                        'annulee'        => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium">#{{ $commande->id }}</td>
                    <td class="px-4 py-3">{{ $commande->user->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">{{ $commande->burgers->count() }}</td>
                    <td class="px-4 py-3 font-semibold text-orange-600">
                        {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $couleurs[$commande->statut] ?? '' }}">
                            {{ $commande->statut_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('gestionnaire.commandes.show', $commande) }}"
                           class="text-orange-500 hover:underline text-xs font-medium">
                            Gérer →
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-gray-400">Aucune commande.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $commandes->links() }}</div>
@endsection