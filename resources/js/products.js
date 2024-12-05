import { API } from './api_interface';
export class Products{
    constructor(){
        this.Products = [];
    }

    static getProducts() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/api/produtos',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    resolve(data); // Resolve the promise with the Product data
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching Products:', error);
                    reject(error); // Reject the promise with the error
                }
            });
        });
    }
    static getUserProducts(type){
        return API.makeAuthenticatedRequest('/api/user/'+type, "GET", {}, ()=>{});
    }
    static removeUserFromProduct(ProductId, successFunction){
        return API.makeAuthenticatedRequest('/api/user/product', 'DELETE', {ProductId:ProductId}, successFunction);
    }
}