<x-mail::message>
    <h1>Confirme a requisição do equipamento</h1>
    <h2>Pedido feito por: <strong>{{ $requisicao->user->name }}</strong></h2>
    <p>Lista de Equipamentos: </p>
    <x-list-products :produtos="$products"/>
    <p>Quantidade: {{ $requisicao->quantity }}</p>
    <p>Data prevista de entrega: {{ $requisicao->getEntregaPrevista() }}</p>
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