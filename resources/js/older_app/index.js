import { Products } from './products';

function loadProducts() {
    Products.getProducts()
    .then(function (products) {
        products.data.forEach(Product => {
            const tagsHtml = Product.tags.map(tag => `<a class="badge bg-dark text-white m-1 text-decoration-none" href="/tag/${tag.id}">${tag.name}</a>`).join(" ");
            Product = `
            <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Sale badge-->
                        <div class="position-absolute flex justify-content-end flex-wrap" style="right: 0.4rem;">
                            ${tagsHtml}
                        </div>
                        <!-- Product image-->
                        <a href="/Product/${Product.id}">
                            <img class="card-img-top" src="${ Product.img }" alt="..." />
                        </a>
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder">${Product.name}</h5>
                                <div class="">${Product.details}</div>
                                <!-- Product reviews-->
                                <div class="d-flex justify-content-center small text-warning mb-2">
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                </div>
                                <!-- Product price-->
                                Entry Ticket: ${Product.price} €
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer pt-0 border-top-0 bg-transparent px-1">
                            <div class="text-center text-center flex justify-content-around">
                                <a class="btn btn-secondary mt-auto" href="/Product/${Product.id}">View Details</a>
                                <button class="btn btn-primary mt-auto fw-bold" onclick="joinProductList(${Product.id})">Join List</button>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('#products').append(Product);
        });
    });
}

$(document).ready(function () {
   loadProducts();
});