<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Inventory::with(['product', 'branch'])->get();
    }

    public function map($inventory): array
    {
        return [
            $inventory->id,
            $inventory->product->name,
            $inventory->product->sku,
            $inventory->branch->branch_name,
            $inventory->quantity,
            $inventory->updated_at,
        ];
    }

    public function headings(): array
    {
        return ['ID', 'Product Name', 'SKU', 'Branch', 'Quantity', 'Last Updated'];
    }
}
