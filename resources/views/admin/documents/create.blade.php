<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Ajouter un document</h2>
            <a href="{{ route('admin.documents.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Accueil
            </a>
        </div>
        
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto bg-white shadow p-6 rounded my-3">
        <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium"></label>
                <x-input-label>Titre</x-input-label>
                <x-text-input type="text" name="title" placeholder="Un titre" required></x-text-input>
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label>Description</x-input-label>
                <textarea name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"></textarea>
            </div>

            <div class="mb-4">
                <x-input-label>Type du Document</x-input-label>
                <x-text-input type="text" name="type" required></x-text-input>
                <x-input-error :messages="$errors->get('type')" class="mt-2" />
            </div>

            <div class="mb-4">
            <x-input-label>Dossier</x-input-label>
            <select name="folder_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                    <option value="">-- Choisir un dossier --</option>
                    @foreach($folders as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
            <x-input-label>Département</x-input-label>
            <select name="category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                    <option value="">-- Choisir un département --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <x-input-label>Fichiers</x-input-label>
                <x-text-input type="file" name="file" required></x-text-input>
                <x-input-error :messages="$errors->get('file')" class="mt-2" />         
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Enregistrer
            </button>
        </form>
    </div>
</x-app-layout>
