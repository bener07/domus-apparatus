<x-app-layout>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Entregar') }}
            </h2>
    
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Entregue o(s) equipamentos") }}
            </p>
        </header>
        <div id="user-products" class="row"></div>
    <x-slot name="scripts">
      @vite(['resources/js/user/entregar.js'])
    </x-slot>
</x-app-layout>
