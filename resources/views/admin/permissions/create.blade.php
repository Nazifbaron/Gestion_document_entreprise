<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Créer une permission</h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('admin.permissions.store') }}" method="POST" class="max-w-md">
            @csrf
            <label class="block mb-2">Nom de la permission</label>
            <input type="text" name="name" class="w-full border-gray-300 rounded mb-4">
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Créer</button>
        </form>
    </div>
</x-app-layout>
