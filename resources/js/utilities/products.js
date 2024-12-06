import { API } from './api';
export class Products{
    constructor(){
        this.Products = [];
    }

    static getProducts(data, callback) {
        var data = {
            start: data.start,
            length: data.length,
            search: data.search.value,
            orderColumn: data.order[0].column,
            orderDir: data.order[0].dir
        }
        return API.makeAuthenticatedRequest('/api/admin/products', 'GET', data, callback);
    }
    static getUserProducts(type){
        return API.makeAuthenticatedRequest('/api/user/'+type, "GET", {}, ()=>{});
    }
    static removeUserFromProduct(ProductId, successFunction){
        return API.makeAuthenticatedRequest('/api/user/product', 'DELETE', {ProductId:ProductId}, successFunction);
    }
    static addProduct(formData){
        return API.makeAuthenticatedRequest('/api/admin/products', 'POST', formData, ()=>{});
    }
    static updateProduct(productId, productData){
        return API.makeAuthenticatedRequest('/api/admin/products/'+productId, 'PUT', productData, ()=>{});
    }
    static deleteProduct(productId){
        return API.makeAuthenticatedRequest('/api/admin/products/'+productId, 'DELETE', {}, ()=>{});
    }
}