@extends('layout.app')

@section('content')
    <div class="container">
        <h1>{{ $product->name }}</h1>
        <p><strong>Product ID:</strong> {{ $product->product_id }}</p>
        <p><strong>Description:</strong> {{ $product->description }}</p>
        <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
        <p><strong>Stock:</strong> {{ $product->stock }}</p>

        @if ($product->image)
            <p><strong>Image:</strong></p>
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="300">
        @endif
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Back to Products</a>
    </div>
@endsection
