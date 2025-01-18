import { Cart } from '../utilities/cart';
import { SwalDialog } from '../utilities/dialog';

function timeAgo(dateString) {
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



export function loadCart() {
    Cart.getProducts().then(response => {
        let products = response.data.requisicoes; 
        let html = '';
        products.map(product => {
            html += `
            <div class="card mb-3 mx-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title fs-5">${product.product}</h5>
                            <p class="card-text">${product.description}</p>
                            <p class="card-text"><small class="text-body-secondary">Adicionado ao carrinho ${timeAgo(product.requisitado)}</small></p>
                            <div class="input-group mb-3" id="quantity-${product.id}">
                                <button class="btn btn-outline-secondary btn-decrement m-0" type="button">-</button>
                                <input type="number" class="form-control text-center m-0 quantity-input" value="${product.quantity}" max="${product.total_product_quantity}" min="1"  style="max-width: 70px;">
                                <button class="btn btn-outline-secondary btn-increment" type="button">+</button>
                            </div>
                            <button class="btn btn-danger btn-sm me-2" id="remove-product-btn-${product.id}">Remover Tudo</button>
                        </div>
                    </div>
                    <div class="col-md-4 mx-0 px-0">
                        <img src="${product.img}" class="img-fluid rounded-end mx-0" alt="...">
                    </div>
                </div>
            </div>
            `;
        });
        $('#checkoutDiv').html(html);
        products.forEach(product => {
            let quantityDiv = $(`#quantity-${product.id}`);
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
            $(`#remove-product-btn-${product.id}`).on('click', (event) =>{
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
    });
    
}

loadCart();
