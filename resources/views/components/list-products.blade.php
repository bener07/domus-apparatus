<div>
    <ul>
        @foreach($produtos as $product)
            <li>{{ $product->name }} ({{ $product->quantityOnDate() }})</li>
        @endforeach
    </ul>
</div>