<x-app-layout>
    <section>
        @isDateChoosen
        <header class="text-center mb-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Requisitar') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Requisite o(s) equipamentos") }}
            </p>
            <input type="text" name="search" placeholder="Pesquisar">
        </header>
        @endisDateChoosen
    
        <!-- Card Container -->
        <div class="container-fluid">
            @isDateChoosen
            <div class="row g-3" id="productsGrid">
                <!-- Cards dynamically inserted here -->
            </div>
            <div style="width: 100%; height: 100px;"></div>
            @else
            <div class="container-fluid d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container-wrapper border rounded p-3 w-100" style="max-width: 600px;">
                    <!-- First Div -->
                    <div id="firstDiv" class="sliding-div bg-success text-white d-flex flex-column align-items-center justify-content-center p-4">
                        <div class="card shadow-sm w-100">
                            <div class="card-body">
                                <h1 class="card-title fs-5 text-primary text-center">Como fazer uma requisição</h1>
                                <ol class="list-group list-group-numbered mb-3">
                                    <li class="list-group-item">Escolha a data para a recolha e entrega dos equipamentos.</li>
                                    <li class="list-group-item">Na página seguinte, selecione os equipamentos desejados.</li>
                                    <li class="list-group-item">Confirme sua requisição.</li>
                                    <li class="list-group-item">Aguarde a autorização do administrador.</li>
                                </ol>
                                <p class="mb-2"><strong>Depois disso:</strong></p>
                                <ul class="list-group">
                                    <li class="list-group-item">A requisição será adicionada ao calendário da plataforma.</li>
                                    <li class="list-group-item">Você receberá notificações por email com lembretes das datas.</li>
                                </ul>
                            </div>
                            <!-- Toggle Button -->
                            <button id="toggleButton" class="btn btn-primary w-100">Escolher Datas</button>
                        </div>
                    </div>
    
                    <!-- Second Div -->
                    <div id="secondDiv" class="sliding-div text-white d-flex flex-column align-items-center justify-content-center p-4">
                        <form action="{{ route('request.products') }}" id="dateForm" method="POST" class="w-100">
                            @csrf
                            <div class="row g-3">
                                <!-- Initial Date -->
                                <div class="col-12 col-md-5">
                                    <label for="start" class="form-label">Data de requisição</label>
                                    <input type="date" id="start" class="form-control" required>
                                    <label for="start_time">Hora de requisição</label>
                                    <input type="time" id="start_time" name="start_time" class="form-control" required>

                                    <input type="datetime-local" id="start_date" name="start" class="form-control d-none">
                                </div>
                                <div class="col-12 col-md-2 text-center d-flex align-items-center justify-content-center">
                                    <i class="bi bi-arrow-right fs-2 text-dark"></i>
                                </div>
                                <!-- Final Date -->
                                <div class="col-12 col-md-5">
                                    <label for="end" class="form-label">Data de entrega</label>
                                    <input type="date" id="end" class="form-control" required>
                                    <label for="end_time">Hora de Entrega</label>
                                    <input type="time" id="end_time" name="end_time" class="form-control"required>
                                    
                                    <input type="datetime-local" id="end_date" name="end" class="form-control d-none">
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <button id="submitForm" class="btn btn-primary mt-3 w-100 d-none">Submeter Datas</button>
                        </form>
                    </div>
                </div>
            </div>
            @endisDateChoosen
        </div>
    </section>

    <x-slot name="scripts">
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
                event.preventDefault();
                let startDate = $('#start').val();
                let startTime = $('#start_time').val();

                let endDate = $('#end').val();
                let endTime = $('#end_time').val();

                if(startDate && startTime){
                    let dateTime = startDate + 'T' + startTime;
                    $('#start_date').val(dateTime);
                    console.log(dateTime);
                }

                if (endDate && endTime){
                    let dateTime = endDate + 'T' + endTime;
                    $('#end_date').val(dateTime);
                    console.log(dateTime);
                }
                document.getElementById('dateForm').submit();
            });
        </script>
    </x-slot>
</x-app-layout>
