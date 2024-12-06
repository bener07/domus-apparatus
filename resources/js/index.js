import { Products } from './utilities/products';

function loadProducts() {
    Products.getProducts()
    .then(function (products) {
        products.data.forEach(Product => {
            const tagsHtml = Product.tags.map(tag => `<a class="badge bg-dark text-white m-1 text-decoration-none" href="/tag/${tag.id}">${tag.name}</a>`).join(" ");
            Product = ``;
            $('#products').append(Product);
        });
    });
}

$(document).ready(function () {
   loadProducts();
});