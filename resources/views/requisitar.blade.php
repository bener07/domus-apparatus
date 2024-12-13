<x-app-layout>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Requisitar') }}
            </h2>
    
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Requisite o(s) equipamentos") }}
            </p>
        </header>
        
        <!-- Card Container -->
        <div class="container">
            <div class="row" id="productsGrid">
                <!-- Cards will be dynamically inserted here -->
            </div>
        </div>

    
  <slot name="scripts">
    @vite(['resources/js/user/requisitar.js'])
  </slot>
</x-app-layout>