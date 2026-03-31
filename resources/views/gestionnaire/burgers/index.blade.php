@extends('layouts.app')
@section('title', 'Gestion des burgers')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Gestion des burgers</h1>
    <a href="{{ route('gestionnaire.burgers.create') }}"
       class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">
        + Nouveau burger
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">Image</th>
                <th class="px-4 py-3 text-left">Nom</th>
                <th class="px-4 py-3 text-left">Prix</th>
                <th class="px-4 py-3 text-left">Stock</th>
                <th class="px-4 py-3 text-left">Statut</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($burgers as $burger)
                <tr class="hover:bg-gray-50 transition {{ $burger->archive ? 'opacity-50' : '' }}">
                    <td class="px-4 py-3">
                        @if($burger->image)
                            <img src="{{ Storage::url($burger->image) }}"
                                 class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center text-2xl">🍔</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium">{{ $burger->nom }}</td>
                    <td class="px-4 py-3 text-orange-600 font-semibold">
                        {{ number_format($burger->prix, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-4 py-3">
                        <span class="{{ $burger->stock <= 2 ? 'text-red-500 font-bold' : 'text-gray-700' }}">
                            {{ $burger->stock }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($burger->archive)
                            <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs">Archivé</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">En ligne</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 flex gap-2 flex-wrap">
                        <a href="{{ route('gestionnaire.burgers.edit', $burger) }}"
                           class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-lg transition">
                            Modifier
                        </a>

                        <form method="POST" action="{{ route('gestionnaire.burgers.archive', $burger) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs bg-yellow-50 text-yellow-700 hover:bg-yellow-100 px-3 py-1 rounded-lg transition">
                                {{ $burger->archive ? 'Remettre en ligne' : 'Archiver' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('gestionnaire.burgers.destroy', $burger) }}"
                              onsubmit="return confirm('Supprimer ce burger ?')">
                            @csrf @method('DELETE')
                            <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg transition">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-400">Aucun burger.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $burgers->links() }}</div>
@endsection