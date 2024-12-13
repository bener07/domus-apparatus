import { API } from "./api";

export class Products extends API{
    static getProducts(callback=()=>{}) {
        return API.makeAuthenticatedRequest('/api/products', 'GET', {}, callback);
    }
}