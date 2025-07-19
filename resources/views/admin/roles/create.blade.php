<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between ">
            <h2 class="text-xl font-semibold">Créer un rôle</h2>
            <a href="{{ route('admin.roles.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Accueil
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('admin.roles.store') }}" method="POST" class="max-w-md">
            @csrf
            <x-input-label class="ml-2">Nom du rôle</x-input-label>
            <x-text-input type="text" name="name" required class="ml-2"></x-text-input>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
            <x-primary-button class="mt-2 ml-2">Créer</x-primary-button>

        </form>
    </div>
    
</x-app-layout>
