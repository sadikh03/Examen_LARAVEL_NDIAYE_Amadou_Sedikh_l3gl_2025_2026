@extends('layouts.app')

@section('title', 'Catalogue — ISI BURGER')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">🍔 Notre Catalogue</h1>
</div>

{{-- FILTRES --}}
<form method="GET" action="{{ auth()->check() && auth()->user()->isClient() ? route('client.catalogue') : route('home') }}"
      class="bg-white rounded-xl shadow-sm border p-4 mb-6 flex flex-wrap gap-3 items-end">

    <div>
        <label class="block text-xs text-gray-500 mb-1">Recherche</label>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Nom du burger..."
               class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 w-48">
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Prix min (FCFA)</label>
        <input type="number" name="prix_min" value="{{ request('prix_min') }}"
               class="border rounded-lg px-3 py-2 text-sm w-32 focus:outline-none focus:ring-2 focus:ring-orange-400">
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Prix max (FCFA)</label>
        <input type="number" name="prix_max" value="{{ request('prix_max') }}"
               class="border rounded-lg px-3 py-2 text-sm w-32 focus:outline-none focus:ring-2 focus:ring-orange-400">
    </div>

    <button type="submit"
            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-semibold transition">
        Filtrer
    </button>

    @if(request()->hasAny(['search', 'prix_min', 'prix_max']))
        <a href="{{ route('home') }}"
           class="text-sm text-gray-500 hover:text-gray-700 underline self-end pb-2">
            Réinitialiser
        </a>
    @endif
</form>

{{-- GRILLE BURGERS --}}
@if($burgers->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <div class="text-5xl mb-3">🍔</div>
        <p class="text-lg">Aucun burger disponible pour le moment.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($burgers as $burger)
            <div class="bg-white rounded-2xl shadow-sm border hover:shadow-md transition overflow-hidden flex flex-col">

                {{-- Image --}}
                <div class="h-48 bg-orange-50 flex items-center justify-center overflow-hidden">
                    @if($burger->image)
                        <img src="{{ Storage::url($burger->image) }}"
                             alt="{{ $burger->nom }}"
                             class="h-full w-full object-cover">
                    @else
                        <span class="text-6xl">🍔</span>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="p-4 flex flex-col flex-1">
                    <h2 class="font-bold text-gray-800 text-lg">{{ $burger->nom }}</h2>
                    <p class="text-gray-500 text-sm mt-1 flex-1">
                        {{ Str::limit($burger->description, 80) }}
                    </p>

                    <div class="flex items-center justify-between mt-4">
                        <span class="text-orange-600 font-bold text-lg">
                            {{ number_format($burger->prix, 0, ',', ' ') }} FCFA
                        </span>
                        <span class="text-xs text-gray-400">Stock : {{ $burger->stock }}</span>
                    </div>

                    {{-- Bouton commander --}}
                    @auth
                        @if(auth()->user()->isClient())
                            <button onclick="ajouterAuPanier({{ $burger->id }}, '{{ $burger->nom }}', {{ $burger->prix }})"
                                    class="mt-3 bg-orange-500 hover:bg-orange-600 text-white w-full py-2 rounded-xl text-sm font-semibold transition">
                                + Ajouter au panier
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="mt-3 block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 w-full py-2 rounded-xl text-sm font-semibold transition">
                            Connectez-vous pour commander
                        </a>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $burgers->links() }}</div>
@endif

{{-- PANIER FLOTTANT --}}
@auth
    @if(auth()->user()->isClient())
    <div id="panier" class="hidden fixed bottom-6 right-6 bg-white border shadow-xl rounded-2xl w-80 z-50">
        <div class="bg-orange-500 text-white px-4 py-3 rounded-t-2xl flex justify-between items-center">
            <span class="font-bold">🛒 Mon panier</span>
            <button onclick="togglePanier()" class="text-white text-xl leading-none">&times;</button>
        </div>

        <div id="panier-items" class="px-4 py-3 max-h-60 overflow-y-auto space-y-2 text-sm text-gray-700">
            <p class="text-gray-400 text-center py-4" id="panier-vide">Panier vide</p>
        </div>

        <div class="px-4 py-3 border-t flex justify-between font-bold text-gray-800">
            <span>Total</span>
            <span id="panier-total">0 FCFA</span>
        </div>

        <div class="px-4 pb-4">
            <form id="form-commande" method="POST" action="{{ route('client.commandes.store') }}">
                @csrf
                <div id="panier-hidden-inputs"></div>
                <button type="submit" id="btn-commander"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-xl font-semibold text-sm transition disabled:opacity-50"
                        disabled>
                    Passer la commande
                </button>
            </form>
        </div>
    </div>

    {{-- Bouton flottant panier --}}
    <button onclick="togglePanier()"
            class="fixed bottom-6 right-6 bg-orange-500 hover:bg-orange-600 text-white w-14 h-14 rounded-full shadow-lg text-2xl flex items-center justify-center z-40 transition"
            id="btn-panier-float">
        🛒 <span id="panier-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
    </button>
    @endif
@endauth

@endsection

@push('scripts')
<script>
let panier = {};

function ajouterAuPanier(id, nom, prix) {
    if (panier[id]) {
        panier[id].quantite++;
    } else {
        panier[id] = { nom, prix, quantite: 1 };
    }
    renderPanier();
    document.getElementById('panier').classList.remove('hidden');
    document.getElementById('btn-panier-float').classList.add('hidden');
}

function renderPanier() {
    const container = document.getElementById('panier-items');
    const hidden    = document.getElementById('panier-hidden-inputs');
    const totalEl   = document.getElementById('panier-total');
    const countEl   = document.getElementById('panier-count');
    const btnCmd    = document.getElementById('btn-commander');
    const vide      = document.getElementById('panier-vide');

    container.innerHTML = '';
    hidden.innerHTML    = '';

    let total = 0;
    let count = 0;
    let i     = 0;

    for (const [id, item] of Object.entries(panier)) {
        total += item.prix * item.quantite;
        count += item.quantite;

        container.innerHTML += `
            <div class="flex justify-between items-center">
                <span>${item.nom} x${item.quantite}</span>
                <div class="flex items-center gap-2">
                    <span class="text-orange-600 font-semibold">${(item.prix * item.quantite).toLocaleString()} FCFA</span>
                    <button onclick="retirerDuPanier(${id})" class="text-red-400 hover:text-red-600 text-lg leading-none">&times;</button>
                </div>
            </div>`;

        hidden.innerHTML += `
            <input type="hidden" name="burgers[${i}][id]" value="${id}">
            <input type="hidden" name="burgers[${i}][quantite]" value="${item.quantite}">`;
        i++;
    }

    if (Object.keys(panier).length === 0) {
        container.innerHTML = '<p class="text-gray-400 text-center py-4">Panier vide</p>';
        btnCmd.disabled = true;
    } else {
        btnCmd.disabled = false;
    }

    totalEl.textContent  = total.toLocaleString() + ' FCFA';
    countEl.textContent  = count;
    countEl.classList.toggle('hidden', count === 0);
}

function retirerDuPanier(id) {
    delete panier[id];
    renderPanier();
}

function togglePanier() {
    const p    = document.getElementById('panier');
    const btn  = document.getElementById('btn-panier-float');
    const show = p.classList.contains('hidden');
    p.classList.toggle('hidden', !show);
    btn.classList.toggle('hidden', show);
}
</script>
@endpush