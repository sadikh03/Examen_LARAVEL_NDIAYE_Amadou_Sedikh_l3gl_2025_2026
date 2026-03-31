<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BurgerController extends Controller
{
    // Catalogue public (client + visiteur)
    public function catalogue(Request $request)
    {
        $query = Burger::disponible();

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }

        $burgers = $query->orderBy('nom')->paginate(9);

        return view('client.catalogue', compact('burgers'));
    }

    // Détail d'un burger (client)
    public function show(Burger $burger)
    {
        if (request()->routeIs('gestionnaire.*')) {
            return view('gestionnaire.burgers.show', compact('burger'));
        }
        return view('client.burger-show', compact('burger'));
    }

    // Liste tous les burgers (gestionnaire)
    public function index()
    {
        $burgers = Burger::orderBy('created_at', 'desc')->paginate(10);
        return view('gestionnaire.burgers.index', compact('burgers'));
    }

    // Formulaire création
    public function create()
    {
        return view('gestionnaire.burgers.create');
    }

    // Enregistrer un burger
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100',
            'description' => 'nullable|string',
            'prix'        => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('burgers', 'public');
        }

        Burger::create($data);

        return redirect()->route('gestionnaire.burgers.index')
                         ->with('success', 'Burger créé avec succès !');
    }

    // Formulaire édition
    public function edit(Burger $burger)
    {
        return view('gestionnaire.burgers.edit', compact('burger'));
    }

    // Mettre à jour un burger
    public function update(Request $request, Burger $burger)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:100',
            'description' => 'nullable|string',
            'prix'        => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($burger->image) {
                Storage::disk('public')->delete($burger->image);
            }
            $data['image'] = $request->file('image')->store('burgers', 'public');
        }

        $burger->update($data);

        return redirect()->route('gestionnaire.burgers.index')
                         ->with('success', 'Burger mis à jour !');
    }

    // Supprimer un burger
    public function destroy(Burger $burger)
    {
        if ($burger->image) {
            Storage::disk('public')->delete($burger->image);
        }
        $burger->delete();

        return redirect()->route('gestionnaire.burgers.index')
                         ->with('success', 'Burger supprimé.');
    }

    // Archiver / désarchiver
    public function toggleArchive(Burger $burger)
    {
        $burger->update(['archive' => !$burger->archive]);

        $msg = $burger->archive ? 'Burger archivé.' : 'Burger remis en ligne.';
        return redirect()->back()->with('success', $msg);
    }
}