<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between ">
            <h2 class="text-xl font-semibold">Modifier le rôle : {{ $role->name }}</h2>
            <a href="{{ route('admin.roles.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Accueil
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-2">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="max-w-xl">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block mb-1">Nom du rôle</label>
                <input type="text" name="name" value="{{ $role->name }}" class="w-full rounded border-gray-300">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Permissions :</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="permissions[]"
                                   value="{{ $permission->name }}"
                                   {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Mettre à jour
            </button>
        </form>
    </div>
</x-app-layout>

