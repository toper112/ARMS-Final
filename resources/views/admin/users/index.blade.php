<x-admin-layout>
    <div class="py-12 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('admin.users.export') }}"
                    class="px-4 py-2 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 transition duration-150">Export
                    Users</a>
                <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data"
                    class="inline">
                    @csrf
                    <label class="block text-gray-700 font-medium mb-1" for="csv_file">Import CSV</label>
                    <input type="file" name="csv_file"
                        class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 mb-2"
                        required>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 transition duration-150">Import
                        Users</button>
                </form>



                <!-- Create User Button -->
                <div class="mb-4 flex justify-end">
                    <button
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 transition duration-150 ease-in-out"
                        onclick="document.getElementById('createUserModal').classList.remove('hidden')">
                        Create User
                    </button>
                </div>

                <!-- Create User Modal -->
                <div id="createUserModal"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-1/3">
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
                                <x-text-input id="LRN" name="LRN" type="number" class="block mt-1 w-full"
                                    required />
                                <x-input-error :messages="$errors->get('LRN')" class="mt-2" />
                            </div>

                            <!-- Year -->
                            <div>
                                <x-input-label for="year" :value="__('Year')" />
                                <x-text-input id="year" name="year" type="number" class="block mt-1 w-full"
                                    required />
                                <x-input-error :messages="$errors->get('year')" class="mt-2" />
                            </div>

                            <!-- Section -->
                            <div>
                                <x-input-label for="section" :value="__('Section')" />
                                <x-text-input id="section" name="section" type="text" class="block mt-1 w-full"
                                    required />
                                <x-input-error :messages="$errors->get('section')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="block mt-1 w-full"
                                    required />
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

                <!-- User Table -->
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
                                                Name
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                LRN
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Year
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Section
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Actions
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
                                                            <lord-icon src="https://cdn.lordicon.com/dicvhxpz.json"
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
