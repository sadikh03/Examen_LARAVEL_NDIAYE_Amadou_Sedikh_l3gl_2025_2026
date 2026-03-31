<?php

namespace App\Http\Controllers;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isGestionnaire()) {
            return redirect()->route('gestionnaire.commandes.index');
        }

        return redirect()->route('client.catalogue');
    }
}
