@extends('layouts.admin')

@section('dashboard_content')
<div class="header">
    <h1 style="margin:0;">Store Manager Dashboard - {{ $branch->branch_name }}</h1>
</div>

<div class="grid">
    <div class="card card-stat btn-info">
        <h3>Today's Sales</h3>
        <p style="font-size: 30px; margin:0;">${{ number_format($today_sales, 2) }}</p>
    </div>
    <div class="card card-stat bg-warning">
        <h3>Low Stock Alerts</h3>
        <p style="font-size: 30px; margin:0;">{{ $low_stock_alerts->count() }} Items</p>
    </div>
</div>

<div class="card">
    <h4 style="margin-top:0;">Branch Inventory</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventory as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td><small style="color:#777;">{{ $item->product->sku }}</small></td>
                <td>{{ $item->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
