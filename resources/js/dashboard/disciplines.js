import { Disciplines } from '../utilities/disciplines';
import { Departments } from '../utilities/departments';
import { SwalDialog } from '../components/dialog';
import { DataTableManager } from '../components/tables';
import { Modal } from '../components/manager';

const disciplinesTableManager = new DataTableManager('disciplinesTable', {
    getData: (data, callback) => {
        Disciplines.getDisciplinesDataTables(data, callback);
    },
    columns: [
        { data: "id" },
        {data: "name", title: "Nome" },
        { data: 'details', title: 'Detalhes'},
        {
            data: "department",
            title: "Departamento",
            render: function (data) {
                return data ? data.name : '';
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `
                    <button class="btn btn-danger btn-sm eliminar-btn" data-id="${row.id}">Eliminar</button>
                    <button class="btn btn-warning btn-sm editar-btn" data-id="${row.id}" data-discipline='${JSON.stringify(row)}'>Editar</button>
                `;
            },
            title: "Ações"
        }
    ],
    onDelete: (id) => {
        eliminarDiscipline(id);
    },
    onEdit: (id, discipline) => {
        if (typeof discipline === 'string') {
            try {
                discipline = JSON.parse(discipline);
            } catch (error) {
                console.error('Error parsing discipline data:', error);
                return;
            }
        }
        editarDiscipline(id, discipline);
    }
});

// Variáveis globais para armazenar seleções
let disciplineDepartment = null;
let allDepartments = [];

export function eliminarDiscipline(disciplineId) {
    SwalDialog.warning(
        'Irá eliminar a disciplina selecionada',
        'Tem certeza que deseja eliminar esta disciplina?',
        () => {
            Disciplines.deleteDiscipline(disciplineId).then(() => {
                SwalDialog.success(
                    'Disciplina eliminada com sucesso!',
                    '',
                    () => disciplinesTableManager.reload(),
                    () => disciplinesTableManager.reload()
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

async function loadDepartments() {
    try {
        const departmentsResponse = await Departments.getDepartments();
        allDepartments = departmentsResponse.data || [];

        console.log('Departments loaded:', allDepartments);

        if ($('#departmentSelection').length) {
            populateDropdown('#departmentSelection', allDepartments);
        }
    } catch (error) {
        console.error('Error loading departments:', error);
        Swal.fire('Erro!', 'Não foi possível carregar departamentos.', 'error');
    }
}

function populateDropdown(selector, data) {
    const dropdown = $(selector);
    dropdown.empty().append('<option value="" disabled selected>Selecione...</option>');
    data.forEach(item => {
        dropdown.append(`<option value="${item.id}">${item.name}</option>`);
    });
}

export async function editarDiscipline(disciplineId, discipline) {
    console.log('Editing discipline:', discipline);
    if (!discipline) {
        console.error('Discipline data is undefined');
        return;
    }

    try {
        await loadDepartments();

        // Inicializa as seleções com os dados existentes
        disciplineDepartment = discipline.department || null;

        const modalContent = buildDisciplineForm(discipline);
        const modal = new Modal(
            '',
            modalContent,
            disciplineId ? 'Editar Disciplina' : 'Adicionar Nova Disciplina',
            () => setupFormEvents(discipline),
            () => handleFormSubmission(disciplineId)
        );
        modal.build();
    } catch (error) {
        console.error('Error editing discipline:', error);
        Swal.fire('Erro!', 'Não foi possível carregar os dados.', 'error');
    }
}

function buildDisciplineForm(discipline = {}) {
    return `
        <div class="row">
            <div class="col-lg-12 d-flex flex-column align-items-center">
                <label for="swal-input-nome" style="display:block; margin-bottom:5px;">Nome</label>
                <input id="swal-input-nome" class="swal2-input" value="${discipline.name || ''}" placeholder="Nome da disciplina">
                <label for="swal-input-details" style="display:block; margin-top:10px; margin-bottom:5px;">Detalhes</label>
                <textarea id="swal-input-details" class="swal2-textarea" rows="3" placeholder="Detalhes da disciplina">${discipline.details || ''}</textarea>
                <table id="department-list" class="table table-bordered">
                    <caption>Departamento</caption>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="padding: 0px">
                                <div class="d-flex flex-row">
                                    <select class="form-select col-lg-8" id="departmentSelection" name="departments">
                                        <option value="" disabled selected>Selecione um departamento</option>
                                        ${allDepartments.map(dept => `
                                            <option value="${dept.id}" ${disciplineDepartment && disciplineDepartment.id === dept.id ? 'selected' : ''}>
                                                ${dept.name}
                                            </option>
                                        `).join('')}
                                    </select>
                                    <button id="addDepartment" type="button" class="btn btn-primary col-lg-4" style="border-radius: 0 10px 10px 0px;">Adicionar</button>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    `;
}

function setupFormEvents(discipline) {
    function updateDepartmentList() {
        const departmentListHtml = disciplineDepartment ? `
            <tr>
                <td>${disciplineDepartment.name}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-department" data-department-id="${disciplineDepartment.id}">Remover</button></td>
            </tr>
        ` : '';
        $('#department-list tbody').html(departmentListHtml);
    }

    updateDepartmentList();

    $('#addDepartment').on('click', function() {
        const selectedDeptId = $('#departmentSelection').val();
        const selectedDept = allDepartments.find(dept => dept.id == selectedDeptId);
        
        if (selectedDept) {
            disciplineDepartment = selectedDept;
            updateDepartmentList();
        }
    });

    $('#department-list').on('click', '.remove-department', function() {
        disciplineDepartment = null;
        updateDepartmentList();
    });
}

function handleFormSubmission(disciplineId) {
    const name = $('#swal-input-nome').val();
    const details = $('#swal-input-details').val();

    if (!name || !details || !disciplineDepartment) {
        Swal.showValidationMessage('Por favor, preencha todos os campos obrigatórios!');
        return null;
    }
    
    const payload = JSON.stringify({
        name: name,
        details: details,
        department_id: disciplineDepartment ? disciplineDepartment.id : null
    });

    const disciplinePromise = disciplineId ? 
        Disciplines.updateDiscipline(disciplineId, payload) : 
        Disciplines.createDiscipline(payload);

    disciplinePromise
        .then(() => {
            Swal.fire('Sucesso!', 'Disciplina salva com sucesso.', 'success');
            disciplinesTableManager.reload();
        })
        .catch((error) => {
            console.error('Error:', error);
            Swal.fire('Erro!', 'Ocorreu um erro ao salvar a disciplina.', 'error');
        });
}

export async function addNewDiscipline() {
    try {
        await loadDepartments();
        
        if (allDepartments.length === 0) {
            Swal.fire('Aviso', 'Por favor, cadastre departamentos primeiro.', 'warning');
            return;
        }

        const modalContent = buildDisciplineForm({
            name: '',
            department: null
        });

        const modal = new Modal(
            '',
            modalContent,
            'Adicionar Nova Disciplina',
            () => setupFormEvents(),
            () => handleFormSubmission(null)
        );
        
        modal.build();
    } catch (error) {
        console.error('Error adding new discipline:', error);
        Swal.fire('Erro', 'Não foi possível carregar o formulário.', 'error');
    }
}

$(document).ready(() => {
    console.log('Document is ready');
    $('#addNew-discipline').on('click', () => {
        console.log('Button clicked');
        addNewDiscipline();
    });

    if ($("#disciplinesTable").length > 0) {
        disciplinesTableManager.init();

        $('#searchInput').on('input', function() {
            disciplinesTableManager.table.search($(this).val()).draw();
        });

        $('#disciplinesTable tbody').on('click', '.editar-btn', function() {
            const disciplineId = $(this).data('id');
            let discipline = $(this).data('discipline');
            if (typeof discipline === 'string') {
                try {
                    discipline = JSON.parse(discipline);
                } catch (error) {
                    console.error('Error parsing discipline data:', error);
                    return;
                }
            }
            editarDiscipline(disciplineId, discipline);
        });
    }
});