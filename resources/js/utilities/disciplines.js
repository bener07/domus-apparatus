import { API } from './api';

export class Disciplines extends API {
    static getDisciplines() {
        return API.makeAuthenticatedRequest('/api/disciplines', 'GET');
    }

    static getDiscipline(departmentId){
        return API.makeAuthenticatedRequest('/api/disciplines/' + departmentId, 'GET');
    }

    static updateDiscipline(departmentId, departmentData){
        return API.makeAuthenticatedRequest('/api/disciplines/' + departmentId, 'PUT', departmentData);
    }

    static deleteDiscipline(departmentId){
        return API.makeAuthenticatedRequest('/api/disciplines/' + departmentId, 'DELETE');
    }

    static createDiscipline(departmentData){
        return API.makeAuthenticatedRequest('/api/disciplines', 'POST', departmentData);
    }
    static loadDisciplines(){
        Disciplines.getDisciplines().then((response)=>{
            window.disciplines = response.data;
            return response.data;
        });
    }
}