<x-app-layout>
    <div class="text-center py-20">
        <h1 class="text-4xl font-bold text-red-600">403 - Accès refusé</h1>
        <p class="mt-4">Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>
        <a href="{{ route('dashboard') }}" class="text-blue-500 underline mt-4 inline-block">Retour à l'accueil</a>
    </div>
</x-app-layout>
