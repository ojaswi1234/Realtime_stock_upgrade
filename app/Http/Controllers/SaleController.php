<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Branch;
use App\Events\StockUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['product', 'branch'])->latest()->paginate(15);
        $products = Product::all();
        $branches = Branch::all();
        return view('sales.index', compact('sales', 'products', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity_sold' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $inventory = Inventory::where('product_id', $request->product_id)
            ->where('branch_id', $request->branch_id)
            ->first();

        if (!$inventory || $inventory->quantity < $request->quantity_sold) {
            return back()->with('error', 'Insufficient stock for sale.');
        }

        DB::transaction(function () use ($request, $product, $inventory) {
            $totalPrice = $product->price * $request->quantity_sold;

            Sale::create([
                'product_id' => $request->product_id,
                'branch_id' => $request->branch_id,
                'quantity_sold' => $request->quantity_sold,
                'total_price' => $totalPrice,
                'sold_at' => now(),
            ]);

            $inventory->decrement('quantity', $request->quantity_sold);

            event(new StockUpdated($inventory, "Sale: {$request->quantity_sold} x {$product->name} sold."));
        });

        return back()->with('success', 'Sale recorded successfully.');
    }
}
