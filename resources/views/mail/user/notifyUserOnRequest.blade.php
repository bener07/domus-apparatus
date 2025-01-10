<x-mail::message>
# Pedido de Requisição em confirmação
A sua Requisição foi enviada para {{ $cart->admin->name }}, assim que confirmado irá receber um email para ir requisitar os equipamentos pedidos.<br>
Produtos requisitados:
<x-list-products :produtos="$cart->products"/>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>