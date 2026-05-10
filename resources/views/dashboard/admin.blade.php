@extends('layouts.admin')

@section('dashboard_content')
<div class="header">
    <h1 style="font-weight: bold; font-size: 2rem;">Real-Time Stock Update Dashboard</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('export.products') }}" class="btn btn-sm btn-secondary">
            <svg style="width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; margin-right: 4px;" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="M18 17V9a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v8"/><path d="M8 17h8"/></svg>
            Export Products
        </a>
        <a href="{{ route('export.inventory') }}" class="btn btn-sm btn-secondary">
            <svg style="width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; margin-right: 4px;" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            Export Inventory
        </a>
        <a href="{{ route('export.sales') }}" class="btn btn-sm btn-secondary">
            <svg style="width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; margin-right: 4px;" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Export Sales
        </a>
    </div>
</div>

<!-- Key Metrics Cards -->
<div class="grid">
    <div class="card card-stat bg-primary">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-weight: bold; margin: 0;">Total Products</h3>
                <p style="font-size: 2.5rem; font-weight: bold; margin: 5px 0;">{{ $total_products }}</p>
            </div>
            <div class="stock-arrow up">
                <svg style="width: 24px; height: 24px; fill: none; stroke: var(--success); stroke-width: 2;" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
        </div>
    </div>
    <div class="card card-stat bg-success">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-weight: bold; margin: 0;">Total Branches</h3>
                <p style="font-size: 2.5rem; font-weight: bold; margin: 5px 0;">{{ $total_branches }}</p>
            </div>
            <div class="stock-arrow up">
                <svg style="width: 24px; height: 24px; fill: none; stroke: var(--success); stroke-width: 2;" viewBox="0 0 24 24"><path d="M12 22v-9"/><path d="M12 13C8.5 13 6 10.5 6 7s2.5-6 6-6 6 2.5 6 6-2.5 6-6 6z"/><path d="M4.5 14.5A4.5 4.5 0 0 1 9 10a4.5 4.5 0 0 1 4.5 4.5A4.5 4.5 0 0 1 9 19a4.5 4.5 0 0 1-4.5-4.5z"/></svg>
            </div>
        </div>
    </div>
    <div class="card card-stat bg-warning">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-weight: bold; margin: 0;">Low Stock Alerts</h3>
                <p style="font-size: 2.5rem; font-weight: bold; margin: 5px 0;">{{ $low_stock_count }}</p>
                @if($low_stock_count > 0)
                    <span class="badge-alert" style="display: inline-flex; align-items: center; gap: 4px;">
                        <svg style="width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2;" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Action Required
                    </span>
                @endif
            </div>
            <div class="stock-arrow down">
                <svg style="width: 24px; height: 24px; fill: none; stroke: var(--danger); stroke-width: 2;" viewBox="0 0 24 24"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid">
    <div class="card" style="grid-column: span 2;">
        <h4 style="font-weight: bold; margin-top: 0; font-size: 1.2rem;">Sales Trend (Last 7 Days)</h4>
        <div class="chart-container">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>
    <div class="card">
        <h4 style="font-weight: bold; margin-top: 0; font-size: 1.2rem;">Inventory Distribution</h4>
        <div class="chart-container">
            <canvas id="inventoryChart"></canvas>
        </div>
    </div>
</div>

<!-- Stock Items Cards -->
<div class="grid">
    <div class="card" style="grid-column: span 3;">
        <h4 style="font-weight: bold; margin-top: 0; font-size: 1.2rem;">Critical Stock Items</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
            @foreach($low_stock_products as $product)
            <div class="card low-stock" style="padding: 15px; transition: transform 0.2s;">
                <h5 style="margin: 0; font-weight: bold;">{{ $product->name }}</h5>
                <p style="margin: 5px 0; color: var(--text-muted);">SKU: {{ $product->sku }}</p>
                <p style="margin: 5px 0; color: var(--danger); font-weight: bold;">Stock: {{ $product->quantity }} units</p>
                <div class="stock-arrow down">
                    <svg style="width: 20px; height: 20px; fill: none; stroke: var(--danger); stroke-width: 2;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid">
    <div class="card">
        <h4 style="font-weight: bold; margin-top: 0; font-size: 1.2rem;">Recent Stock Updates</h4>
        <div style="max-height: 300px; overflow-y: auto;">
            @foreach($stock_updates as $update)
            <div style="padding: 10px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>{{ $update->product->name }}</strong> 
                    <span style="color: var(--text-muted);">@ {{ $update->branch->branch_name }}</span>
                </div>
                <div style="text-align: right;">
                    <span class="{{ $update->type === 'addition' ? 'profit' : 'loss' }}" style="display: flex; align-items: center; justify-content: flex-end; gap: 4px;">
                        {{ $update->type === 'addition' ? '+' : '-' }}{{ $update->quantity }}
                        <div class="stock-arrow {{ $update->type === 'addition' ? 'up' : 'down' }}" style="display: inline-flex;">
                            @if($update->type === 'addition')
                                <svg style="width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2;" viewBox="0 0 24 24"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                            @else
                                <svg style="width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2;" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                            @endif
                        </div>
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="card">
        <h4 style="font-weight: bold; margin-top: 0; font-size: 1.2rem;">Top Performing Products</h4>
        <div class="chart-container">
            <canvas id="topProductsChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Trend Chart
    const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($sales_trend_labels ?? []) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($sales_trend_data ?? []) !!},
                borderColor: 'var(--success)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Inventory Distribution Chart
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(inventoryCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($inventory_labels ?? []) !!},
            datasets: [{
                label: 'Stock Level',
                data: {!! json_encode($inventory_data ?? []) !!},
                backgroundColor: function(context) {
                    const value = context.parsed.y;
                    return value < 10 ? 'var(--danger)' : value < 50 ? 'var(--warning)' : 'var(--success)';
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(topProductsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($top_products_labels ?? []) !!},
            datasets: [{
                data: {!! json_encode($top_products_data ?? []) !!},
                backgroundColor: ['var(--primary)', 'var(--success)', 'var(--warning)', 'var(--danger)', 'var(--info)']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endsection
