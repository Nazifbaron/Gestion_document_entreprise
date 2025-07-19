<x-app-layout>
<div class="px-3 mt-2">
            <div class="bg-white p-4 rounded shadow mb-6 ">
                    <h2 class="text-xl font-bold mb-4">📤 Partager plusieurs documents</h2>
                    <form method="GET" class="mb-4">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="🔍 Rechercher un document par titre..." 
                            value="{{ request('search') }}"
                            class="w-full md:w-1/2 border rounded p-2"
                        >
                    </form>


                    <form method="POST" action="{{ route('admin.documents.partager.multiple') }}">
                        @csrf

                        {{-- ✅ Sélection des documents --}}
                        <div class="mb-4">
                            <label class="font-semibold mb-2 block">Choisir les documents :</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto border p-2 rounded">
                                @foreach ($documents as $document)
                                    <label class="flex items-center justify-between gap-3 px-3 py-2 bg-white rounded-lg shadow border cursor-pointer">
                                        <span class="text-sm text-gray-700">{{ $document->title }}</span>
                                        <input type="checkbox" name="document_ids[]" value="{{ $document->id }}" class="rounded">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        

                        {{-- 👥 Sélection des utilisateurs --}}
                        <div class="mb-4">
                            <label class="font-semibold mb-2 block">Partager avec :</label>
                            <select name="user_ids[]" multiple class="w-full border rounded p-2">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Partager les documents sélectionnés
                        </button>
                    </form>
                </div>
            </div>
        </div>
</x-app-layout>