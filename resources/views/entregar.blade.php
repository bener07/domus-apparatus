<x-app-layout>
    <section>
      <header>
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 fw-bold">
              {{ __('Requisições confirmadas') }}
          </h2>
  
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ __("Ao entregar os equipamentos aqui dispostos, apresente o código qr da requisição") }}
          </p>
      </header>
      <div id="user-confirmed" class="row">
        <p class="w-100 text-center">Sem requisições confirmadas, fale com algum dos administradores caso seja urgente!</p>
      </div>
    </section>
    <section>
      <header>
          <h2 class="text-lg font-medium text-gray-900 fw-bold dark:text-gray-100">
              {{ __('Pedidos de requisições') }}
          </h2>
  
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ __("Todas as requisições precisam de ser autorizadas pelos administradores") }}
          </p>
      </header>
      <div id="user-requests" class="row">
        <p class="w-100 text-center">Sem pedidos de requisição</p>
      </div>
    </section>
    <section>
      <header>
          <h2 class="text-lg font-medium text-gray-900 fw-bold dark:text-gray-100">
              {{ __('Requisições Recusadas') }}
          </h2>
  
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ __("Pode aqui ver os equipamentos que foram recusados pelos administradores") }}
          </p>
      </header>
      <div id="user-denied" class="row">
        <p class="w-100 text-center">Sem requisiçõse anuladas</p>
      </div>
    </section>
    <x-slot name="scripts">
      @vite(['resources/js/user/entregar.js'])
    </x-slot>
</x-app-layout>
 