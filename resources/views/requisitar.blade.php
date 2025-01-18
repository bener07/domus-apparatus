<x-app-layout>
    <section>
        @isDateChoosen
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Requisitar') }}
            </h2>
    
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Requisite o(s) equipamentos") }}
            </p>
        </header>
        @endisDateChoosen

        <!-- Card Container -->
        <div class="container">
            @isDateChoosen
            <div class="row" id="productsGrid">
                <!-- Cards will be dynamically inserted here -->
            </div>
            <div class="d-flex align-items-center justify-content-center flex-row-reverse w-100 text-right py-3 px-5" style="position: fixed; left: 0;bottom: 0;background-color: white;">
                <x-confirm-cart class="w-100"/>
            </div>

            @else
            <div class="container-fluid app-center d-flex flex-wrap justify-content-center py-4">
                <div class="container-wrapper border rounded">
                    <!-- First Div -->
                    <div id="firstDiv" class="sliding-div bg-success text-white d-flex align-items-center justify-content-center">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h1 class="card-title fs-4 text-primary">Como fazer uma requisição</h1>
                                <ol class="list-group list-group-numbered mb-3">
                                    <li class="list-group-item">Escolha a data para a recolha dos equipamentos e a data para a entrega.</li>
                                    <li class="list-group-item">Na página asseguir, selecione os equipamentos que deseja requisitar.</li>
                                    <li class="list-group-item">Confirme a sua requisição.</li>
                                    <li class="list-group-item">Espere autorização do administrador.</li>
                                </ol>
                                <p class="mb-2"><strong>Depois disso:</strong></p>
                                <ul class="list-group">
                                    <li class="list-group-item">A sua requisição será adicionada ao calendário da plataforma.</li>
                                    <li class="list-group-item">Receberá notificações por email com lembretes sobre as datas de recolha e entrega.</li>
                                </ul>
                            </div>
                            {{-- <button class="btn btn-primary mt-3">Escolher datas</button> --}}
                            <!-- Toggle Button -->
                            <button id="toggleButton" class="btn btn-primary w-100 mb-0">Escolher Datas</button>
                        </div>
                    </div>
            
                    <!-- Second Div -->
                    <div id="secondDiv" class="sliding-div text-white d-flex align-items-center justify-content-center">
                        <form action="{{ route('request.products') }}" id="dateForm" method="POST" class="mt-4">
                            @csrf
                            <div class="d-flex justify-content-evenly align-items-center">
                                <!-- Initial Date -->
                                <div class="mb-3 mx-4">
                                    <label for="start_date" class="form-label">Data de requisição</label>
                                    <input type="date" id="start_date" name="start" class="form-control" required placeholder="Selecione a data inicial">
                                </div>
                                <i class="bi bi-arrow-right fs-1" style="color: black"></i>
                                <!-- Final Date -->
                                <div class="mb-3 mx-4">
                                    <label for="end_date" class="form-label">Data de entrega</label>
                                    <input type="date" id="end_date" name="end" class="form-control" required placeholder="Selecione a data final">
                                </div>
                                
                            </div>
                            <button id="submitForm" class="btn btn-primary d-none w-100">Submeter Datas</button>
                            <!-- Submit Button -->
                        </form>
                    </div>
                </div>
            </div>
            @endisDateChoosen
        </div>
    </section>
    
  <slot name="scripts">
    @vite(['resources/js/user/requisitar.js'])
    <script>
        const submitBtn = document.getElementById('submitForm');
        const btn = document.getElementById('toggleButton');
        btn.addEventListener('click', function () {
            const firstDiv = document.getElementById('firstDiv');
            const secondDiv = document.getElementById('secondDiv');
            firstDiv.style.transform = 'translateX(-100%)';
            secondDiv.style.transform = 'translateX(0)';
            submitBtn.classList.remove('d-none');
        });

        submitBtn.addEventListener('click', function () {
            document.getElementById('dateForm').submit();
        });


    </script>
  </slot>
</x-app-layout>