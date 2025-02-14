import { Users } from '../utilities/users';
import { Tags } from '../utilities/tags';
import { Departments } from '../utilities/departments';
import { SwalDialog } from '../utilities/dialog';




$(document).ready(function() {
    // Initialize DataTables
    if($("#usersTable").length > 0) {
        window.table = loadTable();

        // Filter search functionality
        $('#searchInput').on('input', function() {
            $('#usersTable').DataTable().search($(this).val()).draw();
        });
        
        // Handle eliminar button click
        $('#usersTable tbody').on('click', '.eliminar-btn', function() {
            var userId = $(this).data('id');
            eliminarUser(userId);
        });

        $('#usersTable tbody').on('click', '.editar-btn', function() {
            var userId = $(this).data('id');
            var user = $(this).data('user');
            editarUser(userId, user);
        });

        $('#addNewBtn').on('click', function(){
            addNewUser();
        });
    }

    // Hide initial loading wheel
    $('#loadingWheel').hide();

    Tags.getTags().then(function (response){
        response.data.forEach(tag => {
            $('#tagsSelection').append(`<option value="${tag.id}">${tag.name}</option>`);
        });
    });

    Departments.getDepartments().then((response) => {
        response.data.forEach(department => {
            $('#departmentSelection').append(`<option value="${department.id}">${department.name}</option>`);
        });
    });

});

function loadTable(){
    return $('#usersTable').DataTable({
        "paging": true, // Enable pagination 
        "pageLength": 5, // Set number of rows per page 
        "lengthChange": false, // Disable ability to change number of rows per page «
        "searching": true, // Enable searching 
        "info": false, // Disable table info 
        "autoWidth": false, // Disable auto width adjustment 
        "processing": true, // Show loading indicator 
        "serverSide": true,      // Enable server-side processing
        "ajax": function(data, callback, settings) { // Call your custom function
            Users.getUsers(data, function(response) {
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
                           '<img src="' + row.avatar + '" alt="Avatar" width="30" height="30" class="rounded-circle me-2 user-image">' +
                           '<span>' + row.name + '</span>' +
                           '</div>';
                },
                "title": "Perfil"
            },
            { "data": "email" },
            { 
                "data": "departments",
                "title": "Departamento",
                "render": function(data, type, row) {
                    return data.name;
                }
            },
            {
                "data": "roles",
                "render": function(data, type, row) {
                    return data;
                },
                "title": "Cargo"
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

// Function to handle user deletion
export function eliminarUser(userId) {
    // Implement the logic to delete the user
    Swal.fire({
        title: 'Confirmar eliminar?',
        text: 'Tem certeza que deseja eliminar este usuário?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, eliminar!'
    }).then((result) => {
        if(result.isConfirmed) {
            Users.deleteUser(userId).then((result)=>{
                Swal.fire('Eliminado!', 'Eliminado com sucesso!', 'success');
            }).catch((error)=>{
                Swal.fire('Erro!', 'Ocorreu um erro ao eliminar o usuário.', 'error');
            });
            window.table.ajax.reload();
        };
    });
}



// Function to handle user editing
export function editarUser(userId, user) {
    $.ajax({
        url: '/api/tags',
        method: 'GET',
        dataType: 'json',
        success: function (tagsData) {
            $.ajax({
                url: '/api/departments',
                method: 'GET',
                dataType: 'json',
                success: function (departmentsData) {
                    const tags = tagsData.data;
                    const departments = departmentsData.data;

                    let usertags = user.tags || [];
                    let userDepartments = user.departments || [];
                    let uploadedImage = null; // Store uploaded image

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

                    function updateDepartmentList() {
                        const departmentListHtml = userDepartments.name
                            ? `<tr>
                                    <td>${userDepartments.name}</td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-department" data-department-id="${userDepartments.id}">Remover</button></td>
                                </tr>`
                            : '';
                        $('#department-list tbody').html(departmentListHtml);
                    }

                    SwalDialog.defaultAlert(
                        '',
                        'Editar utilizador',
                        '',
                        (result) => {
                            const formData = new FormData();
                            formData.append('name', result.value.name);
                            formData.append('email', result.value.email);
                            formData.append('tags', JSON.stringify(result.value.tags));
                            formData.append('departments', JSON.stringify(result.value.departments));
                            if (uploadedImage) {
                                formData.append('avatar', uploadedImage); // Append image
                            }

                            $.ajax({
                                url: `/api/admin/users/${userId}`, // Adjust endpoint if needed
                                method: 'PUT', 
                                processData: false,
                                contentType: false,
                                data: formData,
                                success: function () {
                                    Swal.fire('Guardado!', 'Detalhes atualizados.', 'success');
                                    window.table.ajax.reload();
                                },
                                error: function (error) {
                                    Swal.fire('Erro!', 'Não foi possível guardar os detalhes.', 'error');
                                    console.error('Error updating user:', error);
                                }
                            });
                        },
                        () => {},
                        {
                            html: `
                                <div class="row">
                                    <div class="col-lg-8 d-flex flex-column">
                                        <label for="swal-input-nome" style="display:block; margin-bottom:5px;">Nome</label>
                                        <input id="swal-input-nome" class="swal2-input" value="${user.name}">
                                        
                                        <label for="swal-input-email" style="display:block; margin-top:10px; margin-bottom:5px;">Email</label>
                                        <input id="swal-input-email" class="swal2-input" value="${user.email}">
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="userImage" class="form-label">Imagem de Utilizador</label>
                                            <input type="file" class="form-control" style="display:none;" id="userImage" name="avatar" accept="image/*">
                                            <div class="col image_container" id="image-container">
                                                <img src="${user.avatar}" alt="Featured Image" id="featured">
                                                <img src="https://cdn-icons-png.flaticon.com/512/84/84380.png" alt="Overlay" class="overlay-image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5 mt-4">
                                        <table id="tag-list" class="table table-bordered">
                                            <caption>Cargos</caption>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" style="padding: 0px">
                                                        <div class="d-flex flex-row">
                                                            <select class="form-select col-lg-8" id="tagsSelection" name="tags">
                                                                <option value="" disabled selected>Selecione um cargo</option>
                                                                ${tags.map(tag => `<option value="${tag.id}">${tag.name}</option>`).join('')}
                                                            </select>
                                                            <button id="addtag" type="button" class="btn btn-primary col-lg-4" style="border-radius: 0 10px 10px 0px;">Adicionar Cargo</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col-lg-7 mt-4">
                                        <table id="department-list" class="table table-bordered">
                                            <caption>Departamento</caption>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" style="padding: 0px">
                                                        <div class="d-flex flex-row">
                                                            <select class="form-select col-lg-8" id="departmentSelection" name="departments">
                                                                <option value="" disabled selected>Selecione um departamento</option>
                                                                ${departments.map(department => `<option value="${department.id}">${department.name}</option>`).join('')}
                                                            </select>
                                                            <button id="addDepartment" type="button" class="btn btn-primary col-lg-4" style="border-radius: 0 10px 10px 0px;">Adicionar Departamento</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            `,
                            focusConfirm: false,
                            showCancelButton: true,
                            confirmButtonText: 'Salvar',
                            customClass: 'swal-form',
                            didOpen: function () {
                                updatetagList();
                                updateDepartmentList();

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

                                $('#tag-list').on('click', '.remove-tag', function () {
                                    const tagIdToRemove = $(this).data('tag-id');
                                    usertags = usertags.filter(tag => tag.id != tagIdToRemove);
                                    updatetagList();
                                });

                                $('#addDepartment').on('click', function () {
                                    const selectedDepartmentId = $('#departmentSelection').val();
                                    const selectedDepartment = departments.find(department => department.id == selectedDepartmentId);
                                    if (selectedDepartment) {
                                        userDepartments = selectedDepartment;
                                        updateDepartmentList();
                                    } else {
                                        alert('Este departamento já foi adicionado.');
                                    }
                                });

                                $('#department-list').on('click', '.remove-department', function () {
                                    userDepartments = [];
                                    updateDepartmentList();
                                });

                                $('#image-container').on('click', () => $('#userImage').click());
                                $('#userImage').on('change', function () {
                                    const file = this.files[0];
                                    if (file && file.type.startsWith('image/')) {
                                        const reader = new FileReader();
                                        reader.onload = function (e) {
                                            $('#featured').attr('src', e.target.result);
                                        };
                                        reader.readAsDataURL(file);
                                        uploadedImage = file;
                                    } else {
                                        alert('Por favor, carregue uma imagem válida.');
                                    }
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
                    console.error('Error fetching departments:', error);
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Error fetching tags:', error);
        }
    });
}





export function addNewUser() {
    $.ajax({
        url: '/api/tags', // Replace with your API URL for tags
        method: 'GET',
        dataType: 'json',
        success: function (tagsData) {
            // Fetch departments from the API
            $.ajax({
                url: '/api/departments', // Replace with your API URL for departments
                method: 'GET',
                dataType: 'json',
                success: function (departmentsData) {
                    const tags = tagsData.data;
                    const departments = departmentsData.data;

                    // Initialize user tags and departments
                    let usertags = [];
                    let userDepartments = [];

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

                    // Function to render departments list
                    function updateDepartmentList() {                       
                        const departmentListHtml = `
                                <tr>
                                    <td>${userDepartments.name}</td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-department" data-department-id="${userDepartments.id}">Remover</button></td>
                                </tr>
                            `;
                        if(userDepartments.length === 0)
                            $('#department-list tbody').html('');   
                        else{
                            $('#department-list tbody').html(departmentListHtml);
                        }
                    }

                    SwalDialog.defaultAlert(
                        '',
                        'Editar utilizador',
                        '',
                        (result) => {
                            console.log(result.value)
                            Users.addUser(JSON.stringify(result.value))
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
                            html: `
                        <div class="row">
                            <div class="col-lg-8 d-flex flex-column">
                                <label for="swal-input-nome" style="display:block; margin-bottom:5px;">Nome</label>
                                <input id="swal-input-nome" class="swal2-input">
                                
                                <label for="swal-input-email" style="display:block; margin-top:10px; margin-bottom:5px;">Email</label>
                                <input id="swal-input-email" class="swal2-input">
                            </div>
                            <div class="col-lg-4">
                                <img src="http://localhost/storage/images/avatar.png" alt="Imagem de utilizador" style="width:200px; height: 200px;" class="rounded-circle user-image">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 mt-4">
                                <table id="tag-list" class="table table-bordered">
                                    <caption>Cargos</caption>
                                    <tbody>
                                        <!-- Dynamically populated tags -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="padding: 0px">
                                                <div class="d-flex flex-row">
                                                    <select class="form-select col-lg-8" id="tagsSelection" name="tags">
                                                        <option value="" disabled selected>Selecione um cargo</option>
                                                        ${tags.map(tag => `<option value="${tag.id}">${tag.name}</option>`).join('')}
                                                    </select>
                                                    <button id="addtag" type="button" class="btn btn-primary col-lg-4" style="border-radius: 0 10px 10px 0px;">Adicionar Cargo</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-lg-7 mt-4">
                                <table id="department-list" class="table table-bordered">
                                    <caption>
                                        Departamento
                                    </caption>
                                    <tbody>
                                        <!-- Dynamically populated departments -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="padding: 0px">
                                                <div class="d-flex flex-row">
                                                    <select class="form-select col-lg-8" id="departmentSelection" name="departments">
                                                        <option value="" disabled selected>Selecione um departamento</option>
                                                        ${departments.map(department => `<option value="${department.id}">${department.name}</option>`).join('')}
                                                    </select>
                                                    <button id="addDepartment" type="button" class="btn btn-primary col-lg-4" style="border-radius: 0 10px 10px 0px;">Adicionar Departamento</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
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

                                // Add department
                                $('#addDepartment').on('click', function () {
                                    const selectedDepartmentId = $('#departmentSelection').val();
                                    const selectedDepartment = departments.find(department => department.id == selectedDepartmentId);

                                    if (selectedDepartment) {
                                        userDepartments = {'name': selectedDepartment.name, 'id': selectedDepartment.id};
                                        updateDepartmentList();
                                    } else {
                                        alert('Este departamento já foi adicionado.');
                                    }
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
                    console.error('Error fetching departments:', error);
                    Swal.fire('Erro!', 'Não foi possível carregar os departamentos.', 'error');
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Error fetching tags:', error);
            Swal.fire('Erro!', 'Não foi possível carregar os cargos.', 'error');
        }
    });
}
