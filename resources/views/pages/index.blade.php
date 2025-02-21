<x-app-layout>
    <!-- Begin Page Content -->
    <div class="container-fluid d-flex flex-column align-items-center justify-content-center text-center py-5" style="min-height: calc(100vh - 172px);">
        <h1 class="fs-1 mb-4">
            Bem-vindo {{ auth()->user()->name }}!
        </h1>
        <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3">
            <a href="/requisitar" class="btn btn-primary btn-lg px-5">
                Requisitar
            </a>
            <a href="/entregar" class="btn btn-secondary btn-lg px-5">
                Entregar
            </a>
        </div>
    </div>
</x-app-layout>
