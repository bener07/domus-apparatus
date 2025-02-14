<div {{ $attributes->merge(['class' => 'bottom-0 d-flex flex-row justify-content-center align-items-center mx-1'])}}>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
    <a {{ $attributes->merge(['class' => 'btn btn-success'])}} href="@if(request()->is('cart/checkout'))  @else {{route('cart-checkout') }} @endif">
        <strong>Confirmar Requisição</strong>
    </a>
    {{ $slot }}
</div>