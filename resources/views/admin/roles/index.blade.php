<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Liste des rôles</h2>
    </x-slot>

    <div class="py-6">
        <a href="{{ route('admin.roles.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Ajouter un rôle
        </a>

        <table class="min-w-full mt-6 bg-white shadow rounded text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Permissions</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr class="border-t">
                        <td class="px-6 py-4 font-medium">{{ $role->name }}</td>

                        <td class="px-6 py-4">
                            @forelse($role->permissions as $permission)
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-1 mb-1">
                                    {{ $permission->name }}
                                </span>
                            @empty
                                <span class="text-gray-500 italic">Aucune</span>
                            @endforelse
                        </td>

                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('admin.roles.edit', $role) }}"  class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Modifier</a>
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Supprimer ce rôle ?')">
                                @csrf @method('DELETE')
                                <x-danger-button>Supprimer</x-danger-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
