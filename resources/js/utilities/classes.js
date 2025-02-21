import { API } from './api';

export class Classes extends API {
    static getClasses() {
        return API.makeAuthenticatedRequest('/api/classes', 'GET');
    }

    static getClass(departmentId){
        return API.makeAuthenticatedRequest('/api/classes/' + departmentId, 'GET');
    }

    static updateClass(departmentId, departmentData){
        return API.makeAuthenticatedRequest('/api/classes/' + departmentId, 'PUT', departmentData);
    }

    static deleteClass(departmentId){
        return API.makeAuthenticatedRequest('/api/classes/' + departmentId, 'DELETE');
    }

    static createClass(departmentData){
        return API.makeAuthenticatedRequest('/api/classes', 'POST', departmentData);
    }

    static loadClasses(){
        Classes.getClasses().then((response)=>{
            window.classes = response.data;
            return response.data;
        });
    }
}