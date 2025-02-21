import { API } from "./api";

export class User{
    static getUser(userId){
        return API.makeAuthenticatedRequest('/api/user/', 'GET');
    }

    static updateUser(userId, userData){
        return API.makeAuthenticatedRequest('/api/user/', 'PUT', userData);
    }

    static changeDeliveryMessage(delivery){
        return API.makeAuthenticatedRequest('/api/user/deliveryMessage', 'POST', delivery);
    }

    static deleteUser(userId){
        return API.makeAuthenticatedRequest('/api/user/', 'DELETE', {});
    }

    static getUserRequests(userId){
        return API.makeAuthenticatedRequest('/api/user/requisicoes', 'GET');
    }

    static deleteRequisicao(requisicaoId){
        return API.makeAuthenticatedRequest('/api/user/requisicoes/' + requisicaoId, 'DELETE');
    }
}