import { Products } from '../utilities/products';

function loadProducts(){
    Products.getProducts(function (products) {
        console.log(products);
        const divRow = $('#productsGrid');
        products.data.forEach(product => {
            let processedStatus = ''
            switch(product.status){
                case 'disponivel':
                    processedStatus = 'success';
                    break;
                case 'indisponivel':
                    processedStatus = 'danger';
                    break;
                case 'em confirmacao':
                    processedStatus = 'info';
                    break;
                default:
                    processedStatus = 'warning';
                    break;
            }
            let cardHtml = `
            <div class="col-sm-3 mb-3 mb-sm-0">
              <div class="card">
                <img src="${product.featured_image}" class="card-img-top product-img" alt="${product.name}">
                <div class="card-body">
                  <h5 class="card-title">${product.name}</h5>
                  <p class="card-text">${product.details}</p>
                  <div class="d-flex flex-row justify-content-between mt-3 align-items-center">
                    <p class="fs-5 text-capitalize">${product.status}</p>
                    <button type="button" class="btn btn-${processedStatus} text-capitalize">${product.status}</button>
                  </div>
                </div>
              </div>
            </div>
            `;
            divRow.append(cardHtml);
        });
    });
}

loadProducts();
