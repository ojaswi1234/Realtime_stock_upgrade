@extends('layouts.admin')

@section('dashboard_content')
<div class="header">
    <h1 style="font-weight: bold; font-size: 2rem;">Real-Time Stock Update Dashboard</h1>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('export.products') }}" class="btn btn-sm btn-secondary">📊 Export Products</a>
        <a href="{{ route('export.inventory') }}" class="btn btn-sm btn-secondary">📦 Export Inventory</a>
        <a href="{{ route('export.sales') }}" class="btn btn-sm btn-secondary">💰 Export Sales</a>
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
            <div class="stock-arrow up">📈</div>
        </div>
    </div>
    <div class="card card-stat bg-success">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-weight: bold; margin: 0;">Total Branches</h3>
                <p style="font-size: 2.5rem; font-weight: bold; margin: 5px 0;">{{ $total_branches }}</p>
            </div>
            <div class="stock-arrow up">🌱</div>
        </div>
    </div>
    <div class="card card-stat bg-warning">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-weight: bold; margin: 0;">Low Stock Alerts</h3>
                <p style="font-size: 2.5rem; font-weight: bold; margin: 5px 0;">{{ $low_stock_count }}</p>
                @if($low_stock_count > 0)
                    <span class="badge-alert">⚠️ Action Required</span>
                @endif
            </div>
            <div class="stock-arrow down">📉</div>
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
                <div class="stock-arrow down">🔴</div>
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
                    <span class="{{ $update->type === 'addition' ? 'profit' : 'loss' }}">
                        {{ $update->type === 'addition' ? '+' : '-' }}{{ $update->quantity }}
                    </span>
                    <div class="stock-arrow {{ $update->type === 'addition' ? 'up' : 'down' }}">
                        {{ $update->type === 'addition' ? '⬆️' : '⬇️' }}
                    </div>
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
            <tbody>
                @foreach($stock_updates as $log)
                <tr>
                    <td>{{ $log->product->name }}</td>
                    <td>{{ $log->branch->branch_name }}</td>
                    <td><span class="badge {{ $log->action == 'increase' ? 'bg-success' : 'bg-info' }}">{{ $log->action }}</span></td>
                    <td>{{ $log->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card" style="flex:1;">
        <h4 style="margin-top:0; color:#e74c3c;">Low Stock Products</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Branch</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($low_stock_products as $inv)
                <tr>
                    <td>{{ $inv->product->name }}</td>
                    <td>{{ $inv->branch->branch_name }}</td>
                    <td style="color:#e74c3c; font-weight:bold;">{{ $inv->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(typeof Chart === 'undefined') return;
        fetch('{{ route("api.chart-data") }}')
            .then(res => res.json())
            .then(data => {
                new Chart(document.getElementById('salesTrendChart'), {
                    type: 'line',
                    data: {
                        labels: data.sales_trend.labels,
                        datasets: [{
                            label: 'Daily Sales ($)',
                            data: data.sales_trend.data,
                            borderColor: '#3498db',
                            tension: 0.1,
                            backgroundColor: 'rgba(52, 152, 219, 0.1)'
                        }]
                    }
                });

                new Chart(document.getElementById('topProductsChart'), {
                    type: 'bar',
                    data: {
                        labels: data.top_products.labels,
                        datasets: [{
                            label: 'Units Sold',
                            data: data.top_products.data,
                            backgroundColor: '#2ecc71'
                        }]
                    },
                    options: { indexAxis: 'y' }
                });
            });
    });
</script>
@endsection
