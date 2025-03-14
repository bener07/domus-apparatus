import { Tags } from '../utilities/tags';
import { SwalDialog } from '../components/dialog';
import { DataTableManager } from '../components/tables';
import { Modal } from '../components/manager';
import { Users } from '../utilities/users';

// Initialize DataTable for tags
const tagsTableManager = new DataTableManager('tagsTable', {
    getData: (data, callback) => {
        Tags.getTags(data, callback).then(response => {
            if (response && response.data) {
                callback({
                    draw: data.draw, // Pass the draw parameter back to DataTables
                    recordsTotal: response.recordsTotal || response.data.length,
                    recordsFiltered: response.recordsFiltered || response.data.length,
                    data: response.data // Ensure this is an array of tag objects
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
            console.error('Error fetching tags:', error);
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
        { data: "description", title: "Descrição" },
        { 
            data: "owner",
            title: "Proprietário",
            render: function (data) {
                return data ? data.name : 'Nenhum';
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `
                    <button class="btn btn-danger btn-sm eliminar-btn" data-id="${row.id}">Eliminar</button>
                    <button class="btn btn-warning btn-sm editar-btn" data-id="${row.id}" data-tag='${JSON.stringify(row)}'>Editar</button>
                `;
            },
            title: "Ações"
        }
    ],
    onDelete: (id) => {
        eliminarTag(id);
    },
    onEdit: (id, tag) => {
        if (typeof tag === 'string') {
            try {
                tag = JSON.parse(tag);
            } catch (error) {
                console.error('Error parsing tag data:', error);
                return;
            }
        }
        editarTag(id, tag);
    }
});

// Function to delete a tag
export function eliminarTag(tagId) {
    SwalDialog.warning(
        'Irá eliminar a tag selecionada',
        'Tem certeza que deseja eliminar esta tag?',
        () => {
            Tags.deleteTag(tagId).then(() => {
                SwalDialog.success(
                    'Tag eliminada com sucesso!',
                    '',
                    () => tagsTableManager.reload(),
                    () => tagsTableManager.reload()
                );
            }).catch(error => {
                console.error('Error deleting tag:', error);
                Swal.fire('Erro!', 'Não foi possível eliminar a tag.', 'error');
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

// Function to edit a tag
export async function editarTag(tagId, tag) {
    console.log('Editing tag:', tag);
    if (!tag) {
        console.error('Tag data is undefined');
        return;
    }

    const modalContent = buildTagForm(tag);
    const modal = new Modal(
        '',
        modalContent,
        'Editar Tag',
        () => setupFormEvents(tag),
        () => handleFormSubmission(tagId)
    );
    modal.build();
}

// Function to build the tag form HTML
function buildTagForm(tag) {
    return `
        <div class="row">
            <div class="col-lg-8 d-flex flex-column">
                <label for="swal-input-nome" style="display:block; margin-bottom:5px;">Nome</label>
                <input id="swal-input-nome" class="swal2-input" value="${tag.name || ''}">
                
                <label for="swal-input-descricao" style="display:block; margin-top:10px; margin-bottom:5px;">Descrição</label>
                <input id="swal-input-descricao" class="swal2-input" value="${tag.description || ''}">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mt-4">
                <label style="display:block; margin-bottom:5px;">Selecione um Proprietário</label>
                <div class="shadow-sm p-3 mb-5 bg-body-tertiary rounded">
                    <div id="owner"></div>
                </div>
                <div class="form-group">
                    <input type="text" id="userSearchInput" class="form-control" placeholder="Pesquisar...">
                </div>
                <table id="ownersTable" class="table table-striped table-bordered" style="width:50%">
                    <thead>
                        <tr>
                            <th>Utilizador</th>
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
function setupFormEvents(tag) {
    // Initialize DataTable for owners
    const ownersTableManager = new DataTableManager('ownersTable', {
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
                            <div class="col-md-4 d-flex justify-content-center align-items-center">
                                <button class="btn btn-primary btn-sm select-owner-btn" data-id="${user.id}" data-user='${JSON.stringify(user)}' data-name="${row.name}">Selecionar</button>
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
        ownersTableManager.table.search($(this).val()).draw();
    });

    // Handle owner selection
    $('#ownersTable tbody').on('click', '.select-owner-btn', function () {
        const ownerId = $(this).data('id');
        const ownerName = $(this).data('name');
        const user = $(this).data('user');

        // Store the selected owner ID (you can use this in the form submission)
        $('#selectedOwnerId').remove(); // Remove any existing hidden input
        $('<input>').attr({
            type: 'hidden',
            id: 'selectedOwnerId',
            name: 'owner_id',
            value: ownerId
        }).appendTo('form');
        $('#owner').html(`
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

    // Initialize the owners table
    ownersTableManager.init();
}

// Function to handle form submission
function handleFormSubmission(tagId) {
    const name = $('#swal-input-nome').val();
    const description = $('#swal-input-descricao').val();
    const ownerId = $('#selectedOwnerId').val(); // Get the selected owner ID

    if (!name || !description || !ownerId) {
        Swal.showValidationMessage('Nome, Descrição e Proprietário são necessários!');
        return null;
    }

    const payload = JSON.stringify({
        id: tagId,
        name: name,
        description: description,
        owner_id: ownerId // Include the selected owner ID
    });

    const tagPromise = tagId ? Tags.updateTag(tagId, payload) : Tags.addTag(payload);

    tagPromise
        .then(() => {
            Swal.fire('Guardado!', 'Detalhes atualizados.', 'success');
            tagsTableManager.reload();
        })
        .catch((error) => {
            console.error('Error:', error.responseJSON || error.responseText || error);
            Swal.fire('Erro!', 'Não foi possível guardar os detalhes.', 'error');
        });
}

// Function to add a new tag
export function addNewTag() {
    const modalContent = buildTagForm({ name: '', description: '' });
    const modal = new Modal(
        '',
        modalContent,
        'Adicionar Nova Tag',
        () => setupFormEvents({}),
        () => handleFormSubmission(null)
    );
    modal.build();
}

// Document ready event
$(document).ready(() => {
    $('#addNewTagBtn').on('click', () => {
        addNewTag();
    });

    if ($("#tagsTable").length > 0) {
        tagsTableManager.init();

        $('#searchInput').on('input', function () {
            tagsTableManager.table.search($(this).val()).draw();
        });

        $('#tagsTable tbody').on('click', '.editar-btn', function () {
            const tagId = $(this).data('id');
            const tag = $(this).data('tag');
            if (typeof tag === 'string') {
                try {
                    tag = JSON.parse(tag);
                } catch (error) {
                    console.error('Error parsing tag data:', error);
                    return;
                }
            }
            editarTag(tagId, tag);
        });
    }
});