import { Users } from './admin_api';


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
                           '<img src="' + row.avatar + '" alt="Avatar" width="30" height="30" class="rounded-circle me-2">' +
                           '<span>' + row.name + '</span>' +
                           '</div>';
                },
                "title": "Perfil"
            },
            { "data": "email" },
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

$(document).ready(function() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Initialize DataTables
    window.table = loadTable();

    // Filter search functionality
    $('#searchInput').on('input', function() {
        $('#usersTable').DataTable().search($(this).val()).draw();
    });

    // Hide initial loading wheel
    $('#loadingWheel').hide();

    // Handle eliminar button click
    $('#usersTable tbody').on('click', '.eliminar-btn', function() {
        var userId = $(this).data('id');
        eliminarUser(userId);
    });

    // Handle editar button click
    $('#usersTable tbody').on('click', '.editar-btn', function() {
        var userId = $(this).data('id');
        var user = $(this).data('user');
        editarUser(userId, user);
    });

    $('#addNewBtn').on('click', function(){
        addNewUser();
    })
});

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

            // Initialize user roles
            let userRoles = user.roles || []; // Assuming `user.roles` is an array of roles the user already has

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
                title: 'Edit User',
                html: `
                    <label for="swal-input-nome" style="display:block; margin-bottom:5px;">Nome</label>
                    <input id="swal-input-nome" class="swal2-input" value="${user.name}">
                    
                    <label for="swal-input-email" style="display:block; margin-top:10px; margin-bottom:5px;">Email</label>
                    <input id="swal-input-email" class="swal2-input" value="${user.email}">
                    
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
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Save',
                didOpen: function () {
                    // Initial render of user roles
                    updateRoleList();

                    // Add role
                    $('#add-role-btn').on('click', function () {
                        const selectedRoleId = $('#role-selector').val();
                        const selectedRole = roles.find(role => role.id == selectedRoleId);

                        if (selectedRole && !userRoles.some(r => r.id == selectedRoleId)) {
                            userRoles.push(selectedRole);
                            updateRoleList();
                        }
                    });

                    // Remove role
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
                        Swal.showValidationMessage('Nome, Email e pelo menos um Cargo são necessários!');
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
                    Users.updateUser(userId, JSON.stringify(result.value))
                        .then(function () {
                            Swal.fire('Guardado!', 'Detalhes atualizados.', 'success');
                            window.table.ajax.reload();
                        })
                        .catch(function (error) {
                            Swal.fire('Erro!', 'Não foi possível guardar os detalhes.', 'error');
                            console.error('Error updating user:', error);
                        });
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
