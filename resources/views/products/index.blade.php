@extends('layouts.admin')

@section('dashboard_content')
<div class="header">
    <h2 style="margin:0;">Product Management</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 50px; border-radius: 4px;">
                    @else
                        <div style="width: 50px; height: 50px; background:#ccc; color:#fff; text-align:center; line-height:50px; border-radius:4px;">No IMG</div>
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->category }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-info btn-sm">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Certain about this?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">
        <!-- Simple pagination links -->
        @if($products->hasPages())
            <div style="display:flex; justify-content:center; gap:10px;">
                <a href="{{ $products->previousPageUrl() }}" class="btn btn-primary btn-sm">&laquo; Prev</a>
                <a href="{{ $products->nextPageUrl() }}" class="btn btn-primary btn-sm">Next &raquo;</a>
            </div>
        @endif
    </div>
</div>
@endsection
