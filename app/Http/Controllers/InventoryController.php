<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Branch;
use App\Models\StockLog;
use App\Events\StockUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with(['product', 'branch'])->paginate(15);
        $products = Product::all();
        $branches = Branch::all();
        return view('inventory.index', compact('inventories', 'products', 'branches'));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer',
            'action' => 'required|in:increase,decrease',
        ]);

        $inventory = Inventory::firstOrCreate(
            ['product_id' => $request->product_id, 'branch_id' => $request->branch_id],
            ['quantity' => 0]
        );

        if ($request->action === 'decrease' && $inventory->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock.');
        }

        DB::transaction(function () use ($request, $inventory) {
            if ($request->action === 'increase') {
                $inventory->increment('quantity', $request->quantity);
            } else {
                $inventory->decrement('quantity', $request->quantity);
            }

            StockLog::create([
                'product_id' => $request->product_id,
                'branch_id' => $request->branch_id,
                'action' => $request->action,
                'quantity' => $request->quantity,
            ]);
        });

        event(new StockUpdated($inventory, "Stock {$request->action}d for {$inventory->product->name}"));

        return back()->with('success', 'Stock updated successfully.');
    }

    public function transferStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $fromInventory = Inventory::where('product_id', $request->product_id)
            ->where('branch_id', $request->from_branch_id)
            ->first();

        if (!$fromInventory || $fromInventory->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock in source branch.');
        }

        DB::transaction(function () use ($request, $fromInventory) {
            $fromInventory->decrement('quantity', $request->quantity);
            
            $toInventory = Inventory::firstOrCreate(
                ['product_id' => $request->product_id, 'branch_id' => $request->to_branch_id],
                ['quantity' => 0]
            );
            $toInventory->increment('quantity', $request->quantity);

            StockLog::create([
                'product_id' => $request->product_id,
                'branch_id' => $request->from_branch_id,
                'action' => 'transfer_out',
                'quantity' => $request->quantity,
            ]);

            StockLog::create([
                'product_id' => $request->product_id,
                'branch_id' => $request->to_branch_id,
                'action' => 'transfer_in',
                'quantity' => $request->quantity,
            ]);

            event(new StockUpdated($fromInventory, "Transferred {$request->quantity} {$fromInventory->product->name} from Branch ID {$request->from_branch_id}"));
            event(new StockUpdated($toInventory, "To Branch ID {$request->to_branch_id}"));
        });

        return back()->with('success', 'Stock transferred successfully.');
    }
}
