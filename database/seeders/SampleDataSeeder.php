<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch1 = \App\Models\Branch::create([
            'branch_name' => 'Main Street Branch',
            'location' => 'New York',
            'manager_name' => 'John Doe',
        ]);

        $branch2 = \App\Models\Branch::create([
            'branch_name' => 'Westside Branch',
            'location' => 'Los Angeles',
            'manager_name' => 'Jane Smith',
        ]);

        $product1 = \App\Models\Product::create([
            'name' => 'Laptop Pro 15',
            'sku' => 'LP-15-001',
            'category' => 'Electronics',
            'price' => 1200.00,
        ]);

        $product2 = \App\Models\Product::create([
            'name' => 'Smartphone X',
            'sku' => 'SX-002',
            'category' => 'Electronics',
            'price' => 800.00,
        ]);

        $product3 = \App\Models\Product::create([
            'name' => 'Wireless Mouse',
            'sku' => 'WM-003',
            'category' => 'Accessories',
            'price' => 25.00,
        ]);

        // Inventory
        \App\Models\Inventory::create(['product_id' => $product1->id, 'branch_id' => $branch1->id, 'quantity' => 50]);
        \App\Models\Inventory::create(['product_id' => $product2->id, 'branch_id' => $branch1->id, 'quantity' => 100]);
        \App\Models\Inventory::create(['product_id' => $product3->id, 'branch_id' => $branch1->id, 'quantity' => 5]); // Low stock for testing

        \App\Models\Inventory::create(['product_id' => $product1->id, 'branch_id' => $branch2->id, 'quantity' => 30]);
        \App\Models\Inventory::create(['product_id' => $product2->id, 'branch_id' => $branch2->id, 'quantity' => 60]);

        // Sales
        \App\Models\Sale::create([
            'product_id' => $product1->id,
            'branch_id' => $branch1->id,
            'quantity_sold' => 2,
            'total_price' => 2400.00,
            'sold_at' => now(),
        ]);
    }
}
