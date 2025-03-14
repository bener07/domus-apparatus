import { API } from "./api";

export class Roles extends API{
    static getRolesDataTables(data, callback){
        var data = {
            start: data.start,
            length: data.length,
            search: data.search.value,
            orderColumn: data.order[0].column,
            orderDir: data.order[0].dir
        }
        return API.makeAuthenticatedRequest('/api/admin/roles', 'GET', data, callback);
    }

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