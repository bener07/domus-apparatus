<x-guest-layout>
    <section>
        <div class="col-xl-12 col-lg-12 col-md-9 p-4">
            <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('First Setup') }}
                </h2>
            </header>
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Roadmap Section -->
                    <div class="col-md-4">
                        <div class="list-group roadmap-container" id="roadmap">
                            <a href="#" class="list-group-item list-group-item-action active" data-form="form1">1. Inicio</a>
                            <a href="#" class="list-group-item list-group-item-action" data-form="form2">2. Administrador</a>
                            <a href="#" class="list-group-item list-group-item-action" data-form="form3">3. Escola</a>
                            <a href="#" class="list-group-item list-group-item-action" data-form="form4">4. Utilizadores</a>
                            <a href="#" class="list-group-item list-group-item-action" data-form="form5">5. Finalizar</a>
                        </div>
                    </div>

                    <!-- Forms Section -->
                    <div class="col-md-8" style="width: 80vh">
                        <div class="card shadow-sm p-4 consistent-height">
                            <!-- Form 1 -->
                            <form id="form1" class="setup-form active" style="display: block;">
                                <h3>Primeira Instalação</h3>
                                <div class="mb-3">
                                    <p class="text-break">Antes de começar, precisamos de alguns detalhes para personalizar a plataforma.
                                        Aqui, pode adicionar o logotipo da sua escola ou instituição e 
                                        preencher informações básicas sobre como irá utilizar a plataforma.
                                        Esta plataforma foi criada para facilitar a gestão de equipamentos oferecidos pelo projeto LED,
                                        de forma a agilizar processo e o requisito. Vamos guiá-lo por cada passo para garantir
                                        que tudo está pronto para uso.<br>
                                        Clique em "Próximo" para continuar! 🚀
                                    </p>
                                </div>
                                <button type="button" class="btn btn-primary next-form">Next</button>
                            </form>

                            <!-- Form 2 -->
                            <form id="form2" class="setup-form active" style="display: none;">
                                <h3>Administrador</h3>
                                <p>Campos com <sup>*</sup> são obrigatórios</p>
                                <div class="mb-3">
                                    <x-input-label for="admin">Utilizador</x-input-label>
                                    <x-text-input placeholder="Utilizador" type="text" id="admin" class="form-control" />
                                </div>
                                <div class="mb-3">
                                    <x-input-label for="email">Email <sup>*</sup></x-input-label>
                                    <x-text-input id="email" placeholder="admin@example.com"/>
                                </div>
                                <div class="mb-3">
                                    <x-input-label for="password">Password <sup>*</sup></x-input-label>
                                    <x-text-input id="password" placeholder="Nova Password" type="password" class="form-control" />
                                </div>
                                <div class="mb-3">
                                    <x-input-label for="confirmPassword">Confirmar Password <sup>*</sup></x-input-label>
                                    <x-text-input id="confirmarPassword" placeholder="Confirmar Password" type="password" class="form-control"/>
                                </div>
                                <div class="mb-3">
                                    <input id="files" style="visibility:hidden;" type="file">
                                    <div class="add-image-container">
                                        <label for="image-upload" class="add-image-label">
                                            <div class="add-image-content">
                                                <i class="bi bi-image" style="font-size: 2rem; color: #4e73df;"></i>
                                                <p>Foto</p>
                                            </div>
                                        </label>
                                        <input type="file" id="image-upload" accept="image/*" style="display: none;">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary prev-form">Anterior</button>
                                <button type="submit" class="btn btn-primary next-form">Seguinte</button>
                            </form>

                            <!-- Form 3 -->
                            <form id="form3" class="setup-form active" style="display: none;">
                                <h3>Configurações da Escola</h3>
                                <p>Todos os campos marcados com <sup style="color:red">*</sup> são obrigatórios</p>
                                <div class="mb-3">
                                    <x-input-label for="logo">Logotipo da Escola <sup>*</sup></x-input-label>
                                    <div class="add-image-container">
                                        <label for="image-upload" class="add-image-label">
                                            <div class="add-image-content">
                                                <i class="bi bi-image" style="font-size: 2rem; color: #4e73df;"></i>
                                                <p>Foto</p>
                                            </div>
                                        </label>
                                        <input type="file" id="image-upload" accept="image/*" style="display: none;">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <x-input-label for="schoolName">Nome da Escola <sup>*</sup></x-input-label>
                                    <x-text-input placeholder="Escola Secundária de Cima" type="text" id="schoolName" class="form-control" required/>
                                </div>
                                <div class="mb-3">
                                    <x-input-label for="address">Morada da Escola <sup>*</sup></x-input-label>
                                    <x-text-input id="address" placeholder="Password" type="password" class="form-control" required />
                                </div>
                                <div class="mb-3">
                                    <x-input-label for="phone">Telefone da Escola <sup>*</sup></x-input-label>
                                    <x-text-input id="phone" placeholder="Confirmar Password" type="password" class="form-control" required/>
                                </div>
                                <button type="button" class="btn btn-secondary prev-form">Anterior</button>
                                <button type="submit" class="btn btn-primary next-form">Seguinte</button>
                            </form>

                            <!-- Form 4 -->
                            <form id="form4" class="setup-form active" style="display: none;">
                                <h3>Admin Info</h3>
                                <div class="mb-3">
                                    <x-input-label for="logo" :value="__('Utilizador')" />
                                    <x-text-input placeholder="Utilizador" type="text" class="form-control" />
                                </div>
                                <div class="mb-3">
                                    <x-text-input placeholder="Password" type="password" class="form-control" />
                                </div>
                                <div class="mb-3">
                                    <x-text-input placeholder="Confirmar Password" type="password" class="form-control" />
                                </div>
                                <button type="button" class="btn btn-secondary prev-form">Anterior</button>
                                <button type="submit" class="btn btn-primary next-form">Seguinte</button>
                            </form>

                            <!-- Form 5 -->
                            <form id="form5" class="setup-form" style="display: none;">
                                <h3>Finalizar configurações</h3>
                                <p>Reveja as configurações e finalize a instalação.</p>
                                <button type="button" class="btn btn-secondary prev-form">Anterior</button>
                                <button type="submit" class="btn btn-success">Terminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        html, body {
            height: 100%;
            margin: 0px;
        }
        .add-image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            width: 100%;
            max-width: 200px;
            height: 100px;
            margin: 20px auto;
            background-color: #f9fafb;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }
        
        .add-image-container:hover {
            background-color: #eef2f7;
            border-color: #4e73df;
        }
        
        .add-image-label {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: #6b7280;
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            cursor: pointer;
        }
        
        .add-image-content:hover {
            color: #4e73df;
        }


        /* Consistent height for the form container */
        .consistent-height {
            height: 450px; /* Adjust as needed */
            max-height: 100%;
            overflow-y: auto;
        }

        /* Roadmap styling */
        #roadmap {
            height: 100%; /* Ensures full height alignment */
            display: flex;
            flex-direction: column;
        }

        #roadmap .list-group-item.active {
            background-color: #4e73df; /* Custom blue for active */
            color: white;
            border: none;
        }
    </style>
    <x-slot name="scripts">
        <script>
        $('#form2').on('submit', function(event){
            event.preventDefault();
            console.log('Hello world!');
        });
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('.setup-form');
            const roadmapItems = document.querySelectorAll('#roadmap .list-group-item');
            let currentFormIndex = 0;

            function showForm(index) {
                forms.forEach((form, i) => form.style.display = i === index ? 'block' : 'none');
                roadmapItems.forEach((item, i) => item.classList.toggle('active', i === index));
                currentFormIndex = index;
            }

            document.querySelectorAll('.next-form').forEach(button => {
                button.addEventListener('click', () => showForm(currentFormIndex + 1));
            });

            document.querySelectorAll('.prev-form').forEach(button => {
                button.addEventListener('click', () => showForm(currentFormIndex - 1));
            });

            roadmapItems.forEach((item, index) => {
                item.addEventListener('click', () => showForm(index));
            });
        }); 
        </script>
    </x-slot>
</x-guest-layout>
