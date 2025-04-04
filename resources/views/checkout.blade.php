<x-app-layout>
    <section>
        @cartEmpty
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Confirmar Requisição') }}
            </h2>
    
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Ainda não tem equipamentos no carrinho, por favor adicione os equipamentos que quer adicionar") }}
            </p>
        </header>
        @endcartEmpty

        <header>
            <h2 class="text-lg font-medium text-gray-900">Data registada para o seu pedido</h2>
            {{ auth()->user()->cart->start }}

            até

            {{ auth()->user()->cart->end }}
        </header>

        <!-- Card Container -->
        <div class="container">
            <ul>
                <li id="checkoutDiv" class="row d-flex justify-content-center">
                </li>
            </ul>
        </div>
    </section>
    
  <slot name="scripts">
    @vite(['resources/js/user/checkout.js'])
    <script>
        const submitBtn = document.getElementById('submitForm');
        const btn = document.getElementById('toggleButton');
        btn.addEventListener('click', function () {
            const firstDiv = document.getElementById('firstDiv');
            const secondDiv = document.getElementById('secondDiv');
            firstDiv.style.transform = 'translateX(-100%)';
            secondDiv.style.transform = 'translateX(0)';
            submitBtn.classList.remove('d-none');
        });

        submitBtn.addEventListener('click', function () {
            document.getElementById('dateForm').submit();
        });

    </script>
  </slot>
</x-app-layout>