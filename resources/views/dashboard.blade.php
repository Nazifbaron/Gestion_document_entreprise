<x-app-layout>
    <x-slot name="header">
        <div class=" flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                Tableau de bord
            </h2>
            
            @php
                $unreadCount = auth()->user()->unreadNotifications->count();
            @endphp

            <div class="relative">
                <a href="{{ route('notifications.index') }}" class="relative inline-block">
                    🔔
                    @if ($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full">
                        {{ $unreadCount }}
                    </span>

                    @endif
                </a>
            </div>

        </div>

    </x-slot>

    <div class="py-10 px-6 space-y-6">
        <p class="text-lg">Bienvenue, <strong>{{ $user->name }}</strong> (rôle : <strong>{{ $user->getRoleNames()->first() }}</strong>)</p>

        {{-- ADMIN --}}
        @role('admin')
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-2">Espace administrateur</h3>
                <ul class="list-disc ml-6 text-blue-600">
                    <li><a href="/admin/utilisateurs">Gérer les utilisateurs</a></li>
                    <li><a href="/admin/roles">Gérer les rôles  </a></li>
                    <li><a href="/admin/permissions">Gérer les permissions</a></li>
                    <li><a href="/admin/documents">Voir tous les documents</a></li>
                    <li><a href="{{ route('admin.documents.stats') }}">📊 Statistiques</a></li>
                    <li><a href="{{ route('admin.documents.validation.index') }}">🛡️ Valider les documents</a></li>
                </ul>
            </div>
        @endrole

        {{-- RESPONSABLE --}}
        @role('responsable')
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-2">Espace responsable</h3>
                <ul class="list-disc ml-6 text-green-600">
                    <li><a href="#">Ajouter un document</a></li>
                    <li><a href="/admin/documents">Gérer les documents de mon service</a></li>
                </ul>
            </div>
        @endrole

        {{-- EMPLOYE --}}
        @role('employe')
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-2">Espace employé</h3>
                <ul class="list-disc ml-6 text-purple-600">
                    <li><a href="#">Ajouter un document</a></li>
                    <li><a href="/admin/documents">Mes documents</a></li>
                </ul>
            </div>
        @endrole
    </div>
</x-app-layout>

