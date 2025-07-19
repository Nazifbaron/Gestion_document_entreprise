<x-app-layout>
    <x-slot name="heading">🕓 Historique des actions</x-slot>

    <div class="overflow-x-auto bg-white shadow rounded-xl p-6">
        <table class="table-auto w-full text-sm">
            <thead>
                <tr class="text-left text-gray-700 font-bold border-b">
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Document</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr class="border-b">
                        <td>{{ optional($log->causer)->name ?? 'Système' }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ optional($log->subject)->title ?? '-' }}</td>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $logs->links() }}</div>
        <a href="{{ route('admin.documents.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Accueil
            </a>
    </div>
</x-app-layout>
