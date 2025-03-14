import { Products } from '../utilities/admin_products';
import { SwalDialog } from '../components/dialog';
import { DataTableManager } from '../components/tables';
import { Modal } from '../components/manager';

const MAX_DESCRIPTION_LENGTH = 50;

function truncatedText(text) {
    return text.length > MAX_DESCRIPTION_LENGTH ? text.substring(0, MAX_DESCRIPTION_LENGTH) + '...' : text;
}

const productsTableManager = new DataTableManager('productsTable', {
    getData: (data, callback) => {
        Products.getProducts(data, callback);
    },
    columns: [
        { data: "id" },
        { 
            data: null,
            render: (data, type, row) => {
                return `<div class="d-flex align-items-center">
                    <img src="${row.featured_image}" alt="Avatar" width="70" height="70" class="me-2 product-image">
                    <div class="d-flex flex-column">
                        <span class="fs-4">${row.name}</span>
                        <span class="fw-lighter">${truncatedText(row.details)}</span>
                    </div>
                </div>`;
            },
            title: "Produto"
        },
        { data: "quantity", title: "Quantidade" },
        { data: "tags", title: "Categorias" },
        {
            data: null,
            render: (data, type, row) => {
                return `<button class="btn btn-danger btn-sm eliminar-btn" data-id="${row.id}">Eliminar</button>
                         <button class="btn btn-warning btn-sm editar-btn" data-id="${row.id}" data-product='${JSON.stringify(row)}'>Editar</button>`;
            },
            title: "Ações"
        }
    ],
    onDelete: (id) => {
        eliminarProduto(id);
    },
    onEdit: (id, product) => {
        editarProduto(id, product);
    }
});

export function eliminarProduto(id){
    SwalDialog.warning(
        'Irá eliminar o produto selectionado',
        'Os registos de requisição irão contiuar dispoivéis',
        () => {
            Products.deleteProduct(id).then(() => {
                SwalDialog.success(
                    'Produto eliminado com sucesso!',
                    'Os registos de requisição continuaram disponivéis',
                    () => productsTableManager.reload(),
                    () => productsTableManager.reload()
                );
            });
        },
        () => {},
        {
            showCancelButton: true,
            confirmButtonText: 'Eliminar!',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }
    );
}

$(document).ready(() => {
    if ($("#productsTable").length > 0) {
        productsTableManager.init();

        $('#searchInput').on('input', function() {
            productsTableManager.table.search($(this).val()).draw();
        });
    }
    $('#addNewBtn').on('click', function(){
        addNewProduct();
    });

    // Other event bindings...
});



function editarProduto(productId, product) {
    // Fetch tags for the category dropdown
    $.ajax({
        url: '/api/tags',
        method: 'GET',
        dataType: 'json',
        success: (tagsData) => {
            const tags = tagsData.data;
            const modalContent = buildModalContent(product, tags);
            const modal = new Modal(
                '',
                modalContent,
                `Edit ${product.name}`,
                () => initializeModal(product, tags),
                () => handleFormSubmission(productId)
            );
            modal.build();
        },
        error: () => {
            Swal.fire('Error!', 'Failed to load categories.', 'error');
        }
    });
}

// Helper function to build the modal content
function buildModalContent(product, tags) {
    return `
        <form id="editProductForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" value="${product.name}" class="form-control" id="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="details" class="form-label">Details</label>
                        <textarea class="form-control" id="details" rows="3" required>${product.details}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" min="1" value="${product.quantity}" required>
                    </div>

                    <div class="mb-3">
                        <label for="tagsSelection" class="form-label">Category</label>
                        <select class="form-select" id="tagsSelection">
                            ${tags.map(tag => `
                                <option value="${tag.id}" ${product.tag_id === tag.id ? 'selected' : ''}>
                                    ${tag.name}
                                </option>
                            `).join('')}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ISBN Management</label>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Equipment #</th>
                                        <th>ISBN</th>
                                    </tr>
                                </thead>
                                <tbody id="isbnTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 d-flex flex-column align-items-center">
                    <label class="form-label">Main Photo</label>
                    <input type="file" class="form-control" id="mainPhoto" accept="image/*">
                    <img id="mainPreview" src="${product.featured_image}" class="img-thumbnail mt-2" width="250">

                    <label class="form-label mt-3">Secondary Photos</label>
                    <input type="file" class="form-control" id="secondaryPhotos" accept="image/*" multiple>
                    <div id="secondaryPreviews" class="d-flex flex-wrap mt-2"></div>

                    <label class="form-label mt-3">Existing Images</label>
                    <div id="existingImages" class="d-flex flex-wrap mt-2">
                        ${product.img.map((imageUrl, index) => `
                            <div class="image-wrapper m-2">
                                <img src="${imageUrl}" class="img-thumbnail existing-image" width="100" data-index="${index}">
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        </form>
    `;
}

// Helper function to initialize modal event listeners
function initializeModal(product, tags) {
    // Populate ISBN table
    const tbody = $('#isbnTableBody');
    tbody.empty();
    product.products.forEach((item, index) => {
        const row = `
            <tr>
                <td class="align-middle">Equipment ${index + 1}</td>
                <td>
                    <input type="text" 
                           class="form-control isbn-input" 
                           name="isbn[]" 
                           value="${item.isbn || ''}"
                           required>
                </td>
            </tr>`;
        tbody.append(row);
    });

    // Handle quantity change
    $('#quantity').on('change', function () {
        const quantity = parseInt($(this).val(), 10);
        const tbody = $('#isbnTableBody');
        tbody.empty();

        if (quantity > 0) {
            for (let i = 0; i < quantity; i++) {
                const row = `
                    <tr>
                        <td class="align-middle">Equipment ${i + 1}</td>
                        <td>
                            <input type="text" 
                                   class="form-control isbn-input" 
                                   name="isbn[]" 
                                   value="${product.products[i]?.isbn || ''}"
                                   required>
                        </td>
                    </tr>`;
                tbody.append(row);
            }
        }
    });

    // Handle main photo preview
    $('#mainPhoto').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => $('#mainPreview').attr('src', event.target.result);
            reader.readAsDataURL(file);
        }
    });

    // Handle secondary photos preview
    $('#secondaryPhotos').on('change', function (event) {
        const container = $('#secondaryPreviews');
        container.empty();
        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail', 'm-1');
                img.style.width = '100px';
                container.append(img);
            };
            reader.readAsDataURL(file);
        });
    });
}

// Helper function to handle form submission
function handleFormSubmission(productId) {
    const updatedData = {
        name: $('#title').val(),
        details: $('#details').val(),
        quantity: $('#quantity').val(),
        tag_id: $('#tagsSelection').val(),
        isbns: $('.isbn-input').map(function () { return $(this).val(); }).get()
    };

    const formData = new FormData();
    formData.append('name', updatedData.name);
    formData.append('details', updatedData.details);
    formData.append('quantity', updatedData.quantity);
    formData.append('tag_id', updatedData.tag_id);

    updatedData.isbns.forEach((isbn, index) => {
        formData.append(`isbns[${index}]`, isbn);
    });

    const mainPhotoFile = $('#mainPhoto')[0].files[0];
    if (mainPhotoFile) {
        formData.append('featured_image', mainPhotoFile);
    }

    const secondaryFiles = $('#secondaryPhotos')[0].files;
    for (let i = 0; i < secondaryFiles.length; i++) {
        formData.append('images[]', secondaryFiles[i]);
    }

    Products.updateProduct(productId, formData)
        .then(() => {
            productsTableManager.reload();
            Swal.fire('Success!', 'Product edited successfully.', 'success');
        })
        .catch((error) => {
            Swal.fire('Error!', 'Failed to update product.', 'error');
            console.error('Update error:', error);
        });
}

export function addNewProduct() {
    // Fetch tags for the category dropdown
    $.ajax({
        url: '/api/tags',
        method: 'GET',
        dataType: 'json',
        success: (tagsData) => {
            const tags = tagsData.data;
            const modalContent = buildAddProductModalContent(tags);
            const modal = new Modal(
                '',
                modalContent,
                'Adicionar Novo Produto',
                () => initializeAddProductModal(tags),
                () => handleAddProductFormSubmission()
            );
            modal.build();
        },
        error: (xhr, status, error) => {
            console.error('Error fetching tags:', error);
            Swal.fire('Erro!', 'Não foi possível carregar os cargos.', 'error');
        }
    });
}

// Helper function to build the modal content
function buildAddProductModalContent(tags) {
    return `
        <form>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="details" class="form-label">Details</label>
                        <textarea class="form-control" id="details" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-select" id="tagsSelection" name="tags">
                            <option value="" disabled selected>Selecione uma categoria</option>
                            ${tags.map(tag => `<option value="${tag.id}">${tag.name}</option>`).join('')}
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ISBN Management</label>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Equipment #</th>
                                        <th>ISBN</th>
                                    </tr>
                                </thead>
                                <tbody id="isbnTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 d-flex flex-column align-items-center">
                    <label class="form-label">Main Photo</label>
                    <input type="file" class="form-control" id="mainPhoto" accept="image/*">
                    <img id="mainPreview" class="img-thumbnail mt-2 d-none" width="250">
                    
                    <label class="form-label mt-3">Secondary Photos</label>
                    <input type="file" class="form-control" id="secondaryPhotos" accept="image/*" multiple>
                    <div id="secondaryPreviews" class="d-flex flex-wrap mt-2"></div>
                </div>
            </div>
        </form>
    `;
}

// Helper function to initialize modal event listeners
function initializeAddProductModal(tags) {
    // Handle quantity change to update ISBN table
    $('#quantity').on('change', function () {
        const quantity = parseInt($(this).val(), 10);
        const tbody = $('#isbnTableBody');
        tbody.empty();

        if (quantity > 0) {
            for (let i = 0; i < quantity; i++) {
                const row = `
                    <tr>
                        <td class="align-middle">Equipment ${i + 1}</td>
                        <td>
                            <input type="text" 
                                   class="form-control isbn-input" 
                                   name="isbn[]" 
                                   required>
                        </td>
                    </tr>`;
                tbody.append(row);
            }
        }
    });

    // Handle main photo preview
    $('#mainPhoto').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                $('#mainPreview').attr('src', event.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle secondary photos preview
    $('#secondaryPhotos').on('change', function (event) {
        const container = $('#secondaryPreviews');
        container.empty();
        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail', 'm-1');
                img.style.width = '100px';
                container.append(img);
            };
            reader.readAsDataURL(file);
        });
    });
}

// Helper function to handle form submission
function handleAddProductFormSubmission() {
    const title = $('#title').val();
    const details = $('#details').val();
    const tag = $('#tagsSelection').val();
    const quantity = $('#quantity').val();

    if (!title || !details || !tag || !quantity) {
        Swal.showValidationMessage('Título, detalhes, quantidade, categoria e imagens são obrigatórias!');
        return null;
    }

    const formData = new FormData();
    formData.append('name', title);
    formData.append('details', details);
    formData.append('tag_id', tag);
    formData.append('quantity', quantity);

    // Append ISBNs
    $('.isbn-input').each(function (index, input) {
        formData.append(`isbns[${index}]`, $(input).val());
    });

    // Append main photo
    const mainPhotoFile = $('#mainPhoto')[0].files[0];
    if (mainPhotoFile) {
        formData.append('featured_image', mainPhotoFile);
    }

    // Append secondary photos
    const secondaryFiles = $('#secondaryPhotos')[0].files;
    for (let i = 0; i < secondaryFiles.length; i++) {
        formData.append('images[]', secondaryFiles[i]);
    }

    // Submit the form data
    Products.addProduct(formData)
        .then(() => {
            Swal.fire('Success!', 'Product added successfully.', 'success');
            productsTableManager.reload();
        })
        .catch((error) => {
            Swal.fire('Error!', 'Failed to add product.', 'error');
            console.error('Add product error:', error);
        });
}