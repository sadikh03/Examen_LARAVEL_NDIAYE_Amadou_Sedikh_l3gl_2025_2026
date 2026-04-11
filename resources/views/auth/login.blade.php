<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — ISI BURGER</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-orange-50 flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- LOGO --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-500 rounded-2xl shadow-lg mb-4">
                <span style="font-size:40px">🍔</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">ISI BURGER</h1>
            <p class="text-gray-500 mt-1">Connectez-vous pour continuer</p>
        </div>

        {{-- CARTE LOGIN --}}
        <div class="bg-white rounded-2xl shadow-lg border border-orange-100 p-8">

            {{-- Erreurs --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-5 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Adresse email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="exemple@email.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition @error('email') border-red-400 @enderror">
                </div>

                {{-- Mot de passe --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mot de passe
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-xs text-orange-500 hover:text-orange-600 hover:underline">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition pr-12 @error('password') border-red-400 @enderror">
                        {{-- Toggle password visibility --}}
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm">
                            <span id="eye-icon">👁️</span>
                        </button>
                    </div>
                </div>

                {{-- Se souvenir de moi --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember_me" name="remember"
                        class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400">
                    <label for="remember_me" class="text-sm text-gray-600">
                        Se souvenir de moi
                    </label>
                </div>

                {{-- Bouton connexion --}}
                <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold py-3 rounded-xl transition shadow-sm">
                    Se connecter
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-xs text-gray-400">ou</span>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            {{-- Lien inscription --}}
            <p class="text-center text-sm text-gray-500">
                Pas encore de compte ?
                <a href="{{ route('register') }}"
                    class="text-orange-500 hover:text-orange-600 font-semibold hover:underline">
                    S'inscrire
                </a>
            </p>
        </div>

        {{-- Comptes de test --}}
        <div class="mt-4 bg-white rounded-2xl border border-orange-100 shadow-sm p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Comptes de démonstration</p>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="remplir('admin@isiburger.com', 'password')"
                    class="text-left bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-xl p-3 transition">
                    <p class="text-xs font-bold text-orange-700">Gestionnaire</p>
                    <p class="text-xs text-gray-500 mt-0.5">admin@isiburger.com</p>
                </button>
                <button onclick="remplir('client@isiburger.com', 'password')"
                    class="text-left bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-xl p-3 transition">
                    <p class="text-xs font-bold text-teal-700">Client</p>
                    <p class="text-xs text-gray-500 mt-0.5">client@isiburger.com</p>
                </button>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} ISI BURGER — Tous droits réservés
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈';
            } else {
                input.type = 'password';
                icon.textContent = '👁️';
            }
        }

        function remplir(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>

</body>

</html>
