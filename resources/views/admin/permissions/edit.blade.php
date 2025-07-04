<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Modifier la permission : {{ $permission->name }}</h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" class="max-w-md">
            @csrf
            @method('PUT')
            <label class="block mb-2">Nom de la permission</label>
            <input type="text" name="name" value="{{ $permission->name }}" class="w-full border-gray-300 rounded mb-4">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Mettre à jour</button>
        </form>
    </div>
</x-app-layout>
