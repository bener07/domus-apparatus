<x-app-layout>
    <h1 class="mt-4">Gestão de Turmas</h1>
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Lista de Turmas</span>
                <button id="addNew-classroom" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Adicionar Nova Turma
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Search Input -->
                <div class="form-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar...">
                </div>

                <!-- Classrooms Table -->
                <table id="classroomsTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome da Turma</th>
                            <th>Capacidade</th>
                            <th>Localização</th>
                            <th>Departamento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data will be populated dynamically by DataTables --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Placeholder -->
    <div id="modal-placeholder"></div>
    
</x-app-layout>