<x-app-layout>
    <x-slot name="header">
        Adicionar Utilizadores
    </x-slot>
    <style>
        .img-thumbnail {
            width: 50px;
            height: 50px;
            margin: 10px 0;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .thumbnail-container {
            width: 100%;
            display: flex;
            justify-content: flex-start;
            align-items: center;
    
        }
        #mainImage{
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: filter 1s ease;
            margin: 0;
        }

        .image_container{
            position: relative;
            overflow: hidden;
            display: flex;
            width: 50%;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
        }

        .image_container:hover img{
            filter: opacity(0.7) blur(2px) url("");
            cursor: pointer;
        }
        #featured{
            transition: filter 1s ease-in-out;
            object-fit: cover;
            object-position: 50% 50%;
            width: 200px;
            height: 200px;
            border-radius: 100px; 
        }
        .overlay-image {
            position: absolute;
            width: 100px; /* Set a specific size for the overlay image */
            height: auto;
            display: none;
            filter: none !important;
        }
        .image_container:hover .overlay-image{
            display: block;
        }
        #role-list{
            border-radius: 10px;
            padding: 0px;
        }
    </style>
    Caso tenha um domínio google ou microsoft é possível sincronizar os utilizadores com a plataforma
    <div class="text-center w-full d-flex justify-content-center my-4">
        <button class="btn btn-outline-primary d-flex align-items-center gap-2 mx-5 btn-google">
            <i class="fab fa-google"></i> Sincronizar com domínio Google
        </button>
        
        <!-- Microsoft Button -->
        <button class="btn btn-outline-secondary d-flex align-items-center gap-2 mx-5 btn-microsoft">
            <i class="fab fa-windows"></i> Sincronizar com domínio Microsoft
        </button>
    </div>
    <form id="addUserForm" enctype="multipart/form-data" class="p-4 border rounded shadow">
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
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite o email" required>
                    <div class="invalid-feedback">Por favor, insira um email válido.</div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Imagem de utilizador -->
                <div class="mb-3">
                    <label for="userImage" class="form-label">Imagem de Utilizador</label>
                    <input type="file" class="form-control" style="display:none;" id="userImage" name="avatar" accept="image/*">
                    <div class="col image_container" id="image-container">
                        <img src="/storage/images/avatar.png" alt="Featured Image" id="featured">
                        <img src="https://cdn-icons-png.flaticon.com/512/84/84380.png" alt="Overlay" class="overlay-image">
                    </div>
                </div>
            </div>
    
            <div class="row">
                <!-- Departamento -->
                <div class="mb-3 col-lg-6">
                    Departamentos
                    <table id="department-list" class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Departamento</td>
                                <td>action</td>
                            </tr>
                            <tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding: 0px">
                                    <div class="d-flex flex-row">
                                        <select class="form-select col-lg-8" id="departmentSelection" name="departments">
                                            <option value="" disabled selected>Selecione departamento (pre-definição: {{ config('DEFAULT_DEPARTMENT', 'base') }})</option>
                                        </select>
                                        <button id="addDepartment" type="button" class="btn btn-primary col-lg-4" style="border-radius: 0 10px 10px 0px;">Adicionar departamento</button>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Papel -->
                <div class="mb-3 col-lg-6">
                    Cargos do utilizador
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
                                            <option value="" disabled selected>Selecione um cargo (pre-definição: {{ config('DEFAULT_ROLE', 'professor') }})</option>
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
        @vite(['resources/js/dashboard/users.js'])
    </x-slot>
</x-app-layout>
