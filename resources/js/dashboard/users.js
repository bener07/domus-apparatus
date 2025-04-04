import { Users } from '../utilities/users';
import { Roles } from '../utilities/roles';
import { Departments } from '../utilities/departments';
import { SwalDialog } from '../components/dialog';
import { DataTableManager } from '../components/tables';
import { Modal } from '../components/manager';

const usersTableManager = new DataTableManager('usersTable', {
    getData: (data, callback) => {
        Users.getUsers(data, callback);
    },
    columns: [
        { data: "id" },
        {
            data: null,
            render: (data, type, row) => {
                return `<div class="d-flex align-items-center">
                            <img src="${row.avatar}" alt="Avatar" width="30" height="30" class="rounded-circle me-2 user-image">
                            <span>${row.name}</span>
                        </div>`;
            },
            title: "Perfil"
        },
        { data: "email" },
        {
            data: "departments",
            title: "Departamento",
            render: (data) => {
                return data ? data.name : ''; // Handle missing departments
            }
        },
        {
            data: "roles",
            title: "Cargo",
            render: function(data, type, row) {
                // Transform the roles array into a comma-separated string
                if (Array.isArray(data) && data.length > 0) {
                    return data.map(role => role.name).join(', ');
                } else {
                    return 'No roles'; // Default value if roles array is empty
                }
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `
                    <button class="btn btn-danger btn-sm eliminar-btn" data-id="${row.id}">Eliminar</button>
                    <button class="btn btn-warning btn-sm editar-btn" data-id="${row.id}" data-user='${JSON.stringify(row)}'>Editar</button>
                `;
            },
            title: "Ações"
        }
    ],
    onDelete: (id) => {
        eliminarUser(id);
    },
    onEdit: (id, user) => {
        // Ensure the user data is correctly parsed
        if (typeof user === 'string') {
            try {
                user = JSON.parse(user);
            } catch (error) {
                console.error('Error parsing user data:', error);
                return;
            }
        }
        editarUser(id, user);
    }
});

export function eliminarUser(userId) {
    SwalDialog.warning(
        'Irá eliminar o utilizador selecionado',
        'Tem certeza que deseja eliminar este utilizador?',
        () => {
            Users.deleteUser(userId).then(() => {
                SwalDialog.success(
                    'Utilizador eliminado com sucesso!',
                    '',
                    () => usersTableManager.reload(),
                    () => usersTableManager.reload()
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
    console.log('Document is ready');
    $('#addNew-user').on('click', ()=>{
        console.log('Button clicked');
        addNewUser()
    });

    if ($("#usersTable").length > 0) {
        usersTableManager.init();

        $('#searchInput').on('input', function () {
            usersTableManager.table.search($(this).val()).draw();
        });

        // Bind the "Editar" button click event
        $('#usersTable tbody').on('click', '.editar-btn', function () {
            const userId = $(this).data('id');
            let user = $(this).data('user');
            if (typeof user === 'string') {
                try {
                    user = JSON.parse(user);
                } catch (error) {
                    console.error('Error parsing user data:', error);
                    return;
                }
            }
            editarUser(userId, user);
        });
    }


    // Load roles and departments
    loadTagsAndDepartments();
});

async function loadTagsAndDepartments() {
    try {
        const [tagsResponse, departmentsResponse] = await Promise.all([
            Roles.getRoles(),
            Departments.getDepartments()
        ]);

        populateDropdown('#roleSelection', tagsResponse.data);
        populateDropdown('#departmentSelection', departmentsResponse.data);
    } catch (error) {
        console.error('Error loading roles or departments:', error);
    }
}

function populateDropdown(selector, data) {
    const dropdown = $(selector);
    dropdown.empty().append('<option value="" disabled selected>Selecione...</option>');
    data.forEach(item => {
        dropdown.append(`<option value="${item.id}">${item.name}</option>`);
    });
}

export async function editarUser(userId, user) {
    console.log('Editing user:', user); // Debugging line
    if (!user) {
        console.error('User data is undefined');
        return;
    }
    try {
        const [rolesData, departmentsData] = await Promise.all([
            Roles.getRoles(),
            Departments.getDepartments()
        ]);

        const roles = rolesData.data;
        const departments = departmentsData.data;

        const modalContent = buildUserForm(user, roles, departments);
        const modal = new Modal(
            '',
            modalContent,
            'Editar Utilizador',
            () => setupFormEvents(user, roles, departments),
            () => handleFormSubmission(userId, userRoles, userDepartments, uploadedImage)
        );
        modal.build();
    } catch (error) {
        console.error('Error editing user:', error);
        Swal.fire('Erro!', 'Não foi possível carregar os dados.', 'error');
    }
}

function buildUserForm(user, roles, departments) {
    return `
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
                <table id="role-list" class="table table-bordered">
                    <caption>Cargos</caption>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="padding: 0px">
                                <div class="d-flex flex-row">
                                    <select class="form-select col-lg-8" id="roleSelection" name="roles">
                                        <option value="" disabled selected>Selecione um cargo</option>
                                        ${roles.map(tag => `<option value="${tag.id}">${tag.name}</option>`).join('')}
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
    `;
}

let userRoles = [];
let userDepartments = [];
let uploadedImage = null;

function setupFormEvents(user, roles, departments) {
    userRoles = user.roles || [];
    userDepartments = user.departments || [];
    uploadedImage = null;

    function updateRoleList() {
        const roleListHtml = userRoles.map(tag => `
            <tr>
                <td>${tag.name}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-role" data-tag-id="${tag.id}">Remover</button></td>
            </tr>
        `).join('');
        $('#role-list tbody').html(roleListHtml);
    }

    function updateDepartmentList() {
        const departmentListHtml = userDepartments.name ? `
            <tr>
                <td>${userDepartments.name}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-department" data-department-id="${userDepartments.id}">Remover</button></td>
            </tr>
        ` : '';
        $('#department-list tbody').html(departmentListHtml);
    }

    updateRoleList();
    updateDepartmentList();

    $('#addRole').on('click', function () {
        const selectedtagId = $('#roleSelection').val();
        const selectedtag = roles.find(tag => tag.id == selectedtagId);
        if (selectedtag && !userRoles.some(r => r.id == selectedtagId)) {
            userRoles.push(selectedtag);
            updateRoleList();
        } else {
            alert('Este cargo já foi adicionado.');
        }
    });

    $('#role-list').on('click', '.remove-role', function () {
        const tagIdToRemove = $(this).data('tag-id');
        userRoles = userRoles.filter(tag => tag.id != tagIdToRemove);
        updateRoleList();
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

    $('#image-container').off('click').on('click', (event) => {
        event.stopPropagation();
        $('#userImage').click()
    });
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
}

function handleFormSubmission(userId, userRoles, userDepartments, uploadedImage) {
    // Extract values from the modal inputs
    const name = $('#swal-input-nome').val();
    const email = $('#swal-input-email').val();

    // Validate required fields
    if (!name || !email || userRoles.length === 0 || userDepartments.length === 0) {
        Swal.showValidationMessage('Nome, Email, pelo menos um Cargo e um Departamento são necessários!');
        return null;
    }

    // Create JSON payload
    const payload = JSON.stringify({
        name: name,
        email: email,
        roles: userRoles,
        departments: userDepartments,
    });

    // Send the JSON payload
    const userPromise = userId ? Users.updateUser(userId, payload) : Users.addUser(payload);

    userPromise
        .then((response) => {
            if (uploadedImage) {
                // Upload the image separately
                return uploadAvatar(userId == null ? response.data.id : userId, uploadedImage);
            }
            return response;
        })
        .then(() => {
            Swal.fire('Guardado!', 'Detalhes atualizados.', 'success');
            usersTableManager.reload();
        })
        .catch((error) => {
            console.error('Error:', error.responseJSON || error.responseText || error);
            Swal.fire('Erro!', 'Não foi possível guardar os detalhes.', 'error');
        });
}

function uploadAvatar(userId, file) {
    const formData = new FormData();
    formData.append('avatar', file);

    return Users.uploadAvatar(userId, formData);
}

export function addNewUser() {
    Roles.getRoles().then(function (rolesData) {
        Departments.getDepartments().then(function (response){
            const roles = rolesData.data;
            const departments = response.data;
            const modalContent = buildUserForm({ name: '', email: '', avatar: 'http://localhost/storage/images/avatar.png' }, roles, departments);
            const modal = new Modal(
                '',
                modalContent,
                'Adicionar Novo Utilizador',
                () => setupFormEvents({ roles: [], departments: [] }, roles, departments),
                () => handleFormSubmission(null, userRoles, userDepartments, uploadedImage)
            );
            modal.build();
        })
    });
}
