import { Products } from '../utilities/products';

function loadProducts() {
  // Clear the status text from the previous products (button labels)
  const divRow = $('#productsGrid');
  divRow.find('.btn').each(function () {
    // Check if the button text matches any of the statuses we want to remove
    if ($(this).text() === "disponivel" || $(this).text() === "indisponivel" || $(this).text() === "em confirmacao") {
      $(this).text(""); // Remove the text if it's a status button
    }
  });

  // Fetch and load products
  Products.getProducts(function (products) {
    console.log(products);

    // Loop through the products and add them to the grid
    products.data.forEach(product => {
      let processedStatus = '';
      switch (product.status) {
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

      // Create the card HTML for each product
      let cardHtml = `
      <div class="col-sm-3 mb-3 mb-sm-0">
        <div class="card">
          <img src="${product.featured_image}" class="card-img-top product-img" alt="${product.name}">
          <div class="card-body">
            <h5 class="card-title">${product.name}</h5>
            <p class="card-text">${product.details}</p>
            <div class="d-flex flex-row justify-content-between mt-3 align-items-center">
              <p class="fs-6 text-muted">Quantidade: ${product.quantity}</p>
            </div>
            <div class="d-flex justify-content-between mt-3">
              <button type="button" class="btn btn-${processedStatus} text-capitalize">${product.status}</button>
              <button type="button" class="btn btn-primary" onclick="addToCart(${product.id})">Por no Carrinho</button>
            </div>
          </div>
        </div>
      </div>
      `;

      // Append the new product card to the grid
      divRow.append(cardHtml);
    });
  });
}

// Function to handle "Por no Carrinho" button click
function addToCart(productId) {
  console.log(`Produto ${productId} adicionado ao carrinho.`);
  alert(`Produto ${productId} foi adicionado ao carrinho com sucesso!`);
}

loadProducts();
