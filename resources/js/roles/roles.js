import { API } from "../api_interface";

export class Roles extends API{
    static getRoles(){
        return API.makeAuthenticatedRequest('/api/roles', 'GET');
    }

    static getRole(roleId){
        return API.makeAuthenticatedRequest('/api/roles/' + roleId, 'GET');
    }

    static updateRole(roleId, roleData){
        return API.makeAuthenticatedRequest('/api/roles/' + roleId, 'PUT', roleData);
    }

    static deleteRole(roleId){
        return API.makeAuthenticatedRequest('/api/roles/' + roleId, 'DELETE');
    }

    static addRole(roleData){
        return API.makeAuthenticatedRequest('/api/roles', 'POST', roleData);
    }
}