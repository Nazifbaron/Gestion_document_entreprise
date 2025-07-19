<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Gestion des utilisateurs</h2>
         
    </x-slot>

    <div class="py-6 px-6">
        <a href="{{ route('admin.utilisateurs.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Nouvel utilisateur
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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="my-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <table class="mt-6 w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">Nom</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Rôle</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">
                            {{ $user->getRoleNames()->join(', ') }}
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('admin.utilisateurs.edit', $user) }}" ><x-primary-button>Modifier</x-primary-button></a>
                            <form action="{{ route('admin.utilisateurs.destroy', $user) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Voulez-vous Supprimer cet utilisateurs ?')">
                                @csrf
                                @method('DELETE')
                                <x-danger-button>Supprimer</x-danger-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
