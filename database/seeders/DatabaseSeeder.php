<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Burger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Gestionnaire par défaut
        User::create([
            'name'     => 'Admin ISI',
            'email'    => 'admin@isiburger.com',
            'password' => Hash::make('password'),
            'role'     => 'gestionnaire',
        ]);

        // Client de test
        User::create([
            'name'     => 'Client Test',
            'email'    => 'client@isiburger.com',
            'password' => Hash::make('password'),
            'role'     => 'client',
        ]);

        // Quelques burgers de démonstration
        $burgers = [
            ['nom' => 'Classic Burger',    'prix' => 3500, 'description' => 'Steak haché, salade, tomate, oignon', 'stock' => 20],
            ['nom' => 'Cheese Burger',     'prix' => 4000, 'description' => 'Double fromage fondu, cornichons', 'stock' => 15],
            ['nom' => 'Spicy Burger',      'prix' => 4200, 'description' => 'Sauce pimentée, jalapeños', 'stock' => 10],
            ['nom' => 'Crispy Chicken',    'prix' => 3800, 'description' => 'Poulet croustillant, mayo', 'stock' => 12],
            ['nom' => 'Veggie Burger',     'prix' => 3200, 'description' => 'Steak végétal, avocat', 'stock' => 8],
        ];

        foreach ($burgers as $b) {
            Burger::create($b);
        }
    }
}