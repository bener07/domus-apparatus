<div>
    <ul>
        @foreach($produtos as $product)
            <li>{{ $product->name }} ({{ $product->quantity }})</li>
        @endforeach
    </ul>
</div>