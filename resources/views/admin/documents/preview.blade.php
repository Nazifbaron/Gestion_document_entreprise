<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Prévisualisation : {{ $document->title }}</h2>
    </x-slot>

    <div class="mt-6">
        @if(Str::endsWith($document->file_path, ['.pdf','.docx', '.odt', '.txt','.pptx','.xls','.xlsx']))
            <iframe src="{{ $url }}" width="100%" height="600px" class="border rounded"></iframe>
        @elseif(Str::endsWith($document->file_path, ['.jpg', '.jpeg', '.png']))
            <img src="{{ $url }}" alt="{{ $document->title }}" class="max-w-full h-auto border rounded">
        @else
            <p class="text-red-600">Ce type de fichier ne peut pas être prévisualisé.</p>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.documents.index') }}" class="text-blue-600 hover:underline">← Retour à la liste</a>
    </div>
</x-app-layout>
