<x-app-layout>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <div id="loadingWheel" style="display: block; background:white;width:100%;" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <table class="table table-striped table-bordered" id="productsTable">
                    <div>
                        <button type="button" class="btn btn-primary" id="addNewBtn">Adicionar Utilizador</button>
                    </div>
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Produto</th>
                            <th scope="col">Categorias</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css">

    <x-slot name="scripts">
        <!-- DataTables JS -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
        @vite(['resources/js/dashboard/products.js'])
    </x-slot>
    
</x-app-layout>