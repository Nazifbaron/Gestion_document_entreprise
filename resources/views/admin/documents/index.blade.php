<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Liste des documents</h2>
    </x-slot>

    <div class="py-6 ">
        
        <div class="py-6 flex justify-between items-center">
            <a href="{{ route('admin.documents.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Ajouter un document
            </a>
            <a href="{{ route('admin.documents.historique') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Historique
            </a>
            <a href="{{ route('admin.folders.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                + Ajouter un nouveau dossier
            </a>
        </div>
        <div class=" flex justify-between items-center">
            <form method="GET" action="{{ route('admin.documents.index') }}" class="mb-4 flex flex-wrap items-center gap-4">
                <input type="text" name="titre" placeholder="Rechercher par titre"
                    value="{{ request('titre') }}"
                    class="border rounded px-3 py-2" />

                <select name="folder_id" class="border rounded px-3 py-2">
                    <option value="">Tous les dossiers</option>
                    @foreach($folders as $folder)
                        <option value="{{ $folder->id }}" {{ request('folder_id') == $folder->id ? 'selected' : '' }}>
                            {{ $folder->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrer</button>

                <a href="{{ route('admin.documents.index') }}" class="text-gray-600 hover:underline">Réinitialiser</a>
            </form>
            <form action="{{ route('admin.documents.export.pdf') }}" method="GET" class="mb-4">
                                    <!-- conserver les filtres actifs -->
                <input type="hidden" name="titre" value="{{ request('titre') }}">
                <input type="hidden" name="folder_id" value="{{ request('folder_id') }}">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">📥 Exporter PDF</button>
            </form>
        </div>
       
  
        @if(session('success'))
            <div class="mt-4 text-green-600">{{ session('success') }}</div>
        @endif

        <table class="min-w-full mt-6 bg-white shadow rounded text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Titre</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Dossier</th>
                    <th class="px-4 py-2 text-left">Ajouté par</th>
                    <th class="px-4 py-2 text-center">Date d'Ajout</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $document->title }}</td>
                        <td class="px-4 py-2">{{ $document->type }}</td>
                        <td class="px-4 py-2">{{ $document->folder->name ?? 'Non défini' }}</td>
                        <td class="px-4 py-2">{{ $document->user->name ?? 'Inconnu' }}</td>
                        <td class="px-4 py-3">{{ $document->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-center flex gap-2 justify-center">
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Prévisualiser</a>
                            <a href="{{ route('admin.documents.telecharger', $document) }}" class="text-blue-600 hover:underline">
                            Télécharger
                        </a>

                        @can('update', $document)
                        <a href="{{ route('admin.documents.edit', $document) }}"
                            class="text-yellow-600 hover:underline">
                            Modifier
                        </a>
                        @endcan

                        @can('delete', $document)
                            <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" onsubmit="return confirm('Supprimer ce document ?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                            @endcan
                        </td>
                        

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Aucun document trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $documents->appends(request()->query())->links() }}
</x-app-layout>


