<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Sale::with(['product', 'branch'])->get();
    }

    public function map($sale): array
    {
        return [
            $sale->id,
            $sale->product->name,
            $sale->branch->branch_name,
            $sale->quantity_sold,
            $sale->total_price,
            $sale->sold_at,
        ];
    }

    public function headings(): array
    {
        return ['ID', 'Product Name', 'Branch', 'Quantity Sold', 'Total Price', 'Sold At'];
    }
}
