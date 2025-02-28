<x-app-layout>
    <section>
      <header data-bs-toggle="collapse" href="#user-confirmed" class="card w-100 p-3 m-2" aria-expanded="true">
        <div class="d-flex align-items-center justify-content-between">
          <a class="card-title text-lg font-medium text-gray-900 dark:text-gray-100 fw-bold">
              {{ __('Requisições Confirmadas') }}
          </a>
  
          <p class="card-text mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ __("Ao entregar os equipamentos aqui dispostos, apresente o código qr da requisição") }}
          </p>
          <i class="bi bi-arrow-down"></i>
        </div>
      </header>
      <div id="user-confirmed" class="row collapse show">
        <p class="w-100 text-center">Sem requisições confirmadas, fale com algum dos administradores caso seja urgente!</p>
      </div>
    </section>
    <section>
      <header data-bs-toggle="collapse" href="#user-requests" class="card w-100 p-3 m-2">
        <div class="d-flex align-items-center justify-content-between">
          <a class="card-title text-lg font-medium text-gray-900 fw-bold dark:text-gray-100">
              {{ __('Pedidos de Requisições') }}
          </a>

          <p class="card-text mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ __("Todas as requisições precisam de ser autorizadas pelos administradores") }}
          </p>
          <i class="bi bi-arrow-down"></i>
        </div>
      </header>
      <div id="user-requests" class="row collapse">
        <p class="w-100 text-center">Sem pedidos de requisição</p>
      </div>
    </section>
    <section>
      <header data-bs-toggle="collapse" href="#user-denied" class="card w-100 p-3 m-2">
        <div class="d-flex align-items-center justify-content-between">
          <h2 class="card-title text-lg font-medium text-gray-900 fw-bold dark:text-gray-100">
              {{ __('Requisições Recusadas') }}
          </h2>
  
          <p class="card-text mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ __("Pode aqui ver os equipamentos que foram recusados pelos administradores") }}
          </p>
          <i class="bi bi-arrow-down"></i>
        </div>
      </header>
      <div id="user-denied" class="row collapse">
        <p class="w-100 text-center">Sem requisiçõse anuladas</p>
      </div>
    </section>
    <x-slot name="scripts">
      @vite(['resources/js/user/entregar.js'])
    </x-slot>
</x-app-layout>
 