import { API } from './api';

export class Departments extends API {
    static getDepartments() {
        return API.makeAuthenticatedRequest('/api/departments', 'GET');
    }

    static getDepartmentsDataTables(data, callback) {
        var data = {
            start: data.start,
            length: data.length,
            search: data.search.value,
            orderColumn: data.order[0].column,
            orderDir: data.order[0].dir
        }
        return API.makeAuthenticatedRequest('/api/departments', 'GET', data, callback);
    }

    static getDepartment(departmentId){
        return API.makeAuthenticatedRequest('/api/departments/' + departmentId, 'GET');
    }

    static updateDepartment(departmentId, departmentData){
        return API.makeAuthenticatedRequest('/api/admin/departments/' + departmentId, 'PUT', departmentData);
    }

    static deleteDepartment(departmentId){
        return API.makeAuthenticatedRequest('/api/admin/departments/' + departmentId, 'DELETE');
    }

    static addDepartment(departmentData){
        return API.makeAuthenticatedRequest('/api/admin/departments', 'POST', departmentData);
    }
}