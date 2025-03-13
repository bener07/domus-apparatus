import { Users } from '../utilities/users';
import { Tags } from '../utilities/tags';
import { Departments } from '../utilities/departments';
import { SwalDialog } from '../utilities/dialog';
import { DataTableManager } from '../utilities/tables';
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
            render: (data) => {
                return data || ''; // Handle missing roles
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
    if ($("#usersTable").length > 0) {
        usersTableManager.init();

        $('#searchInput').on('input', function () {
            usersTableManager.table.search($(this).val()).draw();
        });

        // Bind the "Editar" button click event
        $('#usersTable tbody').on('click', '.editar-btn', function () {
            const userId = $(this).data('id');
            const user = $(this).data('user');
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

    $('#addNewBtn').on('click', addNewUser);

    // Load tags and departments
    loadTagsAndDepartments();
});

async function loadTagsAndDepartments() {
    try {
        const [tagsResponse, departmentsResponse] = await Promise.all([
            Tags.getTags(),
            Departments.getDepartments()
        ]);

        populateDropdown('#tagsSelection', tagsResponse.data);
        populateDropdown('#departmentSelection', departmentsResponse.data);
    } catch (error) {
        console.error('Error loading tags or departments:', error);
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
        const [tagsData, departmentsData] = await Promise.all([
            fetchData('/api/tags'),
            fetchData('/api/departments')
        ]);

        const tags = tagsData.data;
        const departments = departmentsData.data;

        const modalContent = buildUserForm(user, tags, departments);
        const modal = new Modal(
            '',
            modalContent,
            'Editar Utilizador',
            () => setupFormEvents(user, tags, departments),
            () => handleFormSubmission(userId, usertags, userDepartments, uploadedImage)
        );
        modal.build();
    } catch (error) {
        console.error('Error editing user:', error);
        Swal.fire('Erro!', 'Não foi possível carregar os dados.', 'error');
    }
}

function buildUserForm(user, tags, departments) {
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
    `;
}

let usertags = [];
let userDepartments = [];
let uploadedImage = null;

function setupFormEvents(user, tags, departments) {
    usertags = user.tags || [];
    userDepartments = user.departments || [];
    uploadedImage = null;

    function updatetagList() {
        const tagListHtml = usertags.map(tag => `
            <tr>
                <td>${tag.name}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-tag" data-tag-id="${tag.id}">Remover</button></td>
            </tr>
        `).join('');
        $('#tag-list tbody').html(tagListHtml);
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
}

function handleFormSubmission(userId, usertags, userDepartments, uploadedImage) {
    // Extract values from the modal inputs
    const name = $('#swal-input-nome').val();
    const email = $('#swal-input-email').val();

    // Validate required fields
    if (!name || !email || usertags.length === 0 || userDepartments.length === 0) {
        Swal.showValidationMessage('Nome, Email, pelo menos um Cargo e um Departamento são necessários!');
        return null;
    }

    // Create FormData object
    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('tags', JSON.stringify(usertags));
    formData.append('departments', JSON.stringify(userDepartments));
    if (uploadedImage) {
        formData.append('avatar', uploadedImage);
    }

    // Log FormData for debugging
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    // Send the request to the server
    if (userId) {
        // Update existing user
        Users.updateUser(userId, formData)
            .then(() => {
                Swal.fire('Guardado!', 'Detalhes atualizados.', 'success');
                usersTableManager.reload();
            })
            .catch((error) => {
                // Log the full error response
                console.error('Update error:', error.responseJSON || error.responseText || error);
                Swal.fire('Erro!', 'Não foi possível guardar os detalhes.', 'error');
            });
    } else {
        // Add new user
        Users.addUser(formData)
            .then(() => {
                Swal.fire('Guardado!', 'Utilizador adicionado com sucesso.', 'success');
                usersTableManager.reload();
            })
            .catch((error) => {
                // Log the full error response
                console.error('Add user error:', error.responseJSON || error.responseText || error);
                Swal.fire('Erro!', 'Não foi possível adicionar o utilizador.', 'error');
            });
    }
}

export function addNewUser() {
    $.ajax({
        url: '/api/tags',
        method: 'GET',
        dataType: 'json',
        success: (tagsData) => {
            const tags = tagsData.data;
            const modalContent = buildUserForm({ name: '', email: '', avatar: 'http://localhost/storage/images/avatar.png' }, tags, []);
            const modal = new Modal(
                '',
                modalContent,
                'Adicionar Novo Utilizador',
                () => setupFormEvents({ tags: [], departments: [] }, tags, []),
                () => handleFormSubmission(null, usertags, userDepartments, uploadedImage)
            );
            modal.build();
        },
        error: (xhr, status, error) => {
            console.error('Error fetching tags:', error);
            Swal.fire('Erro!', 'Não foi possível carregar os cargos.', 'error');
        }
    });
}

async function fetchData(url) {
    const response = await $.ajax({
        url,
        method: 'GET',
        dataType: 'json'
    });
    return response;
}