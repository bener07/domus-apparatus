import { API } from './api';

export class Disciplines extends API {
    static getDisciplines() {
        return API.makeAuthenticatedRequest('/api/disciplines', 'GET');
    }

    static getDisciplinesDataTables(data, callback) {
        const params = {
            start: data.start,
            length: data.length,
            search: data.search.value,
            orderColumn: data.order[0].column,
            orderDir: data.order[0].dir
        };
        return API.makeAuthenticatedRequest('/api/disciplines', 'GET', params, callback);
    }

    static getDiscipline(departmentId){
        return API.makeAuthenticatedRequest('/api/disciplines/' + departmentId, 'GET');
    }

    static updateDiscipline(departmentId, departmentData){
        return API.makeAuthenticatedRequest('/api/admin/disciplines/' + departmentId, 'PUT', departmentData);
    }

    static deleteDiscipline(departmentId){
        return API.makeAuthenticatedRequest('/api/admin/disciplines/' + departmentId, 'DELETE');
    }

    static createDiscipline(departmentData){
        return API.makeAuthenticatedRequest('/api/admin/disciplines', 'POST', departmentData);
    }
    static loadDisciplines(){
        Disciplines.getDisciplines().then((response)=>{
            window.disciplines = response.data;
            return response.data;
        });
    }
}