import { API } from "./api";

export class Roles extends API{
    static getRoles(){
        return API.makeAuthenticatedRequest('/api/admin/roles', 'GET');
    }

    static getRole(roleId){
        return API.makeAuthenticatedRequest('/api/admin/roles/' + roleId, 'GET');
    }

    static updateRole(roleId, roleData){
        return API.makeAuthenticatedRequest('/api/admin/roles/' + roleId, 'PUT', roleData);
    }

    static deleteRole(roleId){
        return API.makeAuthenticatedRequest('/api/admin/roles/' + roleId, 'DELETE');
    }

    static addRole(roleData){
        return API.makeAuthenticatedRequest('/api/admin/roles', 'POST', roleData);
    }
}