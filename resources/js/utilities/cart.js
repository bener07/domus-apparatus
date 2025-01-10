export class Cart {
    constructor(id) {
        this.id = id;
        this.products = [];
        this.totalPrice = 0;
    }

    buildProductContainer(product){
        return `<div class="card mb-3" style="max-width: 540px;">
      <div class="row g-0 align-items-center">
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title mb-1">${product.name}</h5>
            <p class="card-text mb-1">
              <strong>Quantity:</strong> <span class="badge bg-secondary">2</span>
            </p>
          </div>
        </div>
        <div class="col-md-4 text-end">
          <button class="btn btn-danger btn-sm me-2">Remove</button>
        </div>
      </div>
    </div>`;
    }

    updateProducts(){
        $(this.id).html = '';
        return 0;
    }
}