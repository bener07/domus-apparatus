import { Products } from './products.js';
import Swal from 'sweetalert2';


function removeUserFromProduct(ProductId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You will be removed from the Product list",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remove!"
      }).then((result) => {
        if (result.isConfirmed) {
            Products.removeUserFromProduct(ProductId, loadUsersToDiv).then((result) => {
                console.log(result);
        });
        }
      });
      
}

function loadUsersToDiv() {
    let typeDiv = $('#products').length > 0 ? $('#products') : $('#events')
    Products.getUserProducts(typeDiv.attr('id')).then(function (response){
        let ProductDiv = "";
        response.data.forEach(Product => {
            ProductDiv += `
                <div class="card my-4">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="card-header">
                                Tags
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">${Product.name}</h5>
                                <p class="card-text">${Product.details}</p>
                                <button class="btn btn-primary" href="/Product/${Product.id}" id="${Product.id}" data-Product-id="${Product.id}">Remove from Product</button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div style="width: 100%;height: 220px;background-image: url(${Product.img});background-size: cover;background-repeat: no-repeat;"
                            class="img-fluid rounded-start" alt="${Product.name}">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            typeDiv.on('click', `#${Product.id}`, function(){
                let ProductId = $(this).data('Product-id');
                removeUserFromProduct(ProductId);
            })
        });
        typeDiv.html(ProductDiv);
    });
}

$(document).ready(function() {
    loadUsersToDiv();
});
