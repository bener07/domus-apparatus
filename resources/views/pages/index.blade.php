<x-app-layout>
    <slot name="head">
        @vite([
            'resources/css/styles.css'
        ])
    </slot>

    <!-- Begin Page Content -->
    <div class="container-fluid app-center">

        <!-- Page Heading -->
        <div class="text-center my-4">
            <h1 class="display-4 text-gray-800">O que deseja fazer?</h1>
        </div>
        <div class="d-flex flex-wrap justify-content-center">
            <a href="/entregar" class="btn btn-primary btn-lg mx-4 my-3">
                Entregar
            </a>
            <a href="/requisitar" class="btn btn-secondary btn-lg mx-4 my-3">
                Requisitar
            </a>
        </div>
    </div>           
</x-app-layout>
