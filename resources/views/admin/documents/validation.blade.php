<x-app-layout>
    <x-slot:heading>📥 Documents en attente de validation</x-slot:heading>

    <div class="mt-6">
        @foreach ($documents as $document)
            <div class="bg-white p-4 rounded shadow mb-4 flex justify-between items-center">
                <div>
                    <strong>{{ $document->title }}</strong><br>
                    Type : {{ $document->type }}<br>
                    Ajouté par : {{ $document->user->name }}
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.documents.valider', $document) }}">
                        @csrf
                        @method('PATCH')
                        <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Valider</button>
                    </form>
                    <form method="POST" action="{{ route('admin.documents.rejeter', $document) }}">
                        @csrf
                        @method('PATCH')
                        <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Rejeter</button>
                    </form>
                </div>
            </div>
        @endforeach

        @if ($documents->isEmpty())
            <p class="text-gray-600">Aucun document à valider pour le moment.</p>
        @endif
    </div>
</x-app-layout>
