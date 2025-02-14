<div {{ $attributes->merge(['class' => 'dropdown py-3 ml-2'])}}>

    <!-- Trigger Button -->
    <a id="openDivButton" class="btn btn-primary w-100">
      {{ $slot }}
      <span id="cart-number" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="
          position: absolute !important;
          top: 2px !important;
          transform: translate(-10px, 5px) !important;
          ">0
          </span>
      <i class="bi-cart-fill"></i>
    </a>

    <!-- Backdrop -->
    <div id="backdrop" class="backdrop"></div>

    <!-- Sliding Div -->
    <div id="slidingDiv" class="slide-up-div" >
        <div class="p-1 h-100" style="height:100%;">
          <button id="closeDivButton" class="btn-close float-end" aria-label="Close"></button>
          <ul class="p-3" style="height: 80%;">
            <li class="d-flex justify-content-between align-items-center mb-2">
              <h5 class="mb-1 fs-5">Equipamentos</h5>
            </li>
            <li id="cart_items">
              <p class="mb-0">Não tem equipamentos para requisitar</p>
            </li>
            <div>
              <div id="cart-total-div" style="justify-self:baseline">Total de equipamentos</div>
              <x-confirm-cart class="mt-2 w-100"/>
            </div>
          </ul>
        </div>
    </div>

    <!-- Cart Modal -->
    <div id="messager" class="alert alert-danger alert-dismissible fade" role="alert" style="display:none;">
      <p id="message-text"></p>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openDivButton = document.getElementById('openDivButton');
        const closeDivButton = document.getElementById('closeDivButton');
        const slidingDiv = document.getElementById('slidingDiv');
        const backdrop = document.getElementById('backdrop');

        // Function to open the sliding div
        const openDiv = () => {
            slidingDiv.classList.add('show');
            backdrop.classList.add('show');
        };

        // Function to close the sliding div
        const closeDiv = () => {
            slidingDiv.classList.remove('show');
            backdrop.classList.remove('show');
        };

        // Open div when button is clicked
        openDivButton.addEventListener('click', openDiv);

        // Close div when close button is clicked
        closeDivButton.addEventListener('click', closeDiv);

        // Close div when clicking outside of it
        backdrop.addEventListener('click', closeDiv);
        
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' || event.key === 'Esc') {
                closeDiv();
            }
        });
    });
</script>