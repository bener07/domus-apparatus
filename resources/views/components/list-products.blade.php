<div>
    <ul>
        @foreach($produtos as $product)
            <ul>{{ $product->name }} ({{ $product->quantity }})</ul>
        @endforeach
    </ul>
</div>