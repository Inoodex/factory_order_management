<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            ['code' => '10001', 'name' => 'Office Cash', 'type' => 'asset', 'is_default' => 1],
            ['code' => '10002', 'name' => 'Factory Operating Bank Account', 'type' => 'asset', 'is_default' => 1],
            ['code' => '20001', 'name' => 'Accounts Payable', 'type' => 'liability', 'is_default' => 1],
            ['code' => '30001', 'name' => "Owner's Equity", 'type' => 'equity', 'is_default' => 1],
            ['code' => '41001', 'name' => 'Garment Production Revenue', 'type' => 'revenue', 'is_default' => 1],
            ['code' => '41002', 'name' => 'Export Sample & Freight Revenue', 'type' => 'revenue', 'is_default' => 0],
            ['code' => '51001', 'name' => 'Factory Rent', 'type' => 'expense', 'is_default' => 1],
            ['code' => '51002', 'name' => 'Worker & Staff Salaries', 'type' => 'expense', 'is_default' => 1],
            ['code' => '51003', 'name' => 'Utilities & Power', 'type' => 'expense', 'is_default' => 1],
            ['code' => '51004', 'name' => 'Fabric & Trims Raw Materials', 'type' => 'expense', 'is_default' => 1],
            ['code' => '51005', 'name' => 'Logistics & Shipping', 'type' => 'expense', 'is_default' => 1],
        ];

        foreach ($accounts as $acc) {
            ChartOfAccount::updateOrCreate(['code' => $acc['code']], $acc);
        }
    }
}
