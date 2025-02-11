<div class="dropdown">
    <a class="btn fs-4 m-3" type="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
      <!-- Cart number badge -->
      <span id="cart-number" class="badge bg-danger" style="transform: translate(50px, -20px);">0</span>
      <i class="bi-cart-fill"></i>
    </a>

    <!-- Cart Modal -->
    <ul class="dropdown-menu dropdown-menu-end p-3 shadow" aria-labelledby="cartDropdown" style="width: 400px;">
      <li class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-1 fs-5">Equipamentos</h5>
        <button class="btn-close" id="closeCartButton" aria-label="Close"></button>
      </li>
      <li id="cart-items">
        <p class="mb-0">Não tem equipamentos para requisitar</p>
      </li>
      <button class="btn btn-primary mt-2 w-100">Confirmar Requisição</button>
    </ul>
    <div id="messager" class="alert alert-danger alert-dismissible fade" role="alert" style="display:none;">
      <p id="message-text"></p>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
    </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const closeCartButton = document.getElementById('closeCartButton');
    const cartDropdown = document.getElementById('cartDropdown');

    closeCartButton.addEventListener('click', () => {
      // Manually toggle the dropdown
      const dropdownInstance = bootstrap.Dropdown.getInstance(cartDropdown);
      if (dropdownInstance) {
        dropdownInstance.hide();
      }
    });
  });
</script>
