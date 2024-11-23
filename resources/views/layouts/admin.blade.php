<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FullCalendar Stylesheet -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white font-sans">
    <!-- Session Message -->
    @if (Session::has('message'))
        <div class="fixed top-8 left-1/2 transform -translate-x-1/2 z-50 bg-green-200 text-green-900"
            x-data="{ open: true }" x-init="setTimeout(() => open = false, 3000)" x-show="open"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="px-6 py-4 rounded-full shadow-lg max-w-lg mx-auto text-base font-semibold">

            <div class="flex items-center justify-between space-x-4">
                <div class="flex items-center space-x-3">
                    <!-- Icon -->
                    <svg class="h-6 w-6 text-green-900" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>

                    <!-- Message Content -->
                    <p>{{ Session::get('message') }}</p>
                </div>

                <!-- Dismiss Button -->
                <button @click="open = false" class="text-green-900 focus:outline-none">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Navbar -->
    <header class="bg-white-600 shadow-lg h-20 flex items-center justify-between px-6 w-full">
        <div class="flex items-center">
            <!-- Logo -->
            <img src="{{ asset('images/white.png') }}" alt="Logo" class="h-12 w-12 object-cover rounded-full">

            <span class="ml-4 text-2xl font-bold text-gray-900">SALUS-ARMS</span>
        </div>
        <!-- Dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <div>{{ Auth::user()->first_name }}</div>
                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </header>

    <!-- Content Wrapper -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-80 h-screen bg-green-200 text-gray-900 flex-shrink-0 p-4">
            <!-- Logo -->
            <div class="shrink-0 flex items-center justify-center mb-6">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-900" />
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col space-y-4 items-center">
                <!-- Dashboard Link -->
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                    class="text-xl flex justify-center items-center text-white bg-green-500 hover:bg-green-700 px-10 py-2 border-1 border-gray-600 rounded-lg w-3/4">
                    {{ __('Dashboard') }}
                </x-nav-link>
                {{-- @role('admin')
                <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')"
                            class="text-xl flex justify-center items-center text-white bg-green-500 hover:bg-green-700 px-10 py-2 border-1 border-gray-600 rounded-lg w-3/4">
                    {{ __('Admin') }}
                </x-nav-link>
                @endrole
                @role('admin')
                <x-nav-link :href="route('admin.permissions.index')" :active="request()->routeIs('admin.permissions.index')"
                            class="text-xl flex justify-center items-center text-white bg-green-500 hover:bg-green-700 px-10 py-2 border-1 border-gray-600 rounded-lg w-3/4">
                    {{ __('Permissions') }}
                </x-nav-link>
                @endrole
                @role('admin')
                <x-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.index')"
                            class="text-xl flex justify-center items-center text-white bg-green-500 hover:bg-green-700 px-10 py-2 border-1 border-gray-600 rounded-lg w-3/4">
                    {{ __('Roles') }}
                </x-nav-link>
                @endrole --}}
                @role('admin')
                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')"
                        class="text-xl flex justify-center items-center text-white bg-green-500 hover:bg-green-700 px-10 py-2 border-1 border-gray-600 rounded-lg w-3/4">
                        {{ __('Users') }}
                    </x-nav-link>
                @endrole

                <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.index')"
                    class="text-xl flex justify-center items-center text-white bg-green-500 hover:bg-green-700 px-10 py-2 border-1 border-gray-600 rounded-lg w-3/4">
                    {{ __('Events') }}
                </x-nav-link>
                @role('admin')
                    <x-nav-link :href="route('admin.suggestions.index')" :active="request()->routeIs('admin.suggestions.index')"
                        class="text-xl flex justify-center items-center text-white bg-green-500 hover:bg-green-700 px-10 py-2 border-1 border-gray-600 rounded-lg w-3/4">
                        {{ __('Suggestions') }}
                    </x-nav-link>
                @endrole
            </nav>
        </aside>




        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col p-6 bg-gray-50">
            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
