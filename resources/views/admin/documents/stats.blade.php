<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">📊 Statistiques des documents</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">📄 Total de documents</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalDocuments }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">🗂️ Total de dossiers</h3>
            <p class="text-3xl font-bold text-green-600">{{ $totalFolders }}</p>
        </div>
    </div>

    <div class="mt-8 bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">📂 Documents par dossier</h3>
        <ul>
            @forelse($documentsParDossier as $folder)
                <li class="mb-1">
                    <span class="font-medium">{{ $folder->name }}</span> :
                    {{ $folder->documents_count }} document(s)
                </li>
            @empty
                <li>Aucun dossier disponible.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-8 bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">📎 Répartition par extension</h3>
        <ul>
            @forelse($extensions as $ext)
                <li class="mb-1">
                    <span class="font-medium">{{ strtoupper($ext->extension) }}</span> :
                    {{ $ext->total }} document(s)
                </li>
            @empty
                <li>Aucune donnée d’extension disponible.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.documents.index') }}" class="text-blue-600 hover:underline">← Retour à la liste</a>
    </div>
    <div class="mt-6">
        <a href="{{ route('admin.documents.stats.mois') }}" class="text-blue-600 hover:underline">← Statistique par mois</a>
    </div>
</x-app-layout>
