<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Event Feedbacks List:</h1>
                    <div class="space-y-4">
                        @if ($suggestions->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400">No feedback yet!</p>
                        @else
                            @foreach ($suggestions as $suggestion)
                                <div>
                                    <h2 class="text-lg font-semibold">User:</h2>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $suggestion->context }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
