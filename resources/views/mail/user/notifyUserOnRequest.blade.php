<x-mail::message>
# Pedido de Requisição em confirmação
A sua Requisição foi enviada para {{ $admin->name }}, assim que confirmado irá receber um email para ir requisitar os equipamentos pedidos.<br>
Produtos pedidos:
<x-list-products :produtos="$products"/>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>