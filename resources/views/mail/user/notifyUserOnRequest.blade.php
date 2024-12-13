<x-mail::message>
# Pedido de Requisição em confirmação
A sua Requisição foi enviada para {{ $requisicao->admin->name }}, assim que confirmado irá receber um email para ir requisitar os equipamentos pedidos.<br>
Produtos requisitados:
<x-list-products :produtos="$requisicao->products"/>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>