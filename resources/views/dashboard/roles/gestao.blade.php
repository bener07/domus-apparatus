<x-app-layout>
    <h1 class="mt-4">Gestão de Cargos</h1>
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Lista de Cargos</span>
                <button id="addNew-role" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Adicionar Novo Cargo
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Search Input -->
                <div class="form-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar...">
                </div>

                <!-- Roles Table -->
                <table id="rolesTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <!-- Data will be populated dynamically by DataTables --> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Placeholder -->
    <div id="modal-placeholder"></div>
    

    <x-slot name="scripts">
        
    </x-slot>
    
</x-app-layout>