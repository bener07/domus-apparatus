<x-app-layout>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <div id="loadingWheel" style="display: block; background:white;width:100%;" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <table class="table table-striped table-bordered" id="usersTable">
                    <div>
                        <button type="button" class="btn btn-primary" id="addNewBtn">Adicionar Utilizador</button>
                    </div>
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Perfil</th>
                            <th scope="col">Email</th>
                            <th scope="col">Departamento</th>
                            <th scope="col">Cargo</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        @vite(['resources/js/dashboard/users.js'])
    </x-slot>
    
</x-app-layout>