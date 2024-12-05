<x-app-layout>
    <x-slot name="header">
        Adicionar Utilizadores
    </x-slot>
    <form id="addUserForm" enctype="multipart/form-data">
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
        
        <!-- Papel -->
        <div class="mb-3">
            <label for="role" class="form-label">Papel</label>
            <select class="form-select" id="role" name="role">
                <option value="" disabled selected>Selecione um papel</option>
            </select>
        </div>
        
        <!-- Imagem de utilizador -->
        <div class="mb-3">
            <label for="userImage" class="form-label">Imagem de Utilizador</label>
            <input type="file" class="form-control" id="userImage" name="userImage" accept="image/*">
        </div>
        
        <!-- Botões -->
        <div class="d-flex justify-content-between">
            <button type="reset" class="btn btn-secondary">Limpar</button>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </div>
    </form>
</x-app-layout>
