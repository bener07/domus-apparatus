<x-mail::message>
# A sua requisição foi autorizada

O administrador {{ $requisicao->admin->name }} autorizou a sua requisição, pode levantá-la a {{ $requisicao->start }}
e tem a entrega prevista para {{ $requisicao->entrega_prevista }}

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
