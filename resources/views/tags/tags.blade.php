@extends('../layout')
@section('title')
Product Tags
@endsection

@section('content')
@foreach($tags as $tag)
{{ $tag->name }} <br>
@endforeach
@endsection