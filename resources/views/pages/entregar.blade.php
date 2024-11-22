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
    
        <div class="row">
            <div class="col-sm-4 mb-3 mb-sm-0">
              <div class="card">
                <img src="/img/talmao.jpg" class="card-img-top" alt="">
                <div class="card-body">
                  <h5 class="card-title">laptop</h5>
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                  <button type="button" class="btn btn-success">Entregar</button>
                  <button type="button" class="btn btn-warning">Danificado</button>
                </div>
              </div>
            </div>
    
</x-app-layout>
