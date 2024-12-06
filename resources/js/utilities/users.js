import { API } from './api';

export class Users extends API {
    static getUsers(data, callback) {
        var data = {
            start: data.start,
            length: data.length,
            search: data.search.value,
            orderColumn: data.order[0].column,
            orderDir: data.order[0].dir
        }
        return API.makeAuthenticatedRequest('/api/admin/users', 'GET', data, callback);
    }

    static addUser(userData) {
        return API.makeAuthenticatedRequest('/api/admin/users', 'POST', userData, ()=>{});
    }

    static getUser(userId) {
        return API.makeAuthenticatedRequest('/api/admin/users/' + userId, 'GET', {}, ()=>{});
    }

    static updateUser(userId, userData) {
        return API.makeAuthenticatedRequest('/api/admin/users/' + userId, 'PUT', userData, ()=>{});
    }

    static deleteUser(userId) {
        return API.makeAuthenticatedRequest('/api/admin/users/' + userId, 'DELETE', {}, ()=>{});
    }

    static getUserProducts(userId) {
        return API.makeAuthenticatedRequest('/api/admin/users/' + userId + '/products', 'GET', {}, ()=>{});
    }
}