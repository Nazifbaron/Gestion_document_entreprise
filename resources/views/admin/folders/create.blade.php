<x-app-layout>
    <x-slot name="header">
        
        <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Créer un nouveau dossier</h2>
            <a href="{{ route('admin.documents.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Accueil
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto bg-white shadow p-6 rounded my-3">
        <form method="POST" action="{{ route('admin.folders.store') }}">
            @csrf

            <div class="mb-4">
                <x-input-label>Nom du dossier</x-input-label>
                <x-text-input type="text" name="name" required></x-text-input>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Description (facultatif)</label>
                <textarea name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"></textarea>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Enregistrer
            </button>
        </form>
    </div>
</x-app-layout>
