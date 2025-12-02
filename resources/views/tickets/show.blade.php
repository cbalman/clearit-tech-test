<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ticket Details: {{ $ticket->ticket_name }} (#{{ $ticket->id }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Ticket Information
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($ticket->status === 'new') bg-yellow-100 text-yellow-800
                                        @elseif($ticket->status === 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Creator</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $ticket->creator->name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Agent</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{-- CORRECCIÓN DE SINTAXIS: Usar el operador ternario tradicional --}}
                                    {{ $ticket->agent ? $ticket->agent->name : 'N/A' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Transport/Product</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst($ticket->mode_of_transport) }} / {{ $ticket->product }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Origin/Destination</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $ticket->country_origin }} -> {{ $ticket->country_destination }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-t border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Attached Documents</h3>
                    </div>
                    <div class="p-6">
                        <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                            @forelse($ticket->documents as $document)
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        {{-- Simplificado: El ícono no está definido en el HTML original --}}
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-2.25A3.375 3.375 0 0 0 10.5 8.25v2.625m.5 8.25H9M12 21.75l-4.25-4.25"/></svg>
                                        <span class="ml-2 flex-1 w-0 truncate">
                                            {{ $document->file_name }}
                                            @if($document->requested_by_agent)
                                                <span class="text-xs font-semibold text-blue-500">(Requested)</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Download
                                        </a>
                                    </div>
                                </li>
                            @empty
                                <li class="px-4 py-3 text-center text-gray-500">No documents attached yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="md:col-span-1 space-y-6">

                    @if(Auth::user()->role === 'agent')
                        <div class="bg-white shadow sm:rounded-lg p-6">
                            <h4 class="text-md font-semibold mb-3">Agent Actions</h4>

                            @if($ticket->status === 'new' && $ticket->agent_id === null)
                                {{-- La lógica de asignación está en requestDocument, pero podemos añadir un mensaje --}}
                                <p class="mb-3 text-sm text-gray-600">This ticket is unassigned. Taking action will assign it to you.</p>
                            @endif

                            @if($ticket->status !== 'completed')
                                <form method="POST" action="{{ route('tickets.requestDocument', $ticket) }}" class="mb-4">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded transition">
                                        Request Additional Documents
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('tickets.updateStatus', $ticket) }}">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Change Status:</label>
                                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="new" @if($ticket->status === 'new') selected @endif>New</option>
                                        <option value="in_progress" @if($ticket->status === 'in_progress') selected @endif>In Progress</option>
                                        <option value="completed" @if($ticket->status === 'completed') selected @endif>Completed</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    @endif

                    @if(Auth::user()->role === 'user' || Auth::user()->role === 'agent')
                        <div class="bg-white shadow sm:rounded-lg p-6">
                            <h4 class="text-md font-semibold mb-3">Upload Documents</h4>
                            <p class="text-sm text-gray-600 mb-4">Attach files related to the ticket (.pdf, .jpg, .doc, max 5MB).</p>

                            <form method="POST" action="{{ route('tickets.upload', $ticket) }}" enctype="multipart/form-data"> {{-- Corregir a 'multipart/form-data' --}}
                                @csrf
                                <input type="file" name="document_file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 mb-3">

                                <input type="hidden" name="is_requested" value="0">

                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                                    Upload Document
                                </button>
                            </form>
                            @error('document_file') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
