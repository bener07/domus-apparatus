@extends('../layout')
@section('title')
Party Tags
@endsection

@section('content')
@foreach($tags as $tag)
{{ $tag->name }} <br>
@endforeach
@endsection