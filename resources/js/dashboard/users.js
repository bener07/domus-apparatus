import { Users } from '../utilities/users';
import { Roles } from '../utilities/roles';
import { Departments } from '../utilities/departments';
import { SwalDialog } from '../utilities/dialog';




$(document).ready(function() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

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

    Roles.getRoles().then(function (response){
        response.data.forEach(role => {
            $('#rolesSelection').append(`<option value="${role.id}">${role.name}</option>`);
        });
    });

    Departments.getDepartments().then((response) => {
        response.data.forEach(department => {
            $('#departmentSelection').append(`<option value="${department.id}">${department.name}</option>`);
        });
    });

    $('#image-container').on('click', () => {
        $('#userImage').click();
    })

    $('#featured').on('click', function () {
        $('#userImage').click();
    });

    // Update the image preview when a file is selected
    $('#userImage').on('change', function () {
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

    $('#addUserForm').on('submit', (event) => {
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
        Users.addUser(formData).then(function (response) {
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

    // Function to add a role
    $('#addRole').on('click', function () {
        const selectedRole = $('#rolesSelection option:selected').text();
        const selectedValue = $('#rolesSelection').val();

        // Check if a role is selected
        if (!selectedValue) {
            alert('Por favor, selecione um cargo.');
            return;
        }

        // Check if the role is already in the table
        let isDuplicate = false;
        $('#role-list tbody tr').each(function () {
            const roleText = $(this).find('td:first').text();
            if (roleText === selectedRole) {
                isDuplicate = true;
                return false; // Break loop
            }
        });

        if (isDuplicate) {
            alert('Este cargo já foi adicionado.');
            return;
        }

        // Append a new row to the table
        const newRow = `
            <tr>
                <td>${selectedRole}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-role">Remover</button></td>
            </tr>
        `;
        $('#role-list tbody').append(newRow);

        // Optional: Clear the selection
        $('#rolesSelection').prop('selectedIndex', 0);
    });

    // Function to remove a role
    $('#role-list').on('click', '.remove-role', function () {
        $(this).closest('tr').remove();
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
                    return data.map(role => role.name);
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
function eliminarUser(userId) {
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
function editarUser(userId, user) {
    // Fetch roles and departments from the API
    console.log(user);
    $.ajax({
        url: '/api/admin/roles', // Replace with your API URL for roles
        method: 'GET',
        dataType: 'json',
        success: function (rolesData) {
            // Fetch departments from the API
            $.ajax({
                url: '/api/admin/departments', // Replace with your API URL for departments
                method: 'GET',
                dataType: 'json',
                success: function (departmentsData) {
                    const roles = rolesData.data;
                    const departments = departmentsData.data;

                    // Initialize user roles and departments
                    let userRoles = user.roles || [];
                    let userDepartments = user.departments || [];

                    // Function to render roles list
                    function updateRoleList() {
                        const roleListHtml = userRoles
                            .map(role => `
                                <tr>
                                    <td>${role.name}</td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-role" data-role-id="${role.id}">Remover</button></td>
                                </tr>
                            `)
                            .join('');
                        $('#role-list tbody').html(roleListHtml);
                    }

                    // Function to render departments list
                    function updateDepartmentList() {
                        console.log(userDepartments);
                        
                        const departmentListHtml = `
                                <tr>
                                    <td>${userDepartments.name}</td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-department" data-department-id="${userDepartments.id}">Remover</button></td>
                                </tr>
                            `;
                        if(userDepartments.length > 0)
                            $('#department-list tbody').html(departmentListHtml);
                        else{
                            $('#department-list tbody').html('');   
                        }
                    }

                    SwalDialog.defaultAlert(
                        '',
                        'Editar utilizador',
                        '',
                        (result) => {
                            console.log(result.value)
                            Users.updateUser(userId, JSON.stringify(result.value))
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
                                <input id="swal-input-nome" class="swal2-input" value="${user.name}">
                                
                                <label for="swal-input-email" style="display:block; margin-top:10px; margin-bottom:5px;">Email</label>
                                <input id="swal-input-email" class="swal2-input" value="${user.email}">
                            </div>
                            <div class="col-lg-4">
                                <img src="${user.avatar}" alt="Imagem de utilizador" style="width:200px; height: 200px;" class="rounded-circle user-image">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5 mt-4">
                                <table id="role-list" class="table table-bordered">
                                    <caption>Cargos</caption>
                                    <tbody>
                                        <!-- Dynamically populated roles -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="padding: 0px">
                                                <div class="d-flex flex-row">
                                                    <select class="form-select col-lg-8" id="rolesSelection" name="roles">
                                                        <option value="" disabled selected>Selecione um cargo</option>
                                                        ${roles.map(role => `<option value="${role.id}">${role.name}</option>`).join('')}
                                                    </select>
                                                    <button id="addRole" type="button" class="btn btn-primary col-lg-4" style="border-radius: 0 10px 10px 0px;">Adicionar Cargo</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-lg-7 mt-4">
                                <table id="department-list" class="table table-bordered">
                                    <caption>
                                        Departamentos
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
                                // Initial render of user roles and departments
                                updateRoleList();
                                updateDepartmentList();

                                // Add role
                                $('#addRole').on('click', function () {
                                    const selectedRoleId = $('#rolesSelection').val();
                                    const selectedRole = roles.find(role => role.id == selectedRoleId);

                                    if (selectedRole && !userRoles.some(r => r.id == selectedRoleId)) {
                                        userRoles.push(selectedRole);
                                        updateRoleList();
                                    } else {
                                        alert('Este cargo já foi adicionado.');
                                    }
                                });

                                // Remove role
                                $('#role-list').on('click', '.remove-role', function () {
                                    const roleIdToRemove = $(this).data('role-id');
                                    userRoles = userRoles.filter(role => role.id != roleIdToRemove);
                                    updateRoleList();
                                });

                                // Add department
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

                                // Remove department
                                $('#department-list').on('click', '.remove-department', function () {
                                    console.log(userDz);
                                    userDepartments = [];
                                    updateDepartmentList();
                                });
                            },
                            preConfirm: function () {
                                const name = $('#swal-input-nome').val();
                                const email = $('#swal-input-email').val();

                                if (!name || !email || userRoles.length === 0 || userDepartments.length === 0) {
                                    Swal.showValidationMessage('Nome, Email, pelo menos um Cargo e um Departamento são necessários!');
                                    return null;
                                }

                                return {
                                    name,
                                    email,
                                    roles: userRoles.map(role => role.name),
                                    departments: userDepartments.map(department => department.name)
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
            console.error('Error fetching roles:', error);
            Swal.fire('Erro!', 'Não foi possível carregar os cargos.', 'error');
        }
    });
}




function addNewUser() {
    // Fetch roles from the API
    $.ajax({
        url: '/api/admin/roles', // Replace with your API URL
        method: 'GET',
        dataType: 'json',
        customClass: {
            className: 'swal-form',
        },
        className: 'swal-form',
        success: function (roles) {
            roles = roles.data;

            let userRoles = []; // Initialize with an empty roles array

            function updateRoleList() {
                const roleListHtml = userRoles
                    .map(role => `
                        <span class="role-badge" style="background-color: #e0e0e0; padding: 5px 10px; border-radius: 5px; margin-right: 5px;">
                            ${role.name}
                            <button class="remove-role" data-role-id="${role.id}" style="background: none; border: none; font-weight: bold; color: red; margin-left: 5px;">&times;</button>
                        </span>
                    `)
                    .join('');
                $('#role-list').html(roleListHtml);
            }

            Swal.fire({
                title: 'Adicionar Utilizador',
                html: `
                    <label for="swal-input-nome" style="display:block; margin-bottom:5px;">Nome</label>
                    <input id="swal-input-nome" class="swal2-input" placeholder="Jon Doe">

                    <label for="swal-input-email" style="display:block; margin-top:10px; margin-bottom:5px;">Email</label>
                    <input id="swal-input-email" class="swal2-input" placeholder="johndoe@example.com">

                    <label style="display:block; margin-top:10px; margin-bottom:5px;">Roles</label>
                    <div id="role-list" style="margin-bottom: 10px; display: flex; gap: 5px; flex-wrap: wrap;">
                        <!-- Dynamically populated roles -->
                    </div>
                    <select id="role-selector" class="swal2-select" style="width: calc(69% - 40px); display: inline-block;">
                        <option value="" disabled selected>Select a role to add</option>
                        ${roles.map(role => `<option value="${role.id}">${role.name}</option>`).join('')}
                    </select>
                    <button id="add-role-btn" style="margin-left: 10px; padding: 5px;">+</button>
                `,
                customClass: 'swal-form',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Adicionar',
                didOpen: function () {
                    updateRoleList();

                    $('#add-role-btn').on('click', function () {
                        const selectedRoleId = $('#role-selector').val();
                        const selectedRole = roles.find(role => role.id == selectedRoleId);

                        if (selectedRole && !userRoles.some(r => r.id == selectedRoleId)) {
                            userRoles.push(selectedRole);
                            updateRoleList();
                        }
                    });

                    $(document).on('click', '.remove-role', function () {
                        const roleIdToRemove = $(this).data('role-id');
                        userRoles = userRoles.filter(role => role.id != roleIdToRemove);
                        updateRoleList();
                    });
                },
                preConfirm: function () {
                    const name = $('#swal-input-nome').val();
                    const email = $('#swal-input-email').val();

                    if (!name || !email || userRoles.length === 0) {
                        Swal.showValidationMessage('Name, Email, and at least one Role are required!');
                        return null;
                    }

                    return {
                        name,
                        email,
                        roles: userRoles.map(role => role.name)
                    };
                }
            }).then(function (result) {
                if (result.isConfirmed) {
                    Users.addUser(JSON.stringify(result.value))
                        .then(function () {
                            Swal.fire('Added!', 'New user has been created.', 'success');
                            window.table.ajax.reload(); // Refresh table
                        })
                        .catch(function (error) {
                            Users.showApiErrors("Houve um problema a registar o utilizador, verifique o email ou as outras informações");
                            console.error('Error creating user:', error);
                        });
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Error fetching roles:', error);
            Swal.fire('Error!', 'Failed to load roles. Please try again later.', 'error');
        }
    });
}
