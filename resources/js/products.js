export class Products{
    constructor(){
        this.Products = [];
    }

    static makeAuthenticatedRequest(url, method, data='', successFunction=() => {Products.getUserProducts()}){
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                method: method,
                data: JSON.stringify(data),
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                xhrFields: {
                    withCredentials: true
                 },                       
                contentType: 'application/json; charset=utf-8',
                success: function(data){
                    resolve(data);
                    successFunction();
                },
                error: function (xhr, status, error) {
                    console.error('Error while making request: ', error);
                    reject(error);
                }
            });
        });
    }

    static getProducts() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/api/Product',
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
        return Products.makeAuthenticatedRequest('/api/user/'+type, "GET", {}, ()=>{});
    }
    static removeUserFromProduct(ProductId, successFunction){
        return Products.makeAuthenticatedRequest('/api/user/product', 'DELETE', {ProductId:ProductId}, successFunction);
    }
}