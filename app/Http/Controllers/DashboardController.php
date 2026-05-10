<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Branch;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ProductsExport;
use App\Exports\InventoryExport;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            // Sales trend for last 7 days
            $salesTrend = Sale::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $sales_trend_labels = $salesTrend->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('M d');
            });
            $sales_trend_data = $salesTrend->pluck('total');
            
            // Inventory levels
            $inventoryData = Inventory::with('product')->get()->groupBy('product.name')->map(function($items) {
                return $items->sum('quantity');
            });
            $inventory_labels = $inventoryData->keys();
            $inventory_data = $inventoryData->values();
            
            // Top products
            $topProducts = Sale::with('product')
                ->select('product_id', DB::raw('SUM(quantity_sold) as total_qty'))
                ->groupBy('product_id')
                ->orderBy('total_qty', 'DESC')
                ->take(5)
                ->get();
            $top_products_labels = $topProducts->map(function($item) {
                return $item->product->name;
            });
            $top_products_data = $topProducts->pluck('total_qty');
            
            $data = [
                'total_products' => Product::count(),
                'total_branches' => Branch::count(),
                'low_stock_count' => Inventory::where('quantity', '<', 10)->count(),
                'recent_sales' => Sale::with(['product', 'branch'])->latest()->take(5)->get(),
                'stock_updates' => StockLog::with(['product', 'branch'])->latest()->take(5)->get(),
                'low_stock_products' => Inventory::with(['product', 'branch'])->where('quantity', '<', 10)->take(5)->get(),
                'sales_trend_labels' => $sales_trend_labels,
                'sales_trend_data' => $sales_trend_data,
                'inventory_labels' => $inventory_labels,
                'inventory_data' => $inventory_data,
                'top_products_labels' => $top_products_labels,
                'top_products_data' => $top_products_data,
            ];
            return view('dashboard.admin', $data);
        } else {
            // Assume manager is assigned to a branch (for demo, we pick the first branch or let user select)
            // In a real app, User model would have branch_id
            $branch = Branch::first(); 
            if (!$branch) {
                $branch = Branch::create(['branch_name' => 'Main Branch', 'location' => 'Main Location', 'manager_name' => $user->name]);
            }
            $data = [
                'branch' => $branch,
                'inventory' => Inventory::with('product')->where('branch_id', $branch->id)->get(),
                'today_sales' => Sale::where('branch_id', $branch->id)->whereDate('sold_at', today())->sum('total_price'),
                'low_stock_alerts' => Inventory::with('product')->where('branch_id', $branch->id)->where('quantity', '<', 10)->get(),
            ];
            return view('dashboard.manager', $data);
        }
    }

    public function getChartData()
    {
        $salesData = Sale::select(DB::raw('DATE(sold_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->take(7)
            ->get();

        $productSales = Sale::with('product')
            ->select('product_id', DB::raw('SUM(quantity_sold) as total_qty'))
            ->groupBy('product_id')
            ->orderBy('total_qty', 'DESC')
            ->take(5)
            ->get();

        return response()->json([
            'sales_trend' => [
                'labels' => $salesData->pluck('date'),
                'data' => $salesData->pluck('total'),
            ],
            'top_products' => [
                'labels' => $productSales->map(fn($s) => $s->product->name),
                'data' => $productSales->pluck('total_qty'),
            ]
        ]);
    }

    public function exportProducts() { return Excel::download(new ProductsExport, 'products.csv'); }
    public function exportInventory() { return Excel::download(new InventoryExport, 'inventory.csv'); }
    public function exportSales() { return Excel::download(new SalesExport, 'sales_report.csv'); }
}
