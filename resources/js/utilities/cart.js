import {
  API
} from './api';
import {
  loadProducts
} from '../user/requisitar.js';
import {
  SwalDialog
} from './dialog.js';

export class Cart {
  constructor(id, messagerId) {
      this.id = id;
      this.messager = $(messagerId);
      this.number = $('#cart-number');
      this.totalDiv = $('#cart-total-div');
      this.products = this.loadProducts();
      this.total = 0;
  }

  static timeAgo(dateString) {
      const now = new Date();
      const date = new Date(dateString);

      const seconds = Math.floor((now - date) / 1000);
      const minutes = Math.floor(seconds / 60);
      const hours = Math.floor(minutes / 60);
      const days = Math.floor(hours / 24);
      const months = Math.floor(days / 30);
      const years = Math.floor(months / 12);

      const rtf = new Intl.RelativeTimeFormat('pt', { numeric: 'auto' });

      if (seconds < 60) {
          return rtf.format(-seconds, 'second');
      } else if (minutes < 60) {
          return rtf.format(-minutes, 'minute');
      } else if (hours < 24) {
          return rtf.format(-hours, 'hour');
      } else if (days < 30) {
          return rtf.format(-days, 'day');
      } else if (months < 12) {
          return rtf.format(-months, 'month');
      } else {
          return rtf.format(-years, 'year');
      }
  }


  buildProductContainer(product) {
    return `<li>
        <div class="card mb-3" style="max-width: 100%; border-radius: 10px; display: grid; grid-template-columns: auto 1fr auto; align-items: center;">
            <!-- Image -->
            <img src="${product.img}" alt="${product.product}" class="product-img">
            
                    <!-- Card Body -->
            <div class="p-2">
                <div style="text-align: start;">
                    <h5 class="card-title mb-1" style="font-size: large;">${product.name}</h5>
                    <p class="card-text"><small class="text-body-secondary">Adicionad ${Cart.timeAgo(product.adicionado)}</small></p>
                </div>
                <div class="container p-0 row" style="width: 120%">
                        <div class="input-group col-8" id="quantity-${product.id+this.id.replace('#', '')}" style="width: 130px;height:40px">
                            <button class="btn btn-outline-secondary btn-decrement m-0" style="z-index:0" type="button">-</button>
                            <input type="number" class="number-no-arrows form-control text-center m-0 quantity-input" value="${product.quantity}" max="${product.total_product_quantity}" min="1" style="max-width: 40px; height: 100%;">
                            <button class="btn btn-outline-secondary btn-increment" style="z-index:0" type="button">+</button>
                        </div>
                        <a class="fs-1 btn btn-danger col-4 d-flex flex-wrap justify-content-center pt-1 align-items-center" id="remove-product-btn-${product.id+this.id.replace('#', '')}" style="max-height: 50px; max-width: 50px;">
                            <i class="bi bi-trash fs-3" style="color:black; width: 45px; height: 30px;margin-bottom: 5px;"></i>
                        </a>
                    <!-- Remove Button -->
                </div>
            </div>
            
        </div>
    </li>`;
  }

  updateProducts(products) {
      this.products = products;
      loadProducts();
      $(this.id).html(this.products.map(product => this.buildProductContainer(product)));
      this.products.forEach(product => {
        let quantityDiv = $(`#quantity-${product.id+this.id.replace('#', '')}`);
            let input = quantityDiv.find('.quantity-input').change((event) => {
                Cart.updateQuantity(product.id, parseInt(event.target.value));
            });
            quantityDiv.find('.btn-decrement').on('click', (event) => {
                let futurevalue = parseInt(input.val()) - 1;
                if(futurevalue >= input.attr('min')){
                    input.val(futurevalue).trigger('change');
                }
            });
            quantityDiv.find('.btn-increment').on('click', (event) => {
                let futurevalue = parseInt(input.val()) + 1;
                if(futurevalue <= input.attr('max')){
                    input.val(futurevalue).trigger('change');
                }
            });
            $(`#remove-product-btn-${product.id+this.id.replace('#', '')}`).on('click', (event) =>{
              event.preventDefault();
              SwalDialog.info(
                'O produto ' + product.product + ' irá ser removido do carrinho, deseja confirmar?',
                '',
                () => {
                  console.log(product);
                  Cart.removeItem(product.id).then((response) => {
                    loadCart();
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

  resetProducts() {
      $(this.id).html('<p class="mb-0">Não tem equipamentos para requisitar</p>');
  }

  updateCart(event) {
      this.resetProducts();
      this.updateProducts(event.cart.items);
      this.showMessage(event.message, event.color);
      this.total = event.cart.total;
      this.totalDiv.html('Total de Equipamentos: ' + this.total);
  }

  showMessage(message, color) {
      this.messager.text(message);

      // Show the messager using Bootstrap's 'show' class
      this.messager.removeClass('alert-danger alert-succes');
      this.messager.addClass(`show alert-${color}`).removeClass('fade');

      // Hide the messager after 4 seconds
      const messager = this.messager;
      setTimeout(function() {
          messager.removeClass('show').addClass('hidden fade');
      }, 5000); // Wait for 4 seconds before hiding
  }

  static addItem(data, successCallback, errorCallback) {
      return API.makeAuthenticatedRequest('/api/user/cart', 'POST', JSON.stringify(data));
  }

  static removeItem(productId) {
      return API.makeAuthenticatedRequest('/api/user/cart/' + productId, 'DELETE');
  }

  loadProducts() {
    const callback = (response) => {
        this.total = response.data.total;
          this.totalDiv.html('Total de Equipamentos: ' + this.total);
          this.total = response.data.total;
          this.updateProducts(response.data.items);
    }
    if(window.products === undefined) {
        Cart.getProducts().then((response) => {
            callback(response)
        });
    }else{
        callback(window.products);
    }
  }

  static getProducts(data = {}, callback = () => {}) {
      return API.makeAuthenticatedRequest('/api/user/cart', 'GET', data, callback);
  }
  static updateQuantity(itemId, quantity) {
      return API.makeAuthenticatedRequest('/api/user/cart/', 'PUT',
          JSON.stringify({
              id: itemId,
              quantity: quantity
          }), () => {});
  }

  static checkout(data) {
      return API.makeAuthenticatedRequest('/api/user/cart/checkout', 'POST', JSON.stringify(data));
  }
}