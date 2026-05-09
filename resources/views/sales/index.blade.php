@extends('layouts.admin')

@section('dashboard_content')
<div class="header">
    <h2 style="margin:0;">Sales History</h2>
    <div>
        <a href="{{ route('export.sales') }}" class="btn btn-primary">Export to CSV</a>
    </div>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Branch</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>#{{ $sale->id }}</td>
                <td>{{ $sale->product->name }} ({{ $sale->product->sku }})</td>
                <td>{{ $sale->branch->branch_name }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>${{ number_format($sale->total_price, 2) }}</td>
                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        @if($sales->hasPages())
            <div style="display:flex; justify-content:center; gap:10px;">
                <a href="{{ $sales->previousPageUrl() }}" class="btn btn-primary btn-sm">&laquo; Prev</a>
                <a href="{{ $sales->nextPageUrl() }}" class="btn btn-primary btn-sm">Next &raquo;</a>
            </div>
        @endif
    </div>
</div>
@endsection
