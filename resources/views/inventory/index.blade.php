@extends('layouts.admin')

@section('dashboard_content')
<div class="header">
    <h1 style="font-weight: bold; font-size: 2rem; margin:0;">Inventory Management</h1>
    <div style="display: flex; gap: 10px;">
        <button class="btn btn-sm btn-success" onclick="document.getElementById('updateStockModal').style.display='block'">📈 Update Stock</button>
        <button class="btn btn-sm btn-warning" onclick="document.getElementById('transferStockModal').style.display='block'">📦 Transfer Stock</button>
    </div>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table class="table" id="inventory-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Branch</th>
                    <th>SKU</th>
                    <th>Current Inventory</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inv)
                <tr id="inventory-row-{{ $inv->id }}">
                    <td>{{ $inv->product->name }}</td>
                    <td>{{ $inv->branch->branch_name }}</td>
                    <td>{{ $inv->product->sku }}</td>
                    <td class="quantity-cell" style="font-weight: bold; font-size:1.1rem; color: {{ $inv->quantity < 10 ? 'var(--danger)' : 'var(--text-main)' }};">
                        {{ $inv->quantity }}
                    </td>
                    <td class="status-cell">
                        @if($inv->quantity < 10)
                            <span class="badge badge-alert" style="background-color: var(--danger); color: white;">Low Stock</span>
                        @else
                            <span class="badge" style="background-color: var(--success); color: white;">In Stock</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        @if($inventories->hasPages())
            <div style="display:flex; justify-content:center; gap:10px;">
                <a href="{{ $inventories->previousPageUrl() }}" class="btn btn-primary btn-sm">&laquo; Prev</a>
                <a href="{{ $inventories->nextPageUrl() }}" class="btn btn-primary btn-sm">Next &raquo;</a>
            </div>
        @endif
    </div>
</div>

<!-- Update Stock Modal -->
<div id="updateStockModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('updateStockModal').style.display='none'">&times;</span>
        <h2 style="margin-top: 0;">Update Stock</h2>
        <form action="{{ route('inventory.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Product</label>
                <select name="product_id" class="form-control" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Branch</label>
                <select name="branch_id" class="form-control" required>
                    <option value="">-- Select Branch --</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Quantity Change</label>
                    <input type="number" name="quantity" class="form-control" required min="1">
                </div>
                <div class="form-group">
                    <label>Action</label>
                    <select name="action" class="form-control" required>
                        <option value="increase">Increase (+)</option>
                        <option value="decrease">Decrease (-)</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Process Update</button>
        </form>
    </div>
</div>

<!-- Transfer Stock Modal -->
<div id="transferStockModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('transferStockModal').style.display='none'">&times;</span>
        <h2 style="margin-top: 0;">Transfer Stock</h2>
        <form action="{{ route('inventory.transfer') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Product</label>
                <select name="product_id" class="form-control" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>From Branch</label>
                <select name="from_branch_id" class="form-control" required>
                    <option value="">-- Select Source Branch --</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>To Branch</label>
                <select name="to_branch_id" class="form-control" required>
                    <option value="">-- Select Destination Branch --</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Quantity to Transfer</label>
                <input type="number" name="quantity" class="form-control" required min="1">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Transfer Stock</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Echo) {
            window.Echo.channel('inventory-updates')
                .listen('.stock.updated', (e) => {
                    const row = document.getElementById('inventory-row-' + e.inventory.id);
                    if(row) {
                        const qtyCell = row.querySelector('.quantity-cell');
                        const statusCell = row.querySelector('.status-cell');
                        
                        qtyCell.textContent = e.inventory.quantity;
                        
                        if(e.inventory.quantity < 10) {
                            qtyCell.style.color = 'var(--danger)';
                            statusCell.innerHTML = '<span class="badge badge-alert" style="background-color: var(--danger); color: white;">Low Stock</span>';
                        } else {
                            qtyCell.style.color = 'var(--text-main)';
                            statusCell.innerHTML = '<span class="badge" style="background-color: var(--success); color: white;">In Stock</span>';
                        }
                    }
                });
        }
    });
</script>
@endsection@extends('layouts.admin')

@section('dashboard_content')
<div class="header">
    <h2 style="margin:0;">Inventory Tracking</h2>
    <button onclick="document.getElementById('stockModal').style.display='block'" class="btn btn-primary">Update Stock</button>
</div>

<div class="card">
    <table class="table" id="inventory-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Branch</th>
                <th>Quantity</th>
                <th>Last Evaluated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $inv)
            <tr id="inventory-row-{{ $inv->id }}" @if($inv->quantity < 10) style="background-color: #fff3cd;" @endif>
                <td>{{ $inv->product->name }} ({{ $inv->product->sku }})</td>
                <td>{{ $inv->branch->branch_name }}</td>
                <td class="quantity-cell">
                    {{ $inv->quantity }}
                    @if($inv->quantity < 10)
                        <span style="color: #856404; font-weight: bold; margin-left: 10px;">Low Stock!</span>
                    @endif
                </td>
                <td>{{ $inv->updated_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 20px;">
        @if($inventories->hasPages())
            <div style="display:flex; justify-content:center; gap:10px;">
                <a href="{{ $inventories->previousPageUrl() }}" class="btn btn-primary btn-sm">&laquo; Prev</a>
                <a href="{{ $inventories->nextPageUrl() }}" class="btn btn-primary btn-sm">Next &raquo;</a>
            </div>
        @endif
    </div>
</div>

<!-- Simple Modal CSS and Structure -->
<style>
.modal {
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 0; 
    top: 0; 
    width: 100%; 
    height: 100%; 
    background-color: rgba(0,0,0,0.5); 
}
.modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
.close:hover { color: #000; }
</style>

<div id="stockModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('stockModal').style.display='none'">&times;</span>
        <h2>Update Stock</h2>
        <form action="{{ route('inventory.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Branch</label>
                <select name="branch_id" class="form-control" required>
                    @foreach(\App\Models\Branch::all() as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Product</label>
                <select name="product_id" class="form-control" required>
                    @foreach(\App\Models\Product::all() as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Quantity Change (+/-)</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type" class="form-control" required>
                    <option value="addition">Arrival / Restock (Addition)</option>
                    <option value="deduction">Sale / Loss (Deduction)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Save Update</button>
        </form>
    </div>
</div>

<script>
    // Echo is loaded in app.js
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Echo) {
            window.Echo.channel('inventory-updates')
                .listen('.stock.updated', (e) => {
                    const row = document.getElementById('inventory-row-' + e.inventory.id);
                    if(row) {
                        const qtyCell = row.querySelector('.quantity-cell');
                        qtyCell.innerHTML = e.inventory.quantity + (e.inventory.quantity < 10 ? '<span style="color: #856404; font-weight: bold; margin-left:10px;">Low Stock!</span>' : '');
                        row.style.backgroundColor = e.inventory.quantity < 10 ? '#fff3cd' : 'transparent';
                    } else {
                        window.location.reload();
                    }
                });
        }
    });
</script>
@endsection
@extends('layouts.admin')

@section('dashboard_content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Inventory Management</h2>
    <div style="display: flex; gap: 10px;">
        <button class="btn btn-success" onclick="openModal('updateStockModal')">📈 Update Stock</button>
        <button class="btn btn-warning" onclick="openModal('transferStockModal')">📦 Transfer Stock</button>
    </div>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Branch</th>
                    <th>SKU</th>
                    <th>Current Inventory</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inv)
                <tr>
                    <td>{{ $inv->product->name }}</td>
                    <td>{{ $inv->branch->branch_name }}</td>
                    <td><small>{{ $inv->product->sku }}</small></td>
                    <td style="font-weight: bold;">{{ $inv->quantity }}</td>
                    <td>
                        @if($inv->quantity < 10)
                            <span class="badge badge-danger">Low Stock</span>
                        @else
                            <span class="badge badge-success">In Stock</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">
    {{ $inventories->links() }}
</div>

<!-- Update Stock Modal -->
<div id="updateStockModal" class="modal">
    <div class="modal-content">
        <form action="{{ route('inventory.update') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h3>Increase/Decrease Stock</h3>
                <button class="close-modal" onclick="closeModal('updateStockModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Product</label>
                    <select name="product_id" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{$p->sku}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Branch</label>
                    <select name="branch_id" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" required min="1">
                    </div>
                    <div class="form-group">
                        <label>Action</label>
                        <select name="action" required>
                            <option value="increase">Increase (+)</option>
                            <option value="decrease">Decrease (-)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Process Update</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('updateStockModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Transfer Stock Modal -->
<div id="transferStockModal" class="modal">
    <div class="modal-content">
        <form action="{{ route('inventory.transfer') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h3>Transfer Between Branches</h3>
                <button class="close-modal" onclick="closeModal('transferStockModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Product</label>
                    <select name="product_id" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>From Branch</label>
                    <select name="from_branch_id" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>To Branch</label>
                    <select name="to_branch_id" required>
                        <option value="">-- Select Branch --</option>
                        @foreach($branches as $b)
                        <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" required min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Perform Transfer</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('transferStockModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.add('show');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('show');
    }
}
</script>
@endsection
