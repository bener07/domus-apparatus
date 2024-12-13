<x-app-layout>

    <!-- Begin Page Content -->
    <div class="container-fluid app-center d-flex flex-wrap justify-content-center">
        <h1 class="fs-1">
            Bem-vindo {{ auth()->user()->name }}!
        </h1>
        <div class="d-flex flex-row">
            <a href="/requisitar" class="btn btn-primary btn-lg mx-4 my-3 big-btn">
                Requisitar
            </a>
            <a href="/entregar" class="btn btn-secondary btn-lg mx-4 my-3 big-btn">
                Entregar
            </a>
        </div>
    </div>           
</x-app-layout>
