<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-blue dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("I'm Hosting this parties")}}
                    <div id="events">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        @vite(['resources/js/dashboard.js'])
    </x-slot>
</x-app-layout>