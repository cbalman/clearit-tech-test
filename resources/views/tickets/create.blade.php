<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form method="POST" action="{{ route('tickets.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="ticket_name" class="block text-gray-700 text-sm font-bold mb-2">Ticket Name:</label>
                            <input type="text" name="ticket_name" id="ticket_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            @error('ticket_name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ticket_type" class="block text-gray-700 text-sm font-bold mb-2">Ticket Type (1, 2, 3):</label>
                            <select name="ticket_type" id="ticket_type" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select Type</option>
                                <option value="1">Type 1</option>
                                <option value="2">Type 2</option>
                                <option value="3">Type 3</option>
                            </select>
                            @error('ticket_type') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="mode_of_transport" class="block text-gray-700 text-sm font-bold mb-2">Mode of Transport:</label>
                            <select name="mode_of_transport" id="mode_of_transport" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select Mode</option>
                                <option value="air">Air</option>
                                <option value="land">Land</option>
                                <option value="sea">Sea</option>
                            </select>
                            @error('mode_of_transport') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="product" class="block text-gray-700 text-sm font-bold mb-2">Product to Import/Export:</label>
                            <input type="text" name="product" id="product" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            @error('product') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex space-x-4">
                            <div class="mb-4 w-1/2">
                                <label for="country_origin" class="block text-gray-700 text-sm font-bold mb-2">Country of Origin (Code):</label>
                                <input type="text" name="country_origin" id="country_origin" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required maxlength="2">
                                @error('country_origin') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-4 w-1/2">
                                <label for="country_destination" class="block text-gray-700 text-sm font-bold mb-2">Country of Destination (Code):</label>
                                <input type="text" name="country_destination" id="country_destination" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required maxlength="2">
                                @error('country_destination') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Submit Ticket
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
