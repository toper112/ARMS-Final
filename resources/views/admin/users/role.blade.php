<x-admin-layout>
    <div class="py-2 pb-20 w-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">

                {{-- <!-- Back to Users Index -->
                <div class="flex mb-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-4 py-2 bg-green-700 hover:bg-green-500 text-white rounded-md transition duration-150 ease-in-out">
                        Users Index
                    </a>
                </div> --}}

                <!-- User Information Update Form -->
                <div class="flex flex-col p-4 bg-slate-100 text-gray-500">
                    <h2 class="text-xl font-bold mb-4">Update User Information</h2>
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <!-- First Name -->
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" />
                            <x-text-input id="first_name" name="first_name" type="text" class="block mt-1 w-full"
                                value="{{ old('first_name', $user->first_name) }}" required />
                            @error('first_name')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" name="last_name" type="text" class="block mt-1 w-full"
                                value="{{ old('last_name', $user->last_name) }}" required />
                            @error('last_name')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- LRN -->
                        <div>
                            <x-input-label for="LRN" :value="__('LRN')" />
                            <x-text-input id="LRN" name="LRN" type="number" class="block mt-1 w-full"
                                value="{{ old('LRN', $user->LRN) }}" required />
                            @error('LRN')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Year -->
                        <div>
                            <x-input-label for="year" :value="__('Year')" />
                            <x-text-input id="year" name="year" type="number" class="block mt-1 w-full"
                                value="{{ old('year', $user->year) }}" required />
                            @error('year')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Section -->
                        <div>
                            <x-input-label for="section" :value="__('Section')" />
                            <x-text-input id="section" name="section" type="text" class="block mt-1 w-full"
                                value="{{ old('section', $user->section) }}" required />
                            @error('section')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="block mt-1 w-full"
                                value="{{ old('email', $user->email) }}" required />
                            @error('email')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password (Optional) -->
                        {{-- <div>
                            <x-input-label for="password" :value="__('Password (leave blank if not changing)')" />
                            <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" />
                            @error('password')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div> --}}

                        <!-- Update Button -->
                        <div>
                            <x-primary-button>{{ __('Update User') }}</x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Reset Password Section -->
                <!-- Reset Password Section -->
                <div class="mt-6 p-4 bg-slate-100">
                    <h2 class="text-2xl font-semibold text-gray-500">Reset Password</h2>
                    <div class="flex space-x-2 mt-4 p-2">
                        <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md">
                                Reset Password
                            </button>
                        </form>
                    </div>
                </div>






                <!-- Roles Section -->
                <div class="mt-6 p-4 bg-slate-100">
                    <h2 class="text-2xl font-semibold text-gray-500">Roles</h2>
                    <div class="flex space-x-2 mt-4 p-2">
                        @if ($user->roles)
                            @foreach ($user->roles as $user_role)
                                <form class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md" method="POST"
                                    action="{{ route('admin.users.roles.remove', [$user->id, $user_role->id]) }}"
                                    onsubmit="return confirm('Are you sure you want to remove this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">{{ $user_role->name }}</button>
                                </form>
                            @endforeach
                        @endif
                    </div>
                    <div class="max-w-xl mt-6">
                        <form method="POST" action="{{ route('admin.users.roles', $user->id) }}"
                            onsubmit="return validateForm()">
                            @csrf
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Assign Role
                            </label>
                            <select id="role" name="role" autocomplete="role-name"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="" disabled selected>Select a role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <span id="roleError" class="text-red-400 text-sm hidden">Please select a role.</span>

                            <div class="sm:col-span-6 pt-5">
                                <button type="submit"
                                    class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white rounded-md">
                                    Assign
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Permissions Section -->
                {{-- <div class="mt-6 p-2 bg-slate-100">
                    <h2 class="text-2xl font-semibold text-white">Permissions</h2>
                    <div class="flex space-x-2 mt-4 p-2">
                        @if ($user->permissions)
                            @foreach ($user->permissions as $user_permission)
                                <form class="px-4 py-2 bg-red-500 hover:bg-red-700 text-gray-500 rounded-md"
                                    method="POST"
                                    action="{{ route('admin.users.permissions.revoke', [$user->id, $user_permission->id]) }}"
                                    onsubmit="return confirm('Are you sure you want to revoke this permission?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">{{ $user_permission->name }}</button>
                                </form>
                            @endforeach
                        @endif
                    </div>
                    <div class="max-w-xl mt-6">
                        <form method="POST" action="{{ route('admin.users.permissions', $user->id) }}">
                            @csrf
                            <div class="sm:col-span-6">
                                <label for="permission" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Permission</label>
                                <select id="permission" name="permission" autocomplete="permission-name"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white dark:bg-gray-800 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('permission')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                            <div class="sm:col-span-6 pt-5">
                                <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white rounded-md">Assign</button>
                            </div>
                        </form>
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
</x-admin-layout>

<script>
    function validateForm() {
        const roleSelect = document.getElementById('role');
        const roleError = document.getElementById('roleError');

        // Check if the selected value is empty
        if (!roleSelect.value) {
            roleError.classList.remove('hidden'); // Show error message
            roleSelect.classList.add('border-red-500'); // Add red border
            roleSelect.focus();
            return false; // Prevent form submission
        }

        // If valid, hide the error message
        roleError.classList.add('hidden');
        roleSelect.classList.remove('border-red-500');
        return true; // Allow form submission
    }
</script>
