<x-admin-layout>
    <div class="pb-20 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div>
                    <h2 class="text-4xl font-bold mb-4 py-2">Users List:</h2>
                </div>
                <!-- Action Buttons: Export and Import on the left, Create on the right -->
                <div class="flex justify-between mb-6">
                    <!-- Left Side: Export and Import buttons -->
                    <div class="flex space-x-4">
                        <!-- Export Button -->
                        <a href="{{ route('admin.users.export') }}"
                            class="px-4 py-2 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 transition duration-150">
                            Export Users
                        </a>

                        <!-- Import Button with Drag-and-Drop -->
                        <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data"
                            class="flex items-center">
                            @csrf
                            <input type="file" name="csv_file" id="csv-file-input" class="hidden" accept=".csv"
                                onchange="this.form.submit();">
                            <button type="button" id="import-btn"
                                class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 transition duration-150"
                                onclick="document.getElementById('csv-file-input').click();">
                                Import
                            </button>
                        </form>

                    </div>

                    <!-- Right Side: Create User button -->
                    <button
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 transition duration-150 ease-in-out"
                        onclick="document.getElementById('createUserModal').classList.remove('hidden')">
                        Create User
                    </button>
                </div>

                <!-- Search and Filter -->
                <div class="flex justify-between mt-4 mb-6">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="flex space-x-4">
                        <div>
                            <input type="text" name="search" placeholder="Search by name or LRN"
                                value="{{ request('search') }}"
                                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-green-500 dark:bg-gray-700 dark:text-white"
                                oninput="this.form.submit()">
                        </div>
                        <div>
                            <select name="year"
                                class="px-8 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-green-500 dark:bg-gray-700 dark:text-white"
                                onchange="this.form.submit()">
                                <option value="">Year</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}"
                                        {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="section"
                                class="px-8 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-green-500 dark:bg-gray-700 dark:text-white"
                                onchange="this.form.submit()">
                                <option value="">Section </option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section }}"
                                        {{ request('section') == $section ? 'selected' : '' }}>
                                        {{ $section }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <!-- User Table -->
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
                                                LRN
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Year
                                                @if (request('year'))
                                                    <span class="text-sm text-gray-500">({{ request('year') }})</span>
                                                @endif
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Section
                                                @if (request('section'))
                                                    <span
                                                        class="text-sm text-gray-500">({{ request('section') }})</span>
                                                @endif
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Actions
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Attendance
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($users as $user)
                                            <tr
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div
                                                        class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $user->first_name . ' ' . $user->last_name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                                        {{ $user->LRN }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                                        {{ $user->year }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                                        {{ $user->section }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex justify-end space-x-2">
                                                        <!-- Edit Role Icon -->
                                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                                            class="text-blue-500 hover:text-blue-600">
                                                            <lord-icon src="https://cdn.lordicon.com/exymduqj.json"
                                                                trigger="hover" style="width:30px;height:30px">
                                                            </lord-icon>
                                                        </a>

                                                        <!-- Delete Icon -->
                                                        <form method="POST"
                                                            action="{{ route('admin.users.destroy', $user->id) }}"
                                                            onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-500 hover:text-red-600">
                                                                <lord-icon src="https://cdn.lordicon.com/hwjcdycb.json"
                                                                    trigger="hover" style="width:30px;height:30px">
                                                                </lord-icon>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex justify-end space-x-2">
                                                        <!-- Attendance -->
                                                        <a href="{{ route('admin.attendance.show', $user->id) }}"
                                                            class="text-blue-500 hover:text-blue-600">
                                                            <lord-icon src="https://cdn.lordicon.com/dicvhxpz.json"
                                                                trigger="hover" style="width:30px;height:30px">
                                                            </lord-icon>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div class="mt-4">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <!-- Create User Modal -->
    <div id="createUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-sm shadow-lg w-1/3">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Create New User</h2>
                <button class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                    onclick="document.getElementById('createUserModal').classList.add('hidden')">
                    &times;
                </button>
            </div>

            <!-- User Create Form -->
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf

                <!-- First Name -->
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" name="first_name" type="text" class="block mt-1 w-full"
                        required autofocus />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                <!-- Last Name -->
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" name="last_name" type="text" class="block mt-1 w-full"
                        required />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                <!-- LRN -->
                <div>
                    <x-input-label for="LRN" :value="__('LRN')" />
                    <x-text-input id="LRN" name="LRN" type="number" class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('LRN')" class="mt-2" />
                </div>

                <!-- Year -->
                <div>
                    <x-input-label for="year" :value="__('Year')" />
                    <x-text-input id="year" name="year" type="number" class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('year')" class="mt-2" />
                </div>

                <!-- Section -->
                <div>
                    <x-input-label for="section" :value="__('Section')" />
                    <x-text-input id="section" name="section" type="text" class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('section')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" name="password" type="password" class="block mt-1 w-full"
                        required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                        class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-150 ease-in-out"
                        onclick="document.getElementById('createUserModal').classList.add('hidden')">
                        Cancel
                    </button>
                    <x-primary-button>{{ __('Create') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
    </div>

</x-admin-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all pagination links
        const paginationLinks = document.querySelectorAll('.pagination .page-item');

        // Loop through each link
        paginationLinks.forEach(link => {
            // Check if the link has the "active" class
            if (link.classList.contains('active')) {
                // Add your custom styles
                link.querySelector('.page-link').style.backgroundColor = '#1d4ed8'; // Blue background
                link.querySelector('.page-link').style.color = '#fff'; // White text
                link.querySelector('.page-link').style.fontWeight = 'bold'; // Bold text
                link.querySelector('.page-link').style.borderRadius = '8px'; // Rounded corners
            }
        });
    });
</script>
