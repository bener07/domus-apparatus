import { API } from './api';

export class Tags extends API{
    static getTags() {
        return API.makeAuthenticatedRequest('/api/tags', 'GET');
    }

    static getTag(tagId){
        return API.makeAuthenticatedRequest('/api/tags/' + tagId, 'GET');
    }

    static updateTag(tagId, tagData){
        return API.makeAuthenticatedRequest('/api/tags/' + tagId, 'PUT', tagData);
    }

    static deleteTag(tagId){
        return API.makeAuthenticatedRequest('/api/tags/' + tagId, 'DELETE');
    }

    static createTag(tagData){
        return API.makeAuthenticatedRequest('/api/tags', 'POST', tagData);
    }
}