@extends('../layout')
@section('content')
Parties with the following tag: {{ $tag->name }}<br>
@foreach($parties as $party)
{{ $party->name }} <br>
@endforeach
@endsection