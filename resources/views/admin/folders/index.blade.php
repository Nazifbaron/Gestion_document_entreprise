<x-app-layout>
    <x-slot name="header">
        
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Liste des dossiers</h2>
            <a href="{{ route('admin.documents.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Accueil
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-3">

        <a href="{{ route('admin.folders.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            + Nouveau dossier
        </a>


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

        <table class="min-w-full mt-6 bg-white shadow rounded text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Description</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($folders as $folder)
                    <tr class="border-t">
                        <td class="px-6 py-4">{{ $folder->name }}</td>
                        <td class="px-6 py-4">{{ $folder->description }}</td>
                        <td class="px-6 py-4 text-center flex gap-2 justify-center">
                            <a href="{{ route('admin.folders.edit', $folder) }}"><x-primary-button>Modifier</x-primary-button> </a>
                            <!--<form action="{{ route('admin.folders.destroy', $folder) }}" method="POST" onsubmit="return confirm('Supprimer ce dossier ?')">
                                @csrf @method('DELETE')
                                <x-danger-button>Supprimer</x-danger-button>
                            </form>-->

                            <form action="{{ route('admin.folders.destroy', $folder) }}" method="POST" class="delete-form">
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
