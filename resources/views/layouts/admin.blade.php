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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <aside class="w-80 bg-gray-800 text-white flex-shrink-0 p-4">
            <!-- Logo -->
            <div class="shrink-0 flex items-center mb-6">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-white" />
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col space-y-4">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
                @role('admin')
                    <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                        {{ __('Admin') }}
                    </x-nav-link>
                @endrole
                @role('admin')
                    <x-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.index')">
                        {{ __('Roles') }}
                    </x-nav-link>
                @endrole
                @role('admin')
                    <x-nav-link :href="route('admin.permissions.index')" :active="request()->routeIs('admin.permissions.index')">
                        {{ __('Permissions') }}
                    </x-nav-link>
                @endrole
                @role('admin')
                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                        {{ __('Users') }}
                    </x-nav-link>
                @endrole

                {{-- sample two auth --}}
                {{-- @role('admin|officer')
                    <x-nav-link :href="route('admin.permissions.index')" :active="request()->routeIs('admin.permissions.index')">
                        {{ __('Permissions') }}
                    </x-nav-link>
                @endrole --}}
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1">
            <!-- Top Navigation (Dropdown) -->
            <header class="bg-gray-800 p-4 flex justify-between items-center">
                <div class="flex items-center">
                    {{-- <span class="text-xl text-white">{{ Auth::user()->name }}</span> --}}
                </div>
                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
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

            <!-- Page Content -->
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
