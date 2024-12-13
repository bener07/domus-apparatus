    import { Products } from '../utilities/admin_products';
import { Tags } from '../utilities/tags';
import { SwalDialog } from '../utilities/dialog';


function loadTable(){
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
                                '<span class="fw-lighter"> ' + row.details +'</span>' + 
                            '</div>' +
                        '</div>';
                },
                "title": "Produto"
            },
            { 
                "data": "tags",
                "title": "Categorias"
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return '<button class="btn btn-danger btn-sm eliminar-btn" data-id="' + row.id + '">Eliminar</button> ' +
                        '<button class="btn btn-warning btn-sm editar-btn" data-id="' + row.id + '" data-user=\''+JSON.stringify(row)+'\'>Editar</button>';
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
            editarProduto(userId, user);
        });
    }

    $('#addNewBtn').on('click', function(){
        addNewProduct();
    });

    // Hide initial loading wheel
    $('#loadingWheel').hide();

    Tags.getTags().then((response) => {
        response.data.forEach(department => {
            $('#tagsSelection').append(`<option value="${department.id}">${department.name}</option>`);
        });
    });

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

export function addNewProduct() {
    $.ajax({
        url: '/api/tags', // Replace with your API URL for tags
        method: 'GET',
        dataType: 'json',
        success:
        function (tagsData) {
            const tags = tagsData.data;

            // Initialize user tags and departments
            let productTags = [];

            // Function to render tags list
            function updatetagList() {
                const tagListHtml = usertags
                    .map(tag => `
                        <tr>
                            <td>${tag.name}</td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-tag" data-tag-id="${tag.id}">Remover</button></td>
                        </tr>
                    `)
                    .join('');
                $('#tag-list tbody').html(tagListHtml);
            }

            SwalDialog.defaultAlert(
                '',
                'Criar Equipamento',
                '',
                (result) => {
                    console.log(result.value)
                    Products.addProduct(JSON.stringify(result.value))
                        .then(function () {
                            Swal.fire('Guardado!', 'Detalhes atualizados.', 'success');
                            window.table.ajax.reload();
                        })
                        .catch(function (error) {
                            Swal.fire('Erro!', 'Não foi possível guardar os detalhes.', 'error');
                            console.error('Error updating user:', error);
                        });}
                ,() => {},
                {
                    html: `<div class="row" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(to right, #f8f9fa, #e3f2fd); padding: 20px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); transition: all 0.3s ease-in-out;">
    <div class="col-lg-8 d-flex flex-column">
        <label for="swal-input-nome" style="display:block; margin-bottom:5px; font-weight: bold; font-size: 1.2em; color: #333;">Nome do Equipamento</label>
        <input id="swal-input-nome" class="swal2-input" style="padding: 10px; border-radius: 10px; border: 1px solid #ccc; font-size: 1em; transition: box-shadow 0.3s ease;" onfocus="this.style.boxShadow='0 0 10px rgba(0, 123, 255, 0.5)'" onblur="this.style.boxShadow='none'">

        <label for="swal-input-descricao" style="display:block; margin-top:10px; margin-bottom:5px; font-weight: bold; font-size: 1.2em; color: #333;">Descrição</label>
        <input id="swal-input-descricao" class="swal2-input" style="padding: 10px; border-radius: 10px; border: 1px solid #ccc; font-size: 1em; transition: box-shadow 0.3s ease;" onfocus="this.style.boxShadow='0 0 10px rgba(0, 123, 255, 0.5)'" onblur="this.style.boxShadow='none'">
    </div>
    <div class="col-lg-4 d-flex justify-content-center align-items-center">
        <input type="file" id="equipment-image" accept="image/*" style="display: none;" onchange="previewImage(event)">
        <img src="https://i.pinimg.com/564x/b9/d3/83/b9d3831124e896c6315569c891e31bb9.jpg" alt="Imagem Modelo" id="equipment-preview" 
            style="width:200px; height: 200px; object-fit: cover; cursor: pointer; border: 2px solid #007bff; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); border-radius: 10px; transition: transform 0.3s ease, box-shadow 0.3s ease; background: linear-gradient(to bottom right, #ffffff, #e3f2fd);" 
            onclick="document.getElementById('equipment-image').click();" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 12px 24px rgba(0, 0, 0, 0.3)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 16px rgba(0, 0, 0, 0.3)';">
              
    </div>
</div>
<div class="row mt-4" style="background: linear-gradient(to right, #ffffff, #f0f8ff); padding: 20px; border-radius: 15px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);">
    <div class="col-lg-12">
        <table id="tag-list" class="table table-bordered" style="border-collapse: collapse; background-color: #ffffff;">
            <caption style="caption-side: top; text-align: center; font-size: 1.5em; font-weight: bold; color: #007bff; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">Aspectos</caption>
            <thead>
                <tr style="background-color: #007bff; color: #fff;">
                    <th style="width: 50%; text-align: left; padding: 10px; font-size: 1.1em;">Quantidade</th>
                    <th style="width: 50%; text-align: right; padding: 10px; font-size: 1.1em;">Categorias</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamically populated tags -->
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: left; padding: 10px;">
                        <input type="number" id="tag-quantity" class="form-control" placeholder="Quantidade" style="padding: 10px; border-radius: 10px; border: 1px solid #ccc; font-size: 1em; transition: box-shadow 0.3s ease;" onfocus="this.style.boxShadow='0 0 10px rgba(0, 123, 255, 0.5)'" onblur="this.style.boxShadow='none'">
                    </td>
                    <td style="text-align: right; padding: 10px;">
                        <select class="form-select" id="tagsSelection" name="tags" style="padding: 10px; border-radius: 10px; border: 1px solid #ccc; font-size: 1em; transition: box-shadow 0.3s ease;" onfocus="this.style.boxShadow='0 0 10px rgba(0, 123, 255, 0.5)'" onblur="this.style.boxShadow='none'">
                            <option value="" disabled selected>Selecione uma categoria</option>
                            ${tags.map(tag => `<option value="${tag.id}">${tag.name}</option>`).join('')}
                        </select>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
function previewImage(event) {
    const preview = document.getElementById('equipment-preview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>


                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Salvar',
                    customClass: 'swal-form',
                    didOpen: function () {
                        // Initial render of user tags and departments
                        updatetagList();
                        updateDepartmentList();

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

                        // Remove tag
                        $('#tag-list').on('click', '.remove-tag', function () {
                            const tagIdToRemove = $(this).data('tag-id');
                            usertags = usertags.filter(tag => tag.id != tagIdToRemove);
                            updatetagList();
                        });

                        // Remove department
                        $('#department-list').on('click', '.remove-department', function () {
                            userDepartments = [];
                            updateDepartmentList();
                        });
                    },
                    preConfirm: function () {
                        const name = $('#swal-input-nome').val();
                        const email = $('#swal-input-email').val();

                        if (!name || !email || usertags.length === 0 || userDepartments.length === 0) {
                            Swal.showValidationMessage('Nome, Email, pelo menos um Cargo e um Departamento são necessários!');
                            return null;
                        }

                        return {
                            name,
                            email,
                            tags: usertags.map(tag => tag.name),
                            departments: userDepartments
                        };
                    }
                }
            );
        },
        error: function (xhr, status, error) {
            console.error('Error fetching tags:', error);
            Swal.fire('Erro!', 'Não foi possível carregar os cargos.', 'error');
        }
    });
}
