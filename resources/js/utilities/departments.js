import { API } from './api';

export class Departments extends API {
    static getDepartments() {
        return API.makeAuthenticatedRequest('/api/admin/departments', 'GET');
    }

    static getDepartment(departmentId){
        return API.makeAuthenticatedRequest('/api/admin/departments/' + departmentId, 'GET');
    }

    static updateDepartment(departmentId, departmentData){
        return API.makeAuthenticatedRequest('/api/admin/departments/' + departmentId, 'PUT', departmentData);
    }

    static deleteDepartment(departmentId){
        return API.makeAuthenticatedRequest('/api/admin/departments/' + departmentId, 'DELETE');
    }

    static createDepartment(departmentData){
        return API.makeAuthenticatedRequest('/api/admin/departments', 'POST', departmentData);
    }
}