<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Créer un nouvel utilisateur</h2>
            <a href="{{ route('admin.utilisateurs.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Accueil
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-6 max-w-xl mx-auto">
        <form method="POST" action="{{ route('admin.utilisateurs.store') }}">
            @csrf
            @method('POST')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium">Nom</label>
                <input type="text" name="name" id="name" required class="w-full border px-3 py-2 rounded" />
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" id="email" required class="w-full border px-3 py-2 rounded" />
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">Mot de passe</label>
                <input type="password" name="password" id="password" required class="w-full border px-3 py-2 rounded" />
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700 font-medium">Rôle</label>
                <select name="role" id="role" required class="w-full border px-3 py-2 rounded">
                    <option value="">-- Choisir un rôle --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Créer l'utilisateur
            </button>
        </form>
    </div>
</x-app-layout>
