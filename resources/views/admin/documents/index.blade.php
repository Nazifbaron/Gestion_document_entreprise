<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Liste des documents</h2>
    </x-slot>

    <div class="py-6  ">
        <div class="flex justify-between mb-4 ml-2">
            <div class="relative inline-block text-left">
                <button id="dropdownButton" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                    📁 Ajouter
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="dropdownMenu" class="hidden absolute right-0 z-10 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="dropdownButton">
                        <a href="{{ route('admin.documents.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">➕ Document</a>
                        <a href="{{ route('admin.folders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">📂 Dossier</a>
                        <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">🏷️ Département</a>
                    </div>
                </div>
            </div>
            <div class="mr-3">
                <a href="{{ route('admin.documents.historique') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Historique
                </a>
            </div>
        </div>

        <div class=" flex justify-between items-center ml-2">
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
            <form action="{{ route('admin.documents.export.pdf') }}" method="GET" class="mb-4 mr-3">
                                    <!-- conserver les filtres actifs -->
                <input type="hidden" name="titre" value="{{ request('titre') }}">
                <input type="hidden" name="folder_id" value="{{ request('folder_id') }}">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">📥 Exporter PDF</button>
            </form>
        </div>
       
  
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded',function(){
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: '{{ session('success')}}',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    })
                });
            </script>
        @endif

        @foreach(auth()->user()->unreadNotifications as $notification)
        <div class="px-3">
            <div class="bg-blue-100 text-blue-800 p-3 rounded mb-4 ">
                <p> 📎{{ $notification->data['message'] }}</p>

                <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-blue-700 hover:underline text-xs ml-4">
                        Marquer comme lue
                    </button>
                </form>
            </div>

        </div>         
        @endforeach

        @if (auth()->user()->unreadNotifications->count())
            <form action="{{ route('admin.notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-green-600 hover:underline mb-4">
                    Marquer toutes les notifications comme lues
                </button>
            </form>
        @endif


        <div class="px-3">
            <table class="min-w-full mt-6 bg-white shadow rounded text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Titre</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Dossier</th>
                        <th class="px-4 py-2 text-left">Département</th>
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
                            <td class="px-4 py-2">{{ $document->category->name ?? 'Non défini' }}</td>
                            <td class="px-4 py-2">{{ $document->user->name ?? 'Inconnu' }}</td>
                            <td class="px-4 py-3">{{ $document->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-center flex gap-2 justify-center">
                                @can('update', $document)
                                    <a href="{{ route('admin.documents.edit', $document) }}"
                                        class="text-yellow-600 hover:underline">
                                        ✏️
                                    </a>
                                @endcan
                                @can('delete', $document)
                                    <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 " >
                                            🗑️</button>
                                    </form>
                                @endcan
                                <a href="{{ route('admin.documents.preview', $document) }}" target="_blank" class="text-blue-600 hover:underline">👁️ Voir</a>
                                <a href="{{ route('admin.documents.telecharger', $document) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                ⬇️
                                Télécharger
                                </a>
                                <!-- Bouton qui ouvre le modal -->

                                <button
                                    data-document-id="{{ $document->id }}"
                                    class="open-share-modal bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                    Partager
                                </button>

                                @if (!$document->archived)
                                    <form method="POST" action="{{ route('admin.documents.archive', $document) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-sm text-gray-600 hover:text-blue-600" title="Archiver">
                                            🗃️ Archiver
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.documents.restore', $document) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-sm text-gray-600 hover:text-green-600" title="Restaurer">
                                            🔄 Restaurer
                                        </button>
                                    </form>
                                @endif

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

        
        <form method="GET" class="mb-4 mt-2 ml-3">
            <select name="archived" onchange="this.form.submit()" class="border p-2 rounded">
                <option value="">📂 Documents actifs</option>
                <option value="1" {{ request('archived') === '1' ? 'selected' : '' }}>🗃️ Archivés</option>
            </select>
        </form>

        <a href="{{ route('admin.documents.shareMultiple') }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 ml-3">Partager plusieurs document</a>

                    <!-- Section de partage groupé -->
        
                   

    </div>
    <!-- Modal de partage -->
<div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative">
        <h2 class="text-xl font-bold mb-4">Partager le document</h2>

        <form id="shareForm" method="POST" action="{{ route('admin.documents.share') }}">
            @csrf
            <input type="hidden" name="document_id" id="document_id">

            <input type="text" placeholder="🔍 Rechercher un utilisateur..." id="searchUser" class="w-full mb-3 p-2 border rounded">

            <div id="userList" class="max-h-60 overflow-y-auto border p-3 rounded bg-gray-50">
                @foreach ($users as $user)
                    <label class="block mb-1">
                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="mr-2 user-checkbox">
                        {{ $user->name }} ({{ $user->email }})
                    </label>
                @endforeach
            </div>

            <div class="mt-4 flex justify-end space-x-2">
                <button type="button" class="close-share-modal px-3 py-1 bg-gray-300 rounded">Annuler</button>
                <button type="submit" class="px-4 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Partager</button>
            </div>
        </form>
    </div>
</div>
<script>
    const modal = document.getElementById('shareModal');
    const documentIdInput = document.getElementById('document_id');
    const searchInput = document.getElementById('searchUser');

    document.querySelectorAll('.open-share-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            const docId = btn.getAttribute('data-document-id');
            documentIdInput.value = docId;
            modal.classList.remove('hidden');
        });
    });

    document.querySelectorAll('.close-share-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    });

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#userList label').forEach(label => {
            const text = label.innerText.toLowerCase();
            label.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
<script>
    document.getElementById('dropdownButton').addEventListener('click', function () {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });

    // Optionnel : refermer le menu quand on clique ailleurs
    document.addEventListener('click', function (e) {
        const button = document.getElementById('dropdownButton');
        const menu = document.getElementById('dropdownMenu');
        if (!button.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>

    {{ $documents->appends(request()->query())->links() }}
</x-app-layout>


