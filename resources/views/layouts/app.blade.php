<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ISI BURGER')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen font-sans">

    {{-- NAVBAR --}}
    <nav class="bg-orange-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

            <a href="{{ route('home') }}" class="text-2xl font-bold tracking-tight flex items-center gap-2">
                🍔 ISI BURGER
            </a>

            <div class="flex items-center gap-4 text-sm">
                @auth
                    @if (auth()->user()->isGestionnaire())
                        <a href="{{ route('gestionnaire.commandes.index') }}" class="hover:underline">Commandes</a>
                        <a href="{{ route('gestionnaire.burgers.index') }}" class="hover:underline">Burgers</a>
                        <a href="{{ route('gestionnaire.statistiques.index') }}" class="hover:underline">Statistiques</a>
                    @else
                        <a href="{{ route('client.catalogue') }}" class="hover:underline">Catalogue</a>
                        <a href="{{ route('client.commandes.index') }}" class="hover:underline">Mes commandes</a>
                    @endif

                    <span class="opacity-75">|</span>
                    <span class="opacity-90">{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="bg-white text-orange-600 px-3 py-1 rounded-full text-xs font-semibold hover:bg-orange-50 transition">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-white text-orange-600 px-4 py-1.5 rounded-full text-sm font-semibold hover:bg-orange-50 transition">
                        Connexion
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    <div class="max-w-7xl mx-auto px-4 mt-4">
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
                <span>❌</span> {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- CONTENU --}}
    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="text-center text-gray-400 text-xs py-6 mt-10 border-t">
        © {{ date('Y') }} ISI BURGER — Tous droits réservés
    </footer>

    @stack('scripts')
</body>

</html>
