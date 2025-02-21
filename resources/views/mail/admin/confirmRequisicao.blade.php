<x-mail::message>
    <h1>Confirme a requisição dos seguintes equipamentos</h1>
    <h2>Pedido feito por: <strong>{{ $user->name }}</strong></h2>
    <p>Data prevista de entrega: {{ $requisicao->getEntregaPrevista() }}</p>
    <p>Data de levantamento: {{ $requisicao->start }}</p>
    <p>Lista de Equipamentos: </p>
    <x-list-products :produtos="$products"/>
    <p>Total de Equipamentos pedidos: {{ $quantity }}</p>
    <div style="display: flex; flex-direction:row;">
        <x-mail::button :url="$requisicao->authorization_url()" color="success">
            Autorizar
        </x-mail::button>
        <x-mail::button :url="$requisicao->denial_url()" color="error">
            Recusar
        </x-mail::button>
    </div>
    Obrigado, <br>
    {{ config('app.name') }}
</x-mail::message>