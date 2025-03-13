<x-app-layout>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="usersTable">
                    <div>
                        <button type="button" class="btn btn-primary" id="addNewUser">Adicionar Utilizador</button>
                    </div>
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">id</th>
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