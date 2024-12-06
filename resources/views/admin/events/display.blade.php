<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-8 flex">
                    <!-- Flex container for equal height (use flex-grow for sections) -->
                    <div class="flex w-full space-x-6">
                        <!-- Event Details Section (Left side) -->
                        <div class="w-1/2 space-y-6 flex flex-col justify-between">
                            <!-- Event Name -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Event Name:</h2>
                                <p class="text-gray-600 dark:text-gray-300">{{ $event->name }}</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Description:</h2>
                                <p class="text-gray-600 dark:text-gray-300">{{ $event->description }}</p>
                            </div>

                            <!-- Date -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Date:</h2>
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($event->date)->format('l, F j, Y') }}</p>
                            </div>
                            {{-- fines --}}
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Fines Per Sign-in:
                                </h2>
                                <p class="text-gray-600 dark:text-gray-300">₱ {{ $event->fines }}</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Status:</h2>
                                <p class="text-gray-600 dark:text-gray-300">
                                    @php
                                        $currentDate = \Carbon\Carbon::now();
                                        $eventDate = \Carbon\Carbon::parse($event->date);
                                    @endphp
                                    @if ($currentDate->isSameDay($eventDate))
                                        <span class="text-yellow-500 font-semibold">Ongoing</span>
                                    @elseif ($currentDate->isAfter($eventDate))
                                        <span class="text-green-500 font-semibold">Done</span>
                                    @else
                                        <span class="text-blue-500 font-semibold">Upcoming</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Attendance Overview Section (Right side) -->
                        @role('admin|officer')
                            <div class="w-1/2 space-y-6 flex flex-col justify-between border-t lg:border-t-0">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Attendance Overview:</h2>
                                <div class="space-y-6 mt-2">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Morning Time-In:
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            <span class="font-medium">{{ $morningTimeInAttendanceCount }} students</span>
                                        </p>
                                    </div>

                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Morning Time-Out:
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            <span class="font-medium">{{ $morningTimeOutAttendanceCount }} students</span>
                                        </p>
                                    </div>

                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Afternoon
                                            Time-In:
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            <span class="font-medium">{{ $afternoonTimeInAttendanceCount }} students</span>
                                        </p>
                                    </div>

                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Afternoon
                                            Time-Out:</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            <span class="font-medium">{{ $afternoonTimeOutAttendanceCount }} students</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endrole
                    </div>
                </div>
            </div>
            <!-- Buttons -->
            <div class="mt-8 flex space-x-6">
                <a href="{{ route('events.index') }}"
                    class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition duration-200 ease-in-out">
                    <i class="fa fa-arrow-left mr-2"></i> Return
                </a>

                @role('admin')
                    <button type="button"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-md transition duration-200 ease-in-out"
                        data-modal-target="editModal">
                        <i class="fa fa-edit mr-2"></i> Edit
                    </button>
                @endrole

                @role('officer')
                    <button type="button"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg shadow-md transition duration-200 ease-in-out"
                        data-modal-target="feedbackModal">
                        <i class="fa fa-comment-dots mr-2"></i> Feedback
                    </button>
                @else
                    @if (auth()->user() && !auth()->user()->roles()->exists())
                        <button type="button"
                            class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg shadow-md transition duration-200 ease-in-out"
                            data-modal-target="feedbackModal">
                            <i class="fa fa-comment-dots mr-2"></i> Feedback
                        </button>
                    @endif
                @endrole
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-1/3">
            <div class="p-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Edit Event</h2>
                <form action="{{ route('events.update', $event->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Event Name</label>
                            <input type="text" id="name" name="name" value="{{ $event->name }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea id="description" name="description"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ $event->description }}</textarea>
                        </div>
                        <div>
                            <label for="date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                            <input type="date" id="date" name="date" value="{{ $event->date }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                    </div>
                    <div class="mt-8 flex justify-end space-x-4">
                        <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-6 rounded-lg"
                            data-modal-close="editModal">Cancel</button>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-1/3">
            <div class="p-8">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Submit Feedback</h2>
                <form action="{{ route('suggestions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <div class="space-y-6">
                        <div>
                            <label for="context"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Your Feedback</label>
                            <textarea id="context" name="context" rows="4"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Write your feedback here..."></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end space-x-4">
                        <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-6 rounded-lg"
                            data-modal-close="feedbackModal">Cancel</button>
                        <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-6 rounded-lg">Submit
                            Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit Modal functionality
        const editButton = document.querySelector('[data-modal-target="editModal"]');
        const editModal = document.getElementById('editModal');
        const closeEditModal = document.querySelector('[data-modal-close="editModal"]');

        if (editButton) {
            editButton.addEventListener('click', function() {
                editModal.classList.remove('hidden');
            });
        }

        if (closeEditModal) {
            closeEditModal.addEventListener('click', function() {
                editModal.classList.add('hidden');
            });
        }

        // Feedback Modal functionality
        const feedbackButton = document.querySelector('[data-modal-target="feedbackModal"]');
        const feedbackModal = document.getElementById('feedbackModal');
        const closeFeedbackModal = document.querySelector('[data-modal-close="feedbackModal"]');

        if (feedbackButton) {
            feedbackButton.addEventListener('click', function() {
                feedbackModal.classList.remove('hidden');
            });
        }

        if (closeFeedbackModal) {
            closeFeedbackModal.addEventListener('click', function() {
                feedbackModal.classList.add('hidden');
            });
        }
    });
</script>
