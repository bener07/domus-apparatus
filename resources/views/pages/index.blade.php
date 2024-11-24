<x-app-layout>
    <slot name="head">
        @vite([
            'resources/css/styles.css'
        ])
    </slot>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">O que deseja fazer?</h1>
        </div>

            <!-- /.container-fluid -->
</x-app-layout>