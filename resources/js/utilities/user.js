import { API } from "./api";

export class User{
    static getUser(userId){
        return API.makeAuthenticatedRequest('/api/users/' + userId, 'GET');
    }

    static updateUser(userId, userData){
        return API.makeAuthenticatedRequest('/api/users/' + userId, 'PUT', userData);
    }

    static deleteUser(userId){
        return API.makeAuthenticatedRequest('/api/users/' + userId, 'DELETE', {});
    }

    static getUserRequests(userId){
        return API.makeAuthenticatedRequest('/api/user/requisicoes', 'GET');
    }
}