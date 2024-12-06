<x-admin-layout>
    <div class="py-1 pb-20 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div>
                    <h2 class="text-4xl font-bold mb-4">Events List:</h2>
                </div>
                <!-- Create Event Button -->
                @role('admin')
                    <div class="mb-4 flex justify-end">
                        <button
                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 transition duration-150 ease-in-out"
                            onclick="document.getElementById('createEventModal').classList.remove('hidden')">
                            Create Event
                        </button>
                    </div>
                @endrole
                <!-- Create Event Modal -->
                <div id="createEventModal"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-1/3">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Create New Event</h2>
                            <button class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                                onclick="document.getElementById('createEventModal').classList.add('hidden')">
                                &times;
                            </button>
                        </div>

                        <!-- User Event Form -->
                        <form method="POST" action="{{ route('events.store') }}" class="space-y-4">
                            @csrf

                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                                    required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" class="block mt-1 w-full rounded border border-gray-300" required></textarea>

                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Date -->
                            <div>
                                <x-input-label for="date" :value="__('Date')" />
                                <x-text-input id="date" name="date" type="date" class="block mt-1 w-full"
                                    required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="fines" :value="__('Fines')" />
                                <x-text-input id="fines" name="fines" type="number" class="block mt-1 w-full"
                                    required autofocus />
                                <x-input-error :messages="$errors->get('fines')" class="mt-2" />
                            </div>

                            <!-- Modal Actions -->
                            <div class="flex justify-end space-x-2">
                                <button type="button"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-150 ease-in-out"
                                    onclick="document.getElementById('createEventModal').classList.add('hidden')">
                                    Cancel
                                </button>
                                <x-primary-button>{{ __('Create') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Event Table -->
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div
                                class="shadow overflow-hidden border-b border-gray-200 dark:border-gray-700 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-green-100 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Description
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Fines
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Action
                                            </th>
                                            @role('admin|officer')
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                    Scanner
                                                </th>
                                            @endrole
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($events as $event)
                                            <tr
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div
                                                        class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $event->name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                                        {{ $event->description }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                                        {{ $event->date }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                                        ₱ {{ $event->fines }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex space-x-2 text-gray-500 dark:text-gray-300">
                                                        {{-- View button --}}
                                                        <a href="{{ route('events.show', $event->id) }}"
                                                            class="text-blue-500 hover:text-blue-700">
                                                            <lord-icon src="https://cdn.lordicon.com/dicvhxpz.json"
                                                                trigger="hover" style="width:30px;height:30px">
                                                            </lord-icon>
                                                        </a>


                                                        {{-- Delete button --}}
                                                        @role('admin')
                                                            <form action="{{ route('events.destroy', $event->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-500 hover:text-red-700">
                                                                    <lord-icon src="https://cdn.lordicon.com/hwjcdycb.json"
                                                                        trigger="hover" style="width:30px;height:30px">
                                                                    </lord-icon>
                                                                </button>
                                                            </form>
                                                        @endrole
                                                    </div>
                                                </td>
                                                @role('admin|officer')
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                                            <a href="{{ route('admin_officer.scanner.index', $event->id) }}"
                                                                class="text-blue-500 hover:text-blue-700">
                                                                <lord-icon src="https://cdn.lordicon.com/ggnoyhfp.json"
                                                                    trigger="hover" style="width:30px;height:30px">
                                                                </lord-icon>
                                                            </a>
                                                        </div>
                                                    </td>
                                                @endrole
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>

<script src="https://cdn.lordicon.com/lordicon.js"></script>
