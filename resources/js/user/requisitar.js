import { Products } from '../utilities/products';
import { Cart } from '../utilities/cart';
import { SwalDialog } from '../utilities/dialog';

const MAX_DESCRIPTION_LENGTH = 40;
const MAX_TITLE_LENGTH = 20;

export function loadProducts() {
  // Clear the status text from the previous products (button labels)
  const divRow = $('#productsGrid');
  divRow.find('.btn').each(function () {
    // Check if the button text matches any of the statuses we want to remove
    if ($(this).text() === "disponivel" || $(this).text() === "indisponivel" || $(this).text() === "em confirmacao") {
      $(this).text(""); // Remove the text if it's a status button
    }
  });

  // Fetch and load products
  let html = '';
  Products.getProducts(function (products) {
    window.products = products;

    // Loop through the products and add them to the grid
    products.data.forEach(product => {
      let processedStatus = '';
      switch (product.status) {
        case 'disponivel':
          processedStatus = 'success';
          product.status = 'disponíveis';
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

      let truncatedDescription = product.details;
      if (truncatedDescription.length > MAX_DESCRIPTION_LENGTH) {
        truncatedDescription = truncatedDescription.substring(0, MAX_DESCRIPTION_LENGTH) + '...';
      }
      let truncatedTitle = product.name;
      if (truncatedTitle.length > MAX_TITLE_LENGTH) {
        truncatedTitle = truncatedTitle.substring(0, MAX_TITLE_LENGTH) + '...';
      }


      // Create the card HTML for each product
      let cardHtml = `
      <div class="col-sm-4 mb-4">
        <div class="card">
          <img src="${product.featured_image}" class="card-img-top product-img" alt="${product.name}" style="width: 100%; height: 200px;">
          <div class="card-body">
            <a class="card-title fs-5" href="/product/${product.id}">${truncatedTitle}</a>
            <p class="card-text" style="height: 50px">${truncatedDescription}</p>
            <div class="d-flex flex-row justify-content-between mt-3 align-items-center">
              <h4 class="fs-6 px-3 py-2 fw-400 rounded bg-${processedStatus} text-capitalize">
                ${product.status}: 
                ${product.quantity}
              </h4>
            </div>
            <div class="d-flex justify-content-between flex-column mt-3">
              <label for="item-quantity-${product.id}">Quantidade</label>
              <input id="item-quantity-${product.id}" class="p-1 px-3 m-0 rounded border-opacity-50 border-info" type="number" value="1" placeholder="Quantidade" max="${product.quantity}" min="1">
            </div>
            <button type="button" class="btn btn-primary mt-3" ${product.status == 'indisponivel'? 'disabled': ''} style="width:100%" id="item-${product.id}">
              Adicionar
              <i class="bi bi-cart-plus"></i>
            </button>
          </div>
        </div>
      </div>
      `;

      // Append the new product card to the grid
      html += cardHtml;
    });
    divRow.html(html);
    products.data.forEach(product => {
      $(`#item-${product.id}`).click(function () {
        let quantity = parseInt($('#item-quantity-' + product.id).val());
        addToCart(product.id, quantity);
      });
    })
  });
}

// Function to handle "Por no Carrinho" button click
function addToCart(productId, quantity) {
  Cart.addItem({
    product_id: productId,
    quantity: quantity
  }, ()=>{});
}