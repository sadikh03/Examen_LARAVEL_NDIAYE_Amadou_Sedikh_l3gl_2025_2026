@extends('layouts.app')
@section('title', 'Modifier ' . $burger->nom)

@section('content')
<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('gestionnaire.burgers.index') }}" class="text-gray-400 hover:text-gray-600">← Retour</a>
        <h1 class="text-2xl font-bold text-gray-800">Modifier : {{ $burger->nom }}</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <form method="POST" action="{{ route('gestionnaire.burgers.update', $burger) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                <input type="text" name="nom" value="{{ old('nom', $burger->nom) }}" required
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('description', $burger->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix (FCFA) *</label>
                    <input type="number" name="prix" value="{{ old('prix', $burger->prix) }}" min="0" required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                    <input type="number" name="stock" value="{{ old('stock', $burger->stock) }}" min="0" required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nouvelle image</label>
                @if($burger->image)
                    <img src="{{ Storage::url($burger->image) }}"
                         class="w-24 h-24 object-cover rounded-lg mb-2">
                @endif
                <input type="file" name="image" accept="image/*"
                       class="w-full border rounded-lg px-3 py-2 text-sm text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100">
            </div>

            <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2.5 rounded-xl font-semibold transition">
                Enregistrer les modifications
            </button>
        </form>
    </div>
</div>
@endsection