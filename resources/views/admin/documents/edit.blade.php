<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Modifier le document</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-6">
        <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
         
            <div class="mb-4">
                <label for="title" class="block font-semibold">Titre</label>
                <input type="text" name="title" id="title"
                       value="{{ old('title', $document->title) }}"
                       class="w-full border rounded p-2 mt-1">
            </div>

            <div class="mb-4">
                <label for="type" class="block font-semibold">Type</label>
                <input type="text" name="type" id="type"
                       value="{{ old('type', $document->type) }}"
                       class="w-full border rounded p-2 mt-1">
            </div>

            <div class="mb-4">
                <label for="folder_id" class="block font-semibold">Dossier</label>
                <select name="folder_id" id="folder_id" class="w-full border rounded p-2 mt-1">
                    @foreach($folders as $folder)
                        <option value="{{ $folder->id }}" {{ $document->folder_id == $folder->id ? 'selected' : '' }}>
                            {{ $folder->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="file" class="block font-semibold">Remplacer le fichier (facultatif)</label>
                <input type="file" name="file" id="file" class="w-full p-2 mt-1 border rounded">
                <p class="text-sm text-gray-500 mt-1">Laisser vide pour ne pas changer le fichier actuel.</p>
            </div>
            <h3 class="text-lg font-bold mt-6">Versions précédentes</h3>

                @if ($document->versions->isEmpty())
                    <p class="text-sm text-gray-500 italic">Aucune version précédente n’a encore été enregistrée.</p>
                @else
                    <ul class="mt-3 space-y-2">
                        @foreach ($document->versions as $version)
                            <li class="flex items-center justify-between border-b pb-2">
                                <div>
                                    Version {{ $version->version_number }} -
                                    <a href="{{ route('admin.documents.versions.download', $version) }}" class="text-blue-500 underline">Télécharger</a>
                                </div>
                                <div class="flex items-center gap-3">
                                    {{-- Restaurer --}}
                                    <form action="{{ route('admin.documents.versions.restore', $version) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm text-green-600 hover:underline">Restaurer</button>
                                    </form>

                                    {{-- Supprimer --}}
                                    <form action="{{ route('admin.documents.versions.destroy', $version) }}" method="POST" onsubmit="return confirm('Supprimer cette version ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline">Supprimer</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                @endif


            <div class="flex justify-end">
                <a href="{{ route('admin.documents.index') }}"
                   class="mr-4 text-gray-600 hover:underline">Annuler</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Mettre à jour</button>
            </div>
        </form>
    </div>
</x-app-layout>
