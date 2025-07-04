<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 px-6">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Gestion de documents</h1>
        <p class="text-gray-600 mb-6 text-center max-w-xl">
            Une application interne pour gérer, stocker et organiser les documents de votre entreprise de manière sécurisée et efficace.
        </p>

        @auth
            <a href="{{ route('dashboard') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                Accéder au tableau de bord
            </a>
        @else
            <div class="flex space-x-4">
                <a href="{{ route('login') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                    Connexion
                </a>

                <a href="{{ route('register') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-lg transition">
                    Inscription
                </a>
            </div>
        @endauth
    </div>
</x-guest-layout>
