<x-app-layout>
    <x-slot:heading>
        Mes notifications
    </x-slot:heading>

    <div class="container my-5">
        @forelse ($notifications as $notification)
        <div class="p-4 bg-white shadow mb-3 rounded flex justify-between items-center">
                <div>
                    <strong>{{ $notification->data['title'] }}</strong><br>
                    {{ $notification->data['message'] }}<br>
                    <small>Reçu le {{ $notification->created_at->format('d/m/Y à H:i') }}</small>
                </div>
                
                @if (is_null($notification->read_at))
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf
                        <button class="text-blue-600 hover:underline text-sm">
                            Marquer comme lue
                        </button>
                    </form>
                @else
                    <span class="text-green-600 text-sm">✓ Vue</span>
                @endif
            </div>
        @empty
            <p>Aucune notification.</p>
        @endforelse
    </div>
</x-app-layout>

