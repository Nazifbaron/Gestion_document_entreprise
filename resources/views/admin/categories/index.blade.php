<x-app-layout>
    <div class="flex justify-between items-center mb-4 px-2 mt-2">
        <h1 class="text-xl font-bold">📂 Catégories</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded ">+ Ajouter</a>
    </div>
    @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded',function(){
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: '{{ session('success')}}',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    })
                });
            </script>
        @endif
    @foreach ($categories as $category)
    <div class="px-2">
        <div class="flex justify-between border p-3 mb-2 rounded mb-3 ">
            <div>{{ $category->name }}</div>
            <div class="flex">
                <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-500 mr-3">✏️</a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="delete-form">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-500">🗑️</button>
                </form>
            </div>
        </div>
        </div>    
    @endforeach
    <a href="{{ route('admin.documents.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 ml-2">
                    Accueil
            </a>

</x-app-layout>
