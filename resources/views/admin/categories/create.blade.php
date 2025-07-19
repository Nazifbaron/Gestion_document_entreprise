<x-app-layout>

    <h1 class="text-xl font-bold mb-4 ml-3">{{ isset($category) ? 'Modifier' : 'Créer' }} une catégorie</h1>

    <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif
        <div class="px-3">
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}"
                placeholder="Nom de la catégorie"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mb-3" required>

            <button class="bg-green-500 text-white px-4 py-2 rounded">
                {{ isset($category) ? 'Mettre à jour' : 'Ajouter' }}
            </button>
        </div>
    </form>

</x-app-layout>