<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Liste des permissions</h2>
    </x-slot>

    <div class="py-6">
        <a href="{{ route('admin.permissions.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Ajouter une permission
        </a>

        <table class="min-w-full mt-6 bg-white shadow rounded">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                    <tr class="border-t">
                        <td class="px-6 py-4">{{ $permission->name }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-blue-600 hover:underline">Modifier</a>
                            <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
