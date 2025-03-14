import { Roles } from '../utilities/roles';
import { SwalDialog } from '../components/dialog';
import { DataTableManager } from '../components/tables';
import { Modal } from '../components/manager';

const rolesTableManager = new DataTableManager('rolesTable', {
    getData: (data, callback) => {
        Roles.getRolesDataTables(data, callback).then(response => {
            if (response && response.data) {
                callback({
                    draw: data.draw, // Pass the draw parameter back to DataTables
                    recordsTotal: response.recordsTotal || response.data.length,
                    recordsFiltered: response.recordsFiltered || response.data.length,
                    data: response.data // Ensure this is an array of role objects
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
            console.error('Error fetching roles:', error);
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
            data: null,
            render: function (data, type, row) {
                console.log('Rendering row:', row); // Debugging
                return `
                    <button class="btn btn-danger btn-sm eliminar-btn" data-id="${row.id}">Eliminar</button>
                    <button class="btn btn-warning btn-sm editar-btn" data-id="${row.id}" data-role='${JSON.stringify(row)}'>Editar</button>
                `;
            },
            title: "Ações"
        }
    ],
    onDelete: (id) => {
        eliminarRole(id);
    },
    onEdit: (id, role) => {
        if (typeof role === 'string') {
            try {
                role = JSON.parse(role);
            } catch (error) {
                console.error('Error parsing role data:', error);
                return;
            }
        }
        editarRole(id, role);
    }
});

// Function to delete a role
export function eliminarRole(roleId) {
    SwalDialog.warning(
        'Irá eliminar o cargo selecionado',
        'Tem certeza que deseja eliminar este cargo?',
        () => {
            Roles.deleteRole(roleId).then(() => {
                SwalDialog.success(
                    'Cargo eliminado com sucesso!',
                    '',
                    () => rolesTableManager.reload(),
                    () => rolesTableManager.reload()
                );
            }).catch(error => {
                console.error('Error deleting role:', error);
                Swal.fire('Erro!', 'Não foi possível eliminar o cargo.', 'error');
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

// Function to edit a role
export async function editarRole(roleId, role) {
    console.log('Editing role:', role);
    if (!role) {
        console.error('Role data is undefined');
        return;
    }

    const modalContent = buildRoleForm(role);
    const modal = new Modal(
        '',
        modalContent,
        'Editar Cargo',
        () => setupFormEvents(role),
        () => handleFormSubmission(roleId)
    );
    modal.build();
}

// Function to build the role form HTML
function buildRoleForm(role) {
    return `
        <div class="row">
            <div class="col-lg-8 d-flex flex-column">
                <label for="swal-input-nome" style="display:block; margin-bottom:5px;">Nome</label>
                <input id="swal-input-nome" class="swal2-input" value="${role.name}">
                
                <label for="swal-input-descricao" style="display:block; margin-top:10px; margin-bottom:5px;">Descrição</label>
                <input id="swal-input-descricao" class="swal2-input" value="${role.description}">
            </div>
        </div>
    `;
}

// Function to set up form events
function setupFormEvents(role) {
    // No additional setup needed for roles
}

// Function to handle form submission
function handleFormSubmission(roleId) {
    const name = $('#swal-input-nome').val();
    const description = $('#swal-input-descricao').val();

    if (!name || !description) {
        Swal.showValidationMessage('Nome e Descrição são necessários!');
        return null;
    }

    const payload = JSON.stringify({
        id: roleId,
        name: name,
        description: description,
    });

    const rolePromise = roleId ? Roles.updateRole(roleId, payload) : Roles.addRole(payload);

    rolePromise
        .then(() => {
            Swal.fire('Guardado!', 'Detalhes atualizados.', 'success');
            rolesTableManager.reload();
        })
        .catch((error) => {
            console.error('Error:', error.responseJSON || error.responseText || error);
            Swal.fire('Erro!', 'Não foi possível guardar os detalhes.', 'error');
        });
}

// Function to add a new role
export function addNewRole() {
    const modalContent = buildRoleForm({ name: '', description: '' });
    const modal = new Modal(
        '',
        modalContent,
        'Adicionar Novo Cargo',
        () => setupFormEvents({}),
        () => handleFormSubmission(null)
    );
    modal.build();
}

// Document ready event
$(document).ready(() => {
    $('#addNew-role').on('click', () => {
        addNewRole();
    });

    if ($("#rolesTable").length > 0) {
        rolesTableManager.init();

        $('#searchInput').on('input', function () {
            rolesTableManager.table.search($(this).val()).draw();
        });

        $('#rolesTable tbody').on('click', '.editar-btn', function () {
            const roleId = $(this).data('id');
            const role = $(this).data('role');
            if (typeof role === 'string') {
                try {
                    role = JSON.parse(role);
                } catch (error) {
                    console.error('Error parsing role data:', error);
                    return;
                }
            }
            editarRole(roleId, role);
        });
    }
});