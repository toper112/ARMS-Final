<x-admin-layout>
    <div class="py-1 pb-20">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-green-100 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Event Feedbacks List:</h1>
                    <div class="space-y-4">
                        @if ($suggestions->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400">No feedback yet!</p>
                        @else
                            @foreach ($suggestions as $suggestion)
                                <!-- Card for each suggestion -->
                                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-md">
                                    <div class="flex items-center space-x-2">
                                        <lord-icon src="https://cdn.lordicon.com/kdduutaw.json" trigger="hover"
                                            style="width:20px;height:20px"></lord-icon>
                                        <h2 class="text-lg font-semibold">Feedback</h2>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $suggestion->context }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
