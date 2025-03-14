<x-app-layout>
    <h1 class="mt-4">Gestão de Produtos</h1>
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Lista de Produtos</span>
                <button id="addNewBtn" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Adicionar Produto
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Search Input -->
                <div class="form-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar...">
                </div>

                <!-- Products Table -->
                <table id="productsTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Categorias</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated dynamically by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Placeholder -->
    <div id="modal-placeholder"></div>

    <x-slot name="scripts">
        @vite(['resources/js/dashboard/products.js'])
    </x-slot>
</x-app-layout>