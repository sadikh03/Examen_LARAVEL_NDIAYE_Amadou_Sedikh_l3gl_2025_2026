<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — ISI BURGER</title>
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
            <p class="text-gray-500 mt-1">Créez votre compte client</p>
        </div>

        {{-- CARTE REGISTER --}}
        <div class="bg-white rounded-2xl shadow-lg border border-orange-100 p-8">

            {{-- Erreurs --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Nom --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nom complet
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        placeholder="Prénom Nom"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Adresse email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        placeholder="exemple@email.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mot de passe --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required placeholder="Min. 8 caractères"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition pr-12 @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword('password', 'eye1')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <span id="eye1">👁️</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmation mot de passe --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmer le mot de passe
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            placeholder="Répétez le mot de passe"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition pr-12">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye2')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <span id="eye2">👁️</span>
                        </button>
                    </div>
                </div>

                {{-- Indicateur force mot de passe --}}
                <div id="password-strength" class="hidden">
                    <div class="flex gap-1 mb-1">
                        <div id="bar1" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all"></div>
                        <div id="bar2" class="h-1.5 flex-1 rounded-full bg-gray-200 transition-all"></div>
                        <div id="bar3" class="h-1.5 rounded-full bg-gray-200 transition-all" style="flex:2"></div>
                    </div>
                    <p id="strength-label" class="text-xs text-gray-400"></p>
                </div>

                {{-- CGU --}}
                <div class="flex items-start gap-2">
                    <input type="checkbox" id="cgu" required
                        class="w-4 h-4 mt-0.5 rounded border-gray-300 text-orange-500 focus:ring-orange-400">
                    <label for="cgu" class="text-sm text-gray-600">
                        J'accepte les
                        <span class="text-orange-500 font-medium">conditions d'utilisation</span>
                        d'ISI BURGER
                    </label>
                </div>

                {{-- Bouton inscription --}}
                <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold py-3 rounded-xl transition shadow-sm">
                    Créer mon compte
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-xs text-gray-400">déjà inscrit ?</span>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            {{-- Lien login --}}
            <a href="{{ route('login') }}"
                class="block text-center w-full border border-orange-300 text-orange-500 hover:bg-orange-50 font-semibold py-3 rounded-xl transition text-sm">
                Se connecter
            </a>
        </div>

        {{-- Info rôle --}}
        <div class="mt-4 bg-white rounded-2xl border border-orange-100 shadow-sm p-4 flex items-center gap-3">
            <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center text-base flex-shrink-0">
                ℹ️
            </div>
            <p class="text-xs text-gray-500">
                Tout nouveau compte est créé en tant que <span class="font-semibold text-gray-700">client</span>.
                Les accès gestionnaire sont attribués par l'administration.
            </p>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} ISI BURGER — Tous droits réservés
        </p>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈';
            } else {
                input.type = 'password';
                icon.textContent = '👁️';
            }
        }

        // Indicateur de force du mot de passe
        document.getElementById('password').addEventListener('input', function() {
            const val = this.value;
            const container = document.getElementById('password-strength');
            const bar1 = document.getElementById('bar1');
            const bar2 = document.getElementById('bar2');
            const bar3 = document.getElementById('bar3');
            const label = document.getElementById('strength-label');

            if (val.length === 0) {
                container.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            bar1.className = 'h-1.5 flex-1 rounded-full transition-all';
            bar2.className = 'h-1.5 flex-1 rounded-full transition-all';
            bar3.className = 'h-1.5 rounded-full transition-all';
            bar3.style.flex = '2';

            if (score <= 1) {
                bar1.classList.add('bg-red-400');
                bar2.classList.add('bg-gray-200');
                bar3.classList.add('bg-gray-200');
                label.textContent = 'Mot de passe faible';
                label.className = 'text-xs text-red-500';
            } else if (score === 2) {
                bar1.classList.add('bg-yellow-400');
                bar2.classList.add('bg-yellow-400');
                bar3.classList.add('bg-gray-200');
                label.textContent = 'Mot de passe moyen';
                label.className = 'text-xs text-yellow-500';
            } else {
                bar1.classList.add('bg-green-400');
                bar2.classList.add('bg-green-400');
                bar3.classList.add('bg-green-400');
                label.textContent = 'Mot de passe fort';
                label.className = 'text-xs text-green-500';
            }
        });
    </script>

</body>

</html>
