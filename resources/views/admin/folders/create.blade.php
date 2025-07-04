<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Créer un nouveau dossier</h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto bg-white shadow p-6 rounded">
        <form method="POST" action="{{ route('admin.folders.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium">Nom du dossier</label>
                <input type="text" name="name" class="w-full mt-1 p-2 border rounded" required>
                @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Description (facultatif)</label>
                <textarea name="description" class="w-full mt-1 p-2 border rounded"></textarea>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Enregistrer
            </button>
        </form>
    </div>
</x-app-layout>
