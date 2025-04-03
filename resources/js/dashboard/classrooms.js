import { Classrooms } from '../utilities/classrooms';
import { Departments } from '../utilities/departments';
import { Disciplines } from '../utilities/disciplines';
import { SwalDialog } from '../components/dialog';
import { DataTableManager } from '../components/tables';
import { Modal } from '../components/manager';

const classroomsTableManager = new DataTableManager('classroomsTable', {
    getData: (data, callback) => {
        Classrooms.getClassroomsDataTables(data, callback);
    },
    columns: [
        { data: "id" },
        { 
            data: "name",
            title: "Nome"
        },
        { 
            data: "capacity",
            title: "Capacidade" 
        },
        {
            data: "location", 
            title: "Localização"
        },
        { 
            data: "department",
            title: "Departamento",
            render: function (data) {
                return data ? data.name : '';
            }
        },
        { 
            data: "disciplines",
            title: "Disciplinas",
            render: function(data) {
                if (Array.isArray(data) && data.length > 0) {
                    return data.map(discipline => discipline.name).join(', ');
                }
                return '';
            }
        },
        {
            data: null,
            render: function (data, type, row) {
                return `
                    <button class="btn btn-danger btn-sm eliminar-btn" data-id="${row.id}">Eliminar</button>
                    <button class="btn btn-warning btn-sm editar-btn" data-id="${row.id}" data-classroom='${JSON.stringify(row)}'>Editar</button>
                `;
            },
            title: "Ações"
        }
    ],
    onDelete: (id) => {
        eliminarClassroom(id);
    },
    onEdit: (id, classroom) => {
        if (typeof classroom === 'string') {
            try {
                classroom = JSON.parse(classroom);
            } catch (error) {
                console.error('Error parsing classroom data:', error);
                return;
            }
        }
        editarClassroom(id, classroom);
    }
});

// Variáveis globais para armazenar seleções
let classroomDepartments = null;
let classroomDisciplines = [];
let allDepartments = [];
let allDisciplines = [];

export function eliminarClassroom(classroomId) {
    SwalDialog.warning(
        'Irá eliminar a sala de aula selecionada',
        'Tem certeza que deseja eliminar esta sala de aula?',
        () => {
            Classrooms.deleteClassroom(classroomId).then(() => {
                SwalDialog.success(
                    'Sala de aula eliminada com sucesso!',
                    '',
                    () => classroomsTableManager.reload(),
                    () => classroomsTableManager.reload()
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

async function loadDepartmentsAndDisciplines() {
    try {
        const [departmentsResponse, disciplinesResponse] = await Promise.all([
            Departments.getDepartments(),
            Disciplines.getDisciplines()
        ]);

        allDepartments = departmentsResponse.data || [];
        allDisciplines = disciplinesResponse.data || [];

        console.log('Departments loaded:', allDepartments); // Verifique no console
        console.log('Disciplines loaded:', allDisciplines); // Verifique no console

        // Verifique se os selects existem no DOM antes de popular
        if ($('#departmentSelection').length && $('#disciplineSelection').length) {
            populateDropdown('#departmentSelection', allDepartments);
            populateDropdown('#disciplineSelection', allDisciplines);
        }
    } catch (error) {
        console.error('Error loading departments or disciplines:', error);
        Swal.fire('Erro!', 'Não foi possível carregar departamentos ou disciplinas.', 'error');
    }
}

function populateDropdown(selector, data) {
    const dropdown = $(selector);
    dropdown.empty().append('<option value="" disabled selected>Selecione...</option>');
    data.forEach(item => {
        dropdown.append(`<option value="${item.id}">${item.name}</option>`);
    });
}

export async function editarClassroom(classroomId, classroom) {
    console.log('Editing classroom:', classroom);
    if (!classroom) {
        console.error('Classroom data is undefined');
        return;
    }

    try {
        await loadDepartmentsAndDisciplines();

        // Inicializa as seleções com os dados existentes
        classroomDepartments = classroom.department || null;
        classroomDisciplines = classroom.disciplines || [];

        const modalContent = buildClassroomForm(classroom);
        const modal = new Modal(
            '',
            modalContent,
            classroomId ? 'Editar Sala de Aula' : 'Adicionar Nova Sala',
            () => setupFormEvents(classroom),
            () => handleFormSubmission(classroomId)
        );
        modal.build();
    } catch (error) {
        console.error('Error editing classroom:', error);
        Swal.fire('Erro!', 'Não foi possível carregar os dados.', 'error');
    }
}

function buildClassroomForm(classroom = {}) {
    return `
        <div class="row">
            <div class="col-lg-6 d-flex flex-column">
                <label for="swal-input-nome" style="display:block; margin-bottom:5px;">Nome</label>
                <input id="swal-input-nome" class="swal2-input" value="${classroom.name || ''}" placeholder="Nome da sala">
                
                <label for="swal-input-capacity" style="display:block; margin-top:10px; margin-bottom:5px;">Capacidade</label>
                <input id="swal-input-capacity" class="swal2-input" type="number" min="1" value="${classroom.capacity || ''}" placeholder="Número de lugares">
                
                <label for="swal-input-location" style="display:block; margin-top:10px; margin-bottom:5px;">Localização</label>
                <input id="swal-input-location" class="swal2-input" value="${classroom.location || ''}" placeholder="Ex: Piso 1, Ala Norte">
            </div>
            
            <div class="col-lg-6">
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
                                            <option value="${dept.id}" ${classroomDepartments && classroomDepartments.id === dept.id ? 'selected' : ''}>
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
                
                <table id="discipline-list" class="table table-bordered mt-3">
                    <caption>Disciplinas</caption>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="padding: 0px">
                                <div class="d-flex flex-row">
                                    <select class="form-select col-lg-8" id="disciplineSelection" name="disciplines">
                                        <option value="" disabled selected>Selecione uma disciplina</option>
                                        ${allDisciplines.map(discipline => `
                                            <option value="${discipline.id}">
                                                ${discipline.name}
                                            </option>
                                        `).join('')}
                                    </select>
                                    <button id="addDiscipline" type="button" class="btn btn-primary col-lg-4" style="border-radius: 0 10px 10px 0px;">Adicionar</button>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    `;
}

function setupFormEvents(classroom) {
    function updateDepartmentList() {
        const departmentListHtml = classroomDepartments ? `
            <tr>
                <td>${classroomDepartments.name}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-department" data-department-id="${classroomDepartments.id}">Remover</button></td>
            </tr>
        ` : '';
        $('#department-list tbody').html(departmentListHtml);
    }

    function updateDisciplineList() {
        const disciplineListHtml = classroomDisciplines.map(discipline => `
            <tr>
                <td>${discipline.name}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-discipline" data-discipline-id="${discipline.id}">Remover</button></td>
            </tr>
        `).join('');
        $('#discipline-list tbody').html(disciplineListHtml);
    }

    updateDepartmentList();
    updateDisciplineList();

    $('#addDepartment').on('click', function() {
        const selectedDeptId = $('#departmentSelection').val();
        const selectedDept = allDepartments.find(dept => dept.id == selectedDeptId);
        
        if (selectedDept) {
            classroomDepartments = selectedDept;
            updateDepartmentList();
        }
    });

    $('#department-list').on('click', '.remove-department', function() {
        classroomDepartments = null;
        updateDepartmentList();
    });

    $('#addDiscipline').on('click', function() {
        const selectedDisciplineId = $('#disciplineSelection').val();
        const selectedDiscipline = allDisciplines.find(d => d.id == selectedDisciplineId);
        
        if (selectedDiscipline && !classroomDisciplines.some(d => d.id == selectedDisciplineId)) {
            classroomDisciplines.push(selectedDiscipline);
            updateDisciplineList();
        } else {
            alert('Esta disciplina já foi adicionada.');
        }
    });

    $('#discipline-list').on('click', '.remove-discipline', function() {
        const disciplineIdToRemove = $(this).data('discipline-id');
        classroomDisciplines = classroomDisciplines.filter(d => d.id != disciplineIdToRemove);
        updateDisciplineList();
    });
}

function handleFormSubmission(classroomId) {
    const name = $('#swal-input-nome').val();
    const capacity = $('#swal-input-capacity').val();
    const location = $('#swal-input-location').val();
    const description = $('#swal-input-description').val();

    if (!name || !capacity || !location || !classroomDepartments) {
        Swal.showValidationMessage('Por favor, preencha todos os campos obrigatórios!');
        return null;
    }

    // Preparar array apenas com IDs das disciplinas
    const disciplineIds = classroomDisciplines.map(d => d.id);
    
    // Criar payload apenas com IDs
    const payload = JSON.stringify({
        name: name,
        capacity: parseInt(capacity),
        location: location,
        description: description,
        department_id: classroomDepartments ? classroomDepartments.id : null, // Envia apenas o ID
        discipline_ids: disciplineIds // Envia array de IDs
    });

    const classroomPromise = classroomId ? 
        Classrooms.updateClassroom(classroomId, payload) : 
        Classrooms.addClassroom(payload);

    classroomPromise
        .then(() => {
            Swal.fire('Sucesso!', 'Sala de aula salva com sucesso.', 'success');
            classroomsTableManager.reload();
        })
        .catch((error) => {
            console.error('Error:', error);
            Swal.fire('Erro!', 'Ocorreu um erro ao salvar a sala de aula.', 'error');
        });
}

export async function addNewClassroom() {
    try {
        // Carrega os dados primeiro
        await loadDepartmentsAndDisciplines();
        
        // Verifica se temos dados
        if (allDepartments.length === 0 || allDisciplines.length === 0) {
            Swal.fire('Aviso', 'Por favor, cadastre departamentos e disciplinas primeiro.', 'warning');
            return;
        }

        const modalContent = buildClassroomForm({
            name: '',
            capacity: '',
            location: '',
            description: '',
            department: null,
            disciplines: []
        });

        const modal = new Modal(
            '',
            modalContent,
            'Adicionar Nova Sala',
            () => setupFormEvents(),
            () => handleFormSubmission(null)
        );
        
        modal.build();
    } catch (error) {
        console.error('Error adding new classroom:', error);
        Swal.fire('Erro', 'Não foi possível carregar o formulário.', 'error');
    }
}

$(document).ready(() => {
    console.log('Document is ready');
    $('#addNew-classroom').on('click', () => {
        console.log('Button clicked');
        addNewClassroom();
    });

    if ($("#classroomsTable").length > 0) {
        classroomsTableManager.init();

        $('#searchInput').on('input', function() {
            classroomsTableManager.table.search($(this).val()).draw();
        });

        $('#classroomsTable tbody').on('click', '.editar-btn', function() {
            const classroomId = $(this).data('id');
            let classroom = $(this).data('classroom');
            if (typeof classroom === 'string') {
                try {
                    classroom = JSON.parse(classroom);
                } catch (error) {
                    console.error('Error parsing classroom data:', error);
                    return;
                }
            }
            editarClassroom(classroomId, classroom);
        });
    }
});