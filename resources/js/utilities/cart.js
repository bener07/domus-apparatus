import {API} from './api';

export class Cart {
    constructor(id, messagerId) {
        this.id = id;
        this.messager = $(messagerId);
        this.number = $('#cart-number');
        this.products = this.loadProducts();
        this.totalPrice = 0;
    }

    buildProductContainer(product){
        return `<div class="card mb-3" style="max-width: 540px;">
      <div class="row g-0 align-items-center">
        <div class="col-md-8">
          <div class="card-body flex-row d-flex" style="padding: 0;">
            <img src="${product.img}" alt="${product.product}" class="card-side-image product-img">
            <div class="d-flex flex-column p-2">
              <h5 class="card-title mb-1 fs-5">${product.product}</h5>
              <p class="card-text mb-1 fs-7">
                <strong>Quantity:</strong> <span class="badge bg-secondary">${product.quantity}</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4 text-end">
          <button class="btn btn-danger btn-sm me-2">Remove</button>
        </div>
      </div>
    </div>`;
    }

    updateProducts(products){
        this.products = products;
        $(this.id).html(this.products.map(product => this.buildProductContainer(product)));
        this.number.text(products.length);
        return 0;
    }

    updateCart(event){
      this.updateProducts(event.cart.requisicoes);
      this.showMessage(event.message);
    }

    showMessage(message){
      this.messager.text(message);

      // Show the messager using Bootstrap's 'show' class
      this.messager.addClass('show').removeClass('fade');

      // Hide the messager after 4 seconds
      const messager = this.messager;
      setTimeout(function () {
          messager.removeClass('show').addClass('hidden fade');
      }, 5000); // Wait for 4 seconds before hiding
    }

    static addItem(data, successCallback, errorCallback){ 
      return API.makeAuthenticatedRequest('/api/user/cart', 'POST', JSON.stringify(data));
    }

    loadProducts(){
      Cart.getProducts().then((response) => {
        this.updateProducts(response.data.requisicoes);
      });
    }

    static getProducts(){
      return API.makeAuthenticatedRequest('/api/user/cart', 'GET');
    }
}