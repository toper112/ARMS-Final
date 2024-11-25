<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div
                                    class="shadow overflow-hidden border-b border-gray-200 dark:border-gray-700 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                    Event
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                    Date
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                    Actions
                                                </th>
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
                                                        <div
                                                            class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                                            {{ $event->date }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <a href="{{ route('suggestions.show', $event->id) }}"
                                                            class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                                            <lord-icon src="https://cdn.lordicon.com/dicvhxpz.json"
                                                                trigger="hover" style="width:30px;height:30px">
                                                            </lord-icon>
                                                        </a>
                                                    </td>
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
    </div>
</x-admin-layout>
