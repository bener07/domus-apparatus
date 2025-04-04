import { API } from './api';

export class Classrooms extends API {
    static getClassrooms() {
        return API.makeAuthenticatedRequest('/api/classrooms', 'GET');
    }

    static getClassroomsDataTables(data, callback) {
        var data = {
            start: data.start,
            length: data.length,
            search: data.search.value,
            orderColumn: data.order[0].column,
            orderDir: data.order[0].dir
        }
        return API.makeAuthenticatedRequest('/api/classrooms', 'GET', data, callback);
    }

    static getClassroom(departmentId){
        return API.makeAuthenticatedRequest('/api/classrooms/' + departmentId, 'GET');
    }

    static updateClassroom(departmentId, departmentData){
        return API.makeAuthenticatedRequest('/api/admin/classrooms/' + departmentId, 'PUT', departmentData);
    }

    static deleteClassroom(departmentId){
        return API.makeAuthenticatedRequest('/api/admin/classrooms/' + departmentId, 'DELETE');
    }

    static addClassroom(departmentData){
        return API.makeAuthenticatedRequest('/api/admin/classrooms', 'POST', departmentData);
    }
    static loadClassrooms(){
        Classrooms.getClassrooms().then((response)=>{
            window.classes = response.data;
            return response.data;
        });
    }
}