import { Products } from '../utilities/admin_products';
import { Tags } from '../utilities/tags';
import { SwalDialog } from '../utilities/dialog';
import { Modal } from '../components/manager';

const MAX_DESCRIPTION_LENGTH = 50;

function truncatedText(text){
    let truncatedText = text;
    if (truncatedText.length > MAX_DESCRIPTION_LENGTH) {
        truncatedText = truncatedText.substring(0, MAX_DESCRIPTION_LENGTH) + '...';
    }
    return truncatedText;
}


function loadTable(){
    if ($.fn.dataTable.isDataTable('#productsTable')) {
        $('#productsTable').DataTable().clear().destroy();
    }
    return $('#productsTable').DataTable({
        'paging': true,
        "pageLength": 5, // Set number of rows per page 
        "lengthChange": false, // Disable ability to change number of rows per page «
        "searching": true, // Enable searching 
        "info": false, // Disable table info 
        "autoWidth": false, // Disable auto width adjustment 
        "processing": true, // Show loading indicator 
        "serverSide": true,      // Enable server-side processing
        "ajax": function(data, callback, settings) { // Call your custom function
            Products.getProducts(data, function(response) {
                callback({
                    draw: data.draw,
                    recordsTotal: response.total,
                    recordsFiltered: response.filtered,
                    data: response.data
                });
            })},
        "columns": [
            { "data": "id" },
            { 
                "data": null,
                "render": function(data, type, row) {
                    return '<div class="d-flex align-items-center">' +
                        '<img src="' + row.featured_image + '" alt="Avatar" width="70" height="70" class="me-2 product-image">' +
                            '<div class="d-flex flex-column">'+
                                '<span class="fs-4">' + row.name + '</span>' +
                                '<span class="fw-lighter"> ' + truncatedText(row.details) +'</span>' + 
                            '</div>' +
                        '</div>';
                },
                "title": "Produto"
            },
            { "data": "quantity", "title": "Quantidade"},
            { 
                "data": "tags",
                "title": "Categorias"
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return '<button class="btn btn-danger btn-sm eliminar-btn" data-id="' + row.id + '">Eliminar</button> ' +
                        '<button class="btn btn-warning btn-sm editar-btn" data-id="' + row.id + '" data-product=\''+JSON.stringify(row)+'\'>Editar</button>';
                },
                "title": "Ações"
            }
        ],
        "language": {
            "search": "_INPUT_", // Customize search input
            "searchPlaceholder": "Search..."
        }
    });
}

$(document).ready(function() {

    // Initialize DataTables
    if($("#productsTable").length > 0) {
        window.table = loadTable();

        // Filter search functionality
        $('#searchInput').on('input', function() {
            $('#productsTable').DataTable().search($(this).val()).draw();
        });
        
        // Handle eliminar button click
        $('#productsTable tbody').on('click', '.eliminar-btn', function() {
            var productId = $(this).data('id');
            eliminarProduto(productId);
        });

        $('#productsTable tbody').on('click', '.editar-btn', function() {
            var productId = $(this).data('id');
            var product = $(this).data('product');
            editarProduto(productId, product);
        });
    }

    $('#addNewBtn').on('click', function(){
        addNewProduct();
    });

    // Hide initial loading wheel
    $('#loadingWheel').hide();

    $('#image-container').on('click', () => {
        $('#productImage').click();
    })

    $('#featured').on('click', function () {
        $('#productImage').click();
    });

    // Update the image preview when a file is selected
    $('#productImage').on('change', function () {
        const file = this.files[0]; // Get the selected file
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function (e) {
                $('#featured').attr('src', e.target.result); // Update the featured image src
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            alert('Por favor, carregue uma imagem válida.'); // Validation message for invalid files
        }
    });

    $('#addProductForm').on('submit', (event) => {
        event.preventDefault();
    
        // Create a FormData object from the form
        var formData = new FormData(event.target);
    
        // Collect tags from the "tag-list" table
        let tags = [];
        $('#tag-list tbody tr').each(function () {
            const tag = $(this).find('td:first').text().trim();
            if (tag && tag !== 'professor') { // Ignore the default placeholder if needed
                tags.push(tag);
            }
        });
    
        // Collect departments from the "department-list" table
        let departments = [];
        $('#department-list tbody tr').each(function () {
            const department = $(this).find('td:first').text().trim();
            if (department && department !== 'Departamento') { // Ignore the default placeholder if needed
                departments.push(department);
            }
        });
    
        // Append tags and departments as arrays to FormData
        tags.forEach((tag, index) => {
            formData.append(`tags[${index}]`, tag);
        });
    
        departments.forEach((department, index) => {
            formData.append(`departments[${index}]`, department);
        });
    
        // Send the form data
        Products.addProduct(formData).then(function (response) {
                const aditionalValues = {
                    showCancelButton: true,
                    confirmButtonText: 'Adicionar Novo',
                    cancelButtonText: 'Ir para lista de utilizadores',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                };
                SwalDialog.success(
                    'Utilizador adicionado com sucesso!',
                    '',
                    () => { window.location = '/dashboard/users'; },
                    () => { window.location.reload(); },
                    aditionalValues
                );
            })
            .catch(function (error) {
                if (error.response && error.response.data) {
                    // Extract error messages from the JSON response
                    const errorMessages = Object.values(error.response.data).flat().join('\n');
                    SwalDialog.error(
                        'Erro ao adicionar utilizador',
                        errorMessages,
                        () => { console.log('User dismissed the error alert.'); }
                    );
                } else {
                    SwalDialog.error(
                        'Erro ao adicionar utilizador',
                        'Ocorreu um erro inesperado. Por favor, tente novamente.',
                        () => { console.log('User dismissed the error alert.'); }
                    );
                }
            });
    });
    
    
    $('#addDepartment').on('click', function () {
        const selectedDepartment = $('#departmentSelection option:selected').text();
        const selectedValue = $('#departmentSelection').val();

        // Check if a department is selected
        if (!selectedValue) {
            alert('Por favor, selecione um departamento.');
            return;
        }

        // Check if the department is already in the table
        let isDuplicate = false;
        $('#department-list tbody tr').each(function () {
            const departmentText = $(this).find('td:first').text();
            if (departmentText === selectedDepartment) {
                isDuplicate = true;
                return false; // Break loop
            }
        });

        if (isDuplicate) {
            alert('Este departamento já foi adicionado.');
            return;
        }

        // Append a new row to the table
        const newRow = `
            <tr>
                <td>${selectedDepartment}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-department">Remover</button></td>
            </tr>
        `;
        $('#department-list tbody').append(newRow);

        // Optional: Clear the selection
        $('#departmentSelection').prop('selectedIndex', 0);
    });

    // Function to remove a department
    $('#department-list').on('click', '.remove-department', function () {
        $(this).closest('tr').remove();
    });

});

export function eliminarProduto(id){
    SwalDialog.warning(
        'Irá eliminar o produto selectionado',
        'Os registos de requisição irão contiuar dispoivéis',
        () => {
            Products.deleteProduct(id).then(function(){
                SwalDialog.success(
                    'Produto eliminado com sucesso!',
                    'Os registos de requisição continuaram disponivéis',
                    () => { window.table.ajax.reload() },
                    () => { window.table.ajax.reload() }
                );
            })
        },
        () => {},
        {
            showCancelButton: true,
            confirmButtonText: 'Eliminar!',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }
    )
}

function uploadProduct(title, details, tag, quantity) {
    const formData = new FormData();

    // Adiciona os valores do modal ao FormData
    formData.append('name', title);
    formData.append('details', details);
    formData.append('tag', tag);
    formData.append('quantity', quantity);

    // Adiciona a imagem principal
    const mainPhoto = $('#mainPhoto')[0].files[0];
    if (mainPhoto) {
        formData.append('featured_image', mainPhoto);
    }

    // Adiciona as imagens secundárias
    const secondaryPhotos = $('#secondaryPhotos')[0].files;
    for (let i = 0; i < secondaryPhotos.length; i++) {
        formData.append('images[]', secondaryPhotos[i]);
    }

    // Captura os ISBNs e adiciona ao formData
    $('.isbn-input').each(function (index, input) {
        formData.append(`isbns[]`, $(input).val());
    });

    // Envio com a função Products.addProduct()
    Products.addProduct(formData)
        .then(function () {
            Swal.fire('Guardado!', 'Produto adicionado com sucesso!', 'success');
            window.table.ajax.reload();
        })
        .catch(function (error) {
            Swal.fire('Erro!', 'Não foi possível guardar os detalhes.', 'error');
            console.error('Erro ao adicionar produto:', error);
        });
}

export function addNewProduct() {
    $.ajax({
        url: '/api/tags', // Replace with your API URL for tags
        method: 'GET',
        dataType: 'json',
        success:
        function (tagsData) {
            const tags = tagsData.data;

            // Function to render tags list
            function updatetagList() {
                const tagListHtml = tags
                    .map(tag => `
                        <tr>
                            <td>${tag.name}</td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-tag" data-tag-id="${tag.id}">Remover</button></td>
                        </tr>
                    `)
                    .join('');
                $('#tag-list tbody').html(tagListHtml);
            }

            let addNewProduct = new Modal(
                '',
                `<form>
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
                        <select class="form-select" id="tagsSelection" name="tags" style="padding: 10px; border-radius: 10px; border: 1px solid #ccc; font-size: 1em; transition: box-shadow 0.3s ease;" onfocus="this.style.boxShadow='0 0 10px rgba(0, 123, 255, 0.5)'" onblur="this.style.boxShadow='none'">
                            <option value="" disabled selected>Selecione uma categoria</option>
                            ${tags.map(tag => `<option value="${tag.id}">${tag.name}</option>`).join('')}
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" min="1" required>
                    </div>
                    <div id="isbnContainer" class="mb-3"></div>
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
                `,
                'Adicionar Novo Produto',
                function () {
                    // Initial render of user tags and departments
                    updatetagList();

                    // Add tag
                    $('#addtag').on('click', function () {
                        const selectedtagId = $('#tagsSelection').val();
                        const selectedtag = tags.find(tag => tag.id == selectedtagId);

                        if (selectedtag && !usertags.some(r => r.id == selectedtagId)) {
                            usertags.push(selectedtag);
                            updatetagList();
                        } else {
                            alert('Este cargo já foi adicionado.');
                        }
                    });

                    $('#quantity').on('change', function () {
                        const quantity = parseInt($(this).val(), 10);
                        const isbnContainer = $('#isbnContainer');
                    
                        // Limpa os ISBNs anteriores
                        isbnContainer.empty();
                    
                        if (quantity > 0) {
                            for (let i = 0; i < quantity; i++) {
                                const isbnInput = `
                                    <div class="mb-2">
                                        <label class="form-label">ISBN do Equipamento ${i + 1}</label>
                                        <input type="text" class="form-control isbn-input" name="isbn[]" required>
                                    </div>`;
                                isbnContainer.append(isbnInput);
                            }
                        }
                    });
                    

                    // Remove tag
                    $('#tag-list').on('click', '.remove-tag', function () {
                        const tagIdToRemove = $(this).data('tag-id');
                        usertags = usertags.filter(tag => tag.id != tagIdToRemove);
                        updatetagList();
                    });

                    $('#mainPhoto').on('change', (e) => {
                        e.preventDefault();
                        const preview = document.getElementById('mainPreview');
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                preview.src = event.target.result;
                                preview.classList.remove('d-none');
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    $('#secondaryPhotos').on('change', function(event){
                        const container = document.getElementById('secondaryPreviews');
                        container.innerHTML = ''; // Limpar as imagens anteriores
                        let imageCounter = 0;
                        Array.from(event.target.files).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.classList.add('img-thumbnail', 'm-1');
                                img.style.width = '100px';
                                img.id = `image-${imageCounter}`;

                                img.addEventListener('click', function() {
                                    const input = document.createElement('input');
                                    input.type = 'file';
                                    input.accept = 'image/*';
                                    input.style.display = 'none';
                                    container.appendChild(input);
                                    input.click();

                                    input.addEventListener('change', function(event) {
                                        const file = event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                img.src = e.target.result;
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    });
                                });

                                container.appendChild(img);
                                imageCounter++;
                            };
                            reader.readAsDataURL(file);
                        });
                    })
                },
                function () {
                    const title = $('#title').val();
                    const details = $('#details').val();
                    const tag = $('#tagsSelection').val();
                    const quantity = $('#quantity').val();

                    if (!title || !details || !tag || !quantity) {
                        Swal.showValidationMessage('titulo, detalhes, quantidade, categoria e imagens são obrigatórias!');
                        return null;
                    }
                    uploadProduct(title, details, tag, quantity);
                },
                (result) => {}
            );

            addNewProduct.build();
        },
        error: function (xhr, status, error) {
            console.error('Error fetching tags:', error);
            Swal.fire('Erro!', 'Não foi possível carregar os cargos.', 'error');
        }
    });
}


function editarProduto(productId, product) {
    window.produt = product;
    $.ajax({
        url: '/api/tags',
        method: 'GET',
        dataType: 'json',
        success: function (tagsData) {
            const tags = tagsData.data;

            let editarProduct = new Modal(
                '',
                `<form id="editProductForm">
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
                                    ${tags.map(tag => `<option value="${tag.id}" ${product.tag_id === tag.id ? 'selected' : ''}>${tag.name}</option>`).join('')}
                                </select>
                            </div>

                            <div id="isbnContainer" class="mb-3"></div>
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
                </form>`,
                'Edit ' + product.name,
                function () {
                    // Populate ISBNs
                    const isbnContainer = $('#isbnContainer');
                    isbnContainer.empty();
                    product.products.forEach((item, index) => {
                        const isbnInput = `
                            <div class="mb-2">
                                <label class="form-label">ISBN for Equipment ${index + 1}</label>
                                <input type="text" class="form-control isbn-input" value="${item.isbn}" name="isbn[]" required>
                            </div>`;
                        isbnContainer.append(isbnInput);
                    });

                    $('#quantity').on('change', function () {
                        const quantity = parseInt($(this).val(), 10);
                        const isbnContainer = $('#isbnContainer');
                        // Limpa os ISBNs anteriores
                        isbnContainer.empty();
                    
                        if (quantity > 0) {
                            for (let i = 0; i < quantity; i++) {
                                const isbnInput = `
                                    <div class="mb-2">
                                        <label class="form-label">ISBN do Equipamento ${i + 1}</label>
                                        <input type="text" value="${product.products[i].isbn}" class="form-control isbn-input" name="isbn[]" required>
                                    </div>`;
                                isbnContainer.append(isbnInput);
                            }
                        }
                    });

                    // Handle main photo preview
                    $('#mainPhoto').on('change', function (e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (event) {
                                $('#mainPreview').attr('src', event.target.result);
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    // Handle new secondary photos preview
                    $('#secondaryPhotos').on('change', function (event) {
                        const container = $('#secondaryPreviews');
                        container.empty(); // Clear previous previews
                        Array.from(event.target.files).forEach(file => {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.classList.add('img-thumbnail', 'm-1');
                                img.style.width = '100px';
                                container.append(img);
                            };
                            reader.readAsDataURL(file);
                        });
                    });
                },
                function () {
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

                    // Append new secondary photos
                    const secondaryFiles = $('#secondaryPhotos')[0].files;
                    for (let i = 0; i < secondaryFiles.length; i++) {
                        formData.append('images[]', secondaryFiles[i]);
                    }

                    Products.updateProduct(productId, formData).then((response) => {
                        window.table.ajax.reload();
                        Swal.fire('Success!', 'Product edited successfully.','success');
                    });
                }
            );

            editarProduct.build();
        },
        error: function () {
            Swal.fire('Error!', 'Failed to load categories.', 'error');
        }
    });
}
