<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Ajouter un document</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto bg-white shadow p-6 rounded">
        <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium">Titre</label>
                <input type="text" name="title" class="w-full mt-1 p-2 border rounded" required>
                @error('title') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="w-full mt-1 p-2 border rounded"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Type du document</label>
                <input type="text" name="type" class="w-full mt-1 p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Dossier</label>
                <select name="folder_id" class="w-full mt-1 p-2 border rounded" required>
                    <option value="">-- Choisir un dossier --</option>
                    @foreach($folders as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Fichier</label>
                <input type="file" name="file" class="w-full mt-1" required>
                @error('file') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Enregistrer
            </button>
        </form>
    </div>
</x-app-layout>
