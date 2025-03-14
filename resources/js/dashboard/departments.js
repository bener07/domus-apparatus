import { Departments } from '../utilities/departments';
import { SwalDialog } from '../components/dialog';
import { DataTableManager } from '../components/tables';
import { Modal } from '../components/manager';
import { Users } from '../utilities/users';

// Initialize DataTable for departments
const departmentsTableManager = new DataTableManager('departmentsTable', {
    getData: (data, callback) => {
        Departments.getDepartmentsDataTables(data, callback).then(response => {
            if (response && response.data) {
                callback({
                    draw: data.draw, // Pass the draw parameter back to DataTables
                    recordsTotal: response.recordsTotal || response.data.length,
                    recordsFiltered: response.recordsFiltered || response.data.length,
                    data: response.data // Ensure this is an array of department objects
                });
            } else {
                console.error('Invalid API response format:', response);
                callback({
                    draw: data.draw,
                    recordsTotal: 0,
                    recordsFiltered: 0,
                    data: [] // Fallback to empty data
                });
            }
        }).catch(error => {
            console.error('Error fetching departments:', error);
            callback({
                draw: data.draw,
                recordsTotal: 0,
                recordsFiltered: 0,
                data: [] // Fallback to empty data
            });
        });
    },
    columns: [
        { data: "id" },
        { data: "name", title: "Nome" },
        { data: "description", title: "Descrição" }, // Uncomment if needed
        { 
            data: "manager",
            title: "Gerente",
            render: function (data){
                return data.name;
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `
                    <button class="btn btn-danger btn-sm eliminar-btn" data-id="${row.id}">Eliminar</button>
                    <button class="btn btn-warning btn-sm editar-btn" data-id="${row.id}" data-department='${JSON.stringify(row)}'>Editar</button>
                `;
            },
            title: "Ações"
        }
    ],
    onDelete: (id) => {
        eliminarDepartment(id);
    },
    onEdit: (id, department) => {
        if (typeof department === 'string') {
            try {
                department = JSON.parse(department);
            } catch (error) {
                console.error('Error parsing department data:', error);
                return;
            }
        }
        editarDepartment(id, department);
    }
});

// Function to delete a department
export function eliminarDepartment(departmentId) {
    SwalDialog.warning(
        'Irá eliminar o departamento selecionado',
        'Tem certeza que deseja eliminar este departamento?',
        () => {
            Departments.deleteDepartment(departmentId).then(() => {
                SwalDialog.success(
                    'Departamento eliminado com sucesso!',
                    '',
                    () => departmentsTableManager.reload(),
                    () => departmentsTableManager.reload()
                );
            }).catch(error => {
                console.error('Error deleting department:', error);
                Swal.fire('Erro!', 'Não foi possível eliminar o departamento.', 'error');
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

// Function to edit a department
export async function editarDepartment(departmentId, department) {
    console.log('Editing department:', department);
    if (!department) {
        console.error('Department data is undefined');
        return;
    }

    const modalContent = buildDepartmentForm(department);
    const modal = new Modal(
        '',
        modalContent,
        'Editar Departamento',
        () => setupFormEvents(department),
        () => handleFormSubmission(departmentId)
    );
    modal.build();
}

// Function to build the department form HTML
function buildDepartmentForm(department) {
    return `
        <div class="row">
            <div class="col-lg-8 d-flex flex-column">
                <label for="swal-input-nome" style="display:block; margin-bottom:5px;">Nome</label>
                <input id="swal-input-nome" class="swal2-input" value="${department.name || ''}">
                
                <label for="swal-input-descricao" style="display:block; margin-top:10px; margin-bottom:5px;">Descrição</label>
                <input id="swal-input-descricao" class="swal2-input" value="${department.description || ''}">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mt-4">
            <label style="display:block; margin-bottom:5px;">Selecione um Gestor</label>
                <div class="shadow-sm p-3 mb-5 bg-body-tertiary rounded">
                    <div id="manager"></div>
                </div>
                <div class="form-group">
                    <input type="text" id="userSearchInput" class="form-control" placeholder="Pesquisar...">
                </div>
                <table id="managersTable" class="table table-striped table-bordered" style="width:50%">
                    <thead>
                        <tr>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated dynamically by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

// Function to set up form events
// Function to set up form events
function setupFormEvents(department) {
    // Initialize DataTable for managers
    const managersTableManager = new DataTableManager('managersTable', {
        getData: (data, callback) => {
            Users.getUsers(data, callback).then(response => {
                if (response && response.data) {
                    callback({
                        draw: data.draw, // Pass the draw parameter back to DataTables
                        recordsTotal: response.recordsTotal || response.data.length,
                        recordsFiltered: response.recordsFiltered || response.data.length,
                        data: response.data // Ensure this is an array of user objects
                    });
                } else {
                    console.error('Invalid API response format:', response);
                    callback({
                        draw: data.draw,
                        recordsTotal: 0,
                        recordsFiltered: 0,
                        data: [] // Fallback to empty data
                    });
                }
            }).catch(error => {
                console.error('Error fetching users:', error);
                callback({
                    draw: data.draw,
                    recordsTotal: 0,
                    recordsFiltered: 0,
                    data: [] // Fallback to empty data
                });
            });
        },
        columns: [
            {
                data: null,
                render: function (user, type, row) {
                    return `
                        <div class="row">
                            <div class="col-md-2">
                                <img src="${user.avatar}" alt="${user.name}" style="width:50px; border-radius: 50px;">
                            </div>
                            <div class="col-md-6">
                                <h1>${user.name}</h1>
                                <p class="small-text muted-text">${user.email}</p>
                            </div>
                            <div class="col-md-4 d-flex justify-content-center align-items-center d-flex">
                                <button class="btn btn-primary btn-sm select-manager-btn" data-id="${user.id}" data-user='${JSON.stringify(user)}' data-name="${row.name}">Selecionar</button>
                            </div>
                        </div>
                    `;
                },
                title: "Utilizadores"
            }
        ],
        onDelete: null, // No delete functionality for this table
        onEdit: null // No edit functionality for this table
    });

    // Add search functionality
    $('#userSearchInput').on('input', function () {
        managersTableManager.table.search($(this).val()).draw();
    });

    // Handle manager selection
    $('#managersTable tbody').on('click', '.select-manager-btn', function () {
        const managerId = $(this).data('id');
        const managerName = $(this).data('name');
        const user = $(this).data('user');

        // Store the selected manager ID (you can use this in the form submission)
        $('#selectedManagerId').remove(); // Remove any existing hidden input
        $('<input>').attr({
            type: 'hidden',
            id: 'selectedManagerId',
            name: 'manager_id',
            value: managerId
        }).appendTo('form');
        $('#manager').html(`
            <div class="row">
                <div class="col-md-2">
                    <img src="${user.avatar}" alt="${user.name}" style="width:50px; border-radius: 50px">
                </div>
                <div class="col-md-6">
                    <h1>${user.name}</h1>
                    <p class="small-text muted-text">${user.email}</p>
                </div>
            </div>
        `);
    });

    // Initialize the managers table
    managersTableManager.init();
}

// Function to handle form submission
// Function to handle form submission
function handleFormSubmission(departmentId) {
    const name = $('#swal-input-nome').val();
    const description = $('#swal-input-descricao').val();
    const managerId = $('#selectedManagerId').val(); // Get the selected manager ID

    if (!name || !description || !managerId) {
        Swal.showValidationMessage('Nome, Descrição e Gestor são necessários!');
        return null;
    }

    const payload = JSON.stringify({
        id: departmentId,
        name: name,
        details: description,
        manager_id: managerId // Include the selected manager ID
    });

    const departmentPromise = departmentId ? Departments.updateDepartment(departmentId, payload) : Departments.addDepartment(payload);

    departmentPromise
        .then(() => {
            Swal.fire('Guardado!', 'Detalhes atualizados.', 'success');
            departmentsTableManager.reload();
        })
        .catch((error) => {
            console.error('Error:', error.responseJSON || error.responseText || error);
            Swal.fire('Erro!', 'Não foi possível guardar os detalhes.', 'error');
        });
}

// Function to add a new department
export function addNewDepartment() {
    const modalContent = buildDepartmentForm({ name: '', description: '' });
    const modal = new Modal(
        '',
        modalContent,
        'Adicionar Novo Departamento',
        () => setupFormEvents({}),
        () => handleFormSubmission(null)
    );
    modal.build();
}

// Document ready event
$(document).ready(() => {
    $('#addNew-department').on('click', () => {
        addNewDepartment();
    });

    if ($("#departmentsTable").length > 0) {
        departmentsTableManager.init();

        $('#searchInput').on('input', function () {
            departmentsTableManager.table.search($(this).val()).draw();
        });

        $('#departmentsTable tbody').on('click', '.editar-btn', function () {
            const departmentId = $(this).data('id');
            const department = $(this).data('department');
            if (typeof department === 'string') {
                try {
                    department = JSON.parse(department);
                } catch (error) {
                    console.error('Error parsing department data:', error);
                    return;
                }
            }
            editarDepartment(departmentId, department);
        });
    }
});