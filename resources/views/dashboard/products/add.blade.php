<x-app-layout>
    <x-slot name="header">
        Adicionar Utilizadores
    </x-slot>
    <style>

.img-pr{
    margin-left: 85px;
}
.img-thumbnail {
    width: 150px; /* Increased width for clear rectangle shape */
    height: 100px; /* Proportional height for a rectangle */
    margin: 10px 0;
    cursor: pointer;
    transition: transform 0.3s ease;
    border-radius: 0; /* Ensures no rounding */
}

.thumbnail-container {
    width: 100%;
    display: flex;
    justify-content: flex-start;
    align-items: center;
}

#mainImage {
    width: 100%;
    height: 400px;
    object-fit: cover;
    transition: filter 1s ease;
    margin: 0;
}

.image_container {
    position: relative;
    overflow: hidden;
    display: flex;
    width: 50%;
    justify-content: center;
    align-items: center;
    margin-left: 90px;
}

.image_container:hover img {
    filter: opacity(0.7) blur(2px);
    cursor: pointer;
}

#featured {
    transition: filter 0.2s ease-in-out;
    object-fit: cover;
    object-position: 50% 50%;
    width: 300px; /* Clearly rectangular */
    height: 200px; /* Clearly rectangular */
    border-radius: 0; /* Ensures no rounding */
}

.overlay-image {
    position: absolute;
    width: 100px; /* Set a specific size for the overlay image */
    height: auto;
    display: none;
    filter: none !important;
}

.image_container:hover .overlay-image {
    display: block;
}

#role-list {
    border-radius: 10px;
    padding: 0px;
}


    </style>
    <form id="addProductForm" enctype="multipart/form-data" class="p-4 border rounded shadow">
        <div class="row">
            <div class="row col-lg-8">
                <!-- Nome -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome" required>
                    <div class="invalid-feedback">Por favor, insira o nome.</div>
                </div>
                
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Detalhes do produto <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Detalhes do produto" required>
                    <div class="invalid-feedback">Por favor, insira um email válido.</div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Imagem de utilizador -->
                <div class="mb-3">
                    <label for="productImage" class="form-label img-pr">Imagens dos Produtos</label>
                    <input type="file" class="form-control" style="display:none;" id="productImage" name="avatar" accept="image/*">
                    <div class="col image_container" id="image-container">
                        <img src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg" alt="Featured Image" id="featured">
                        <img src="https://cdn-icons-png.flaticon.com/512/84/84380.png" alt="Overlay" class="overlay-image">
                    </div>
                </div>
            </div>
    
            <div class="row">
                
                <!-- Papel -->
                <div class="mb-3 col-lg-6">
                    Categorias
                    <table id="role-list" class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>professor</td>
                                <td>action</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding: 0px">
                                    <div class="d-flex flex-row">
                                        <select class="form-select col-lg-8" id="rolesSelection" name="roles">
                                            <option value="" disabled selected>Selecione uma categoria (pre-definição: {{ config('DEFAULT_TAG', 'default') }})</option>
                                        </select>
                                        <button id="addRole" type="button" class="btn btn-primary col-lg-4" style="border-radius: 0 10px 10px 0px;">Adicionar Cargo</button>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- Botões -->
            <div class="d-flex justify-content-between ">
                <button type="reset" class="btn btn-secondary">Limpar</button>
                <button type="submit" id="addBtn" class="btn btn-primary">Adicionar</button>
            </div>
        </div>
    </form>
    <x-slot name="scripts">
        @vite(['resources/js/dashboard/products.js'])
    </x-slot>
</x-app-layout>
