<section>
    <div class="py-12">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold">Profile Information</h1>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                        onclick="openModal()">
                        Edit
                    </button>
                </div>
                <div class="mt-6 space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold">Name:</h2>
                        <p>{{ $user->first_name }} {{ $user->last_name }}</p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">LRN:</h2>
                        <p>{{ $user->LRN }}</p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Year:</h2>
                        <p>{{ $user->year }}</p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Section:</h2>
                        <p>{{ $user->section }}</p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Email:</h2>
                        <p>{{ $user->email }}</p>
                    </div>
                </div>
                <div class="mt-6">
                    <h2 class="text-lg font-semibold">Your QR Code:</h2>
                    <div class="mt-4">
                        {!! $qrCode !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-bold mb-4">Edit Profile</h2>
            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <!-- First Name -->
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full"
                        :value="old('first_name', $user->first_name)" required autofocus autocomplete="first_name" />
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>

                <!-- Last Name -->
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full"
                        :value="old('last_name', $user->last_name)" required autocomplete="last_name" />
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>

                <!-- LRN -->
                <div>
                    <x-input-label for="LRN" :value="__('LRN')" />
                    <x-text-input id="LRN" name="LRN" type="number" class="mt-1 block w-full"
                        :value="old('LRN', $user->LRN)" required autocomplete="LRN" />
                    <x-input-error class="mt-2" :messages="$errors->get('LRN')" />
                </div>

                <!-- Year -->
                <div>
                    <x-input-label for="year" :value="__('Year')" />
                    <x-text-input id="year" name="year" type="number" class="mt-1 block w-full"
                        :value="old('year', $user->year)" required autocomplete="year" />
                    <x-input-error class="mt-2" :messages="$errors->get('year')" />
                </div>

                <!-- Section -->
                <div>
                    <x-input-label for="section" :value="__('Section')" />
                    <x-text-input id="section" name="section" type="text" class="mt-1 block w-full"
                        :value="old('section', $user->section)" required autocomplete="section" />
                    <x-input-error class="mt-2" :messages="$errors->get('section')" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded"
                        onclick="closeModal()">Cancel</button>
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    function openModal() {
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
