
@extends('layout.app')

@section('title', 'Product List')

@section('content')
    <h1>Product List</h1>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
    </div>


    <form action="{{ route('products.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Product ID or description" value="{{ request()->get('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>
                <a href="{{ route('products.index', ['sort' => 'product_id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                    Product ID
                    @if (request('sort') === 'product_id')
                        <span class="ms-2" style="background-color: #f0f0f0; padding: 2px 5px; border-radius: 4px;">
                <i class="fa {{ request('direction') === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}" style="color: green;"></i>
            </span>
                    @endif
                </a>
            </th>
            <th>
                <a href="{{ route('products.index', ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                    Name
                    @if (request('sort') === 'name')
                        <span class="ms-2" style="background-color: #f0f0f0; padding: 2px 5px; border-radius: 4px;">
                <i class="fa {{ request('direction') === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}" style="color: green;"></i>
            </span>
                    @endif
                </a>
            </th>


            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->product_id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">
                        <i class="fa fa-eye"></i> <!-- Show Icon -->
                    </a>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit"></i> <!-- Edit Icon -->
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                            <i class="fa fa-trash"></i> <!-- Delete Icon -->
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $products->appends(['sort' => request('sort'), 'direction' => request('direction')])->links() }}
@endsection
