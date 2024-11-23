<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Event Details</h1>

                    <div class="space-y-4">
                        <div>
                            <h2 class="text-lg font-semibold">Event Name:</h2>
                            <p class="text-gray-600 dark:text-gray-400">{{ $event->name }}</p>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold">Description:</h2>
                            <p class="text-gray-600 dark:text-gray-400">{{ $event->description }}</p>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold">Date:</h2>
                            <p class="text-gray-600 dark:text-gray-400">{{ $event->date }}</p>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold">Time:</h2>
                            <p class="text-gray-600 dark:text-gray-400">{{ $event->time }}</p>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold">Status:</h2>
                            <p class="text-gray-600 dark:text-gray-400">
                                @php
                                    $currentDate = \Carbon\Carbon::now(); // Get the current date and time
                                    $eventDate = \Carbon\Carbon::parse($event->date); // Parse the event's date
                                @endphp

                                @if ($currentDate->isSameDay($eventDate))
                                    <span class="text-yellow-500">Ongoing</span>
                                @elseif ($currentDate->isAfter($eventDate))
                                    <span class="text-green-500">Done</span>
                                @else
                                    <span class="text-blue-500">Upcoming</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('events.index') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Back to Events List
                        </a>

                        <!-- Edit Button -->
                        @role('admin')
                            <button type="button"
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                                data-modal-target="editModal">
                                Edit Event
                            </button>
                        @endrole

                        <!-- Suggestion Button -->
                        <button type="button"
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded"
                            data-modal-target="feedbackModal">
                            Suggest Feedback
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-1/3">
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4">Edit Event</h2>
                <form action="{{ route('events.update', $event->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event Name</label>
                            <input type="text" id="name" name="name" value="{{ $event->name }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea id="description" name="description"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $event->description }}</textarea>
                        </div>
                        <div>
                            <label for="date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                            <input type="date" id="date" name="date" value="{{ $event->date }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="time"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time</label>
                            <input type="time" id="time" name="time" value="{{ $event->time }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                            data-modal-close="editModal">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-1/3">
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4">Submit Feedback</h2>
                <form action="{{ route('admin.suggestions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <div class="space-y-4">
                        <div>
                            <label for="feedback"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Your Feedback</label>
                            <textarea id="feedback" name="feedback"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Write your feedback here..." rows="4"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="dateSubmitted" value="{{ now() }}">
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                            data-modal-close="feedbackModal">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script to toggle modal -->
    <script>
        document.querySelectorAll('[data-modal-target]').forEach(button => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.getAttribute('data-modal-target'));
                modal.classList.remove('hidden');
            });
        });
        document.querySelectorAll('[data-modal-close]').forEach(button => {
            button.addEventListener('click', () => {
                const modal = button.closest('.fixed');
                modal.classList.add('hidden');
            });
        });
    </script>
</x-admin-layout>
