    import { Products } from '../utilities/products';
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
    
        // Collect roles from the "role-list" table
        let roles = [];
        $('#role-list tbody tr').each(function () {
            const role = $(this).find('td:first').text().trim();
            if (role && role !== 'professor') { // Ignore the default placeholder if needed
                roles.push(role);
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
    
        // Append roles and departments as arrays to FormData
        roles.forEach((role, index) => {
            formData.append(`roles[${index}]`, role);
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

function eliminarProduto(id){
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

function addNewProduct(){
    SwalDialog.defaultAlert(
        '',
        'Adicionar novo utilizador',
        '',
        () => {},
        () => {},
        {
            html: `
                <form id="addProductForm">
                <div class="form-group">
                    <label for="nome">Nome de produto</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="form-group">
                    Cena para adicionar imagens
                </div>
                `,
            customClass: 'swal-form'
        }
    )
}