import {API} from './api';
import {loadProducts} from '../user/requisitar.js';
import { SwalDialog } from './dialog.js';

export class Cart {
    constructor(id, messagerId) {
        this.id = id;
        this.messager = $(messagerId);
        this.number = $('#cart-number');
        this.totalDiv = $('#cart-total-div');
        this.products = this.loadProducts();
        this.total = 0;
    }

    buildProductContainer(product){
        return `<div class="card mb-3" style="max-width: 540px;">
      <div class="row g-0 align-items-center">
        <div class="col-md-8">
          <div class="card-body flex-row d-flex" style="padding: 0;">
            <img src="${product.img}" alt="${product.product}" class="card-side-image product-img">
            <div class="d-flex flex-column p-2">
              <h5 class="card-title mb-1" style="font-size: large;">${product.product}</h5>
              <p class="card-text mb-1 fs-6">
                <strong>Quantidade:</strong> <span class="badge bg-secondary">${product.quantity}</span>
                
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4 text-end">
          <button class="btn btn-danger btn-sm me-2" id="remove-product-${product.id}">Remover Tudo</button>
        </div>
      </div>
    </div>`;
    }

    updateProducts(products){
        this.products = products;
        loadProducts();
        $(this.id).html(this.products.map(product => this.buildProductContainer(product)));
        this.products.forEach(product => {
          $(`#remove-product-${product.id}`).on('click', (event) =>{
            event.preventDefault();
            SwalDialog.info(
              'O produto ' + product.product + ' irá ser removido do carrinho, deseja confirmar?',
              '',
              () => {
                console.log(product);
                Cart.removeItem(product.id).then((response) => {
                  console.log(response);
                });
              },
              () => {},
              {
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: "Confirmar",
              }
            );
          });
        });
        this.number.text(products.length);
        return 0;
    }

    resetProducts(){
      $(this.id).html('<p class="mb-0">Não tem equipamentos para requisitar</p>');
    }

    updateCart(event){
      this.resetProducts();
      this.updateProducts(event.cart.requisicoes);
      this.showMessage(event.message);
      this.total = event.cart.total;
      this.totalDiv.html('Total de Equipamentos: '+ this.total);
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

    static removeItem(productId){
      return API.makeAuthenticatedRequest('/api/user/cart/' + productId, 'DELETE');
    }

    loadProducts(){
      Cart.getProducts().then((response) => {
        this.total = response.data.total;
        this.totalDiv.html('Total de Equipamentos: '+ this.total);
        this.total = response.data.total;
        this.updateProducts(response.data.requisicoes);
      });
    }

    static getProducts(data={}, callback=()=>{}){
      return API.makeAuthenticatedRequest('/api/user/cart', 'GET', data, callback);
    }
    static updateQuantity(itemId, quantity){
      return API.makeAuthenticatedRequest('/api/user/cart/', 'PUT', 
        JSON.stringify({
          id: itemId,
          quantity: quantity
        }), ()=>{});
    }
}