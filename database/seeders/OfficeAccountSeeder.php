<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\OfficeAccount;
use App\Models\User;
use Illuminate\Database\Seeder;

class OfficeAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'hello@inoodex.com')->first();
        $adminId = $admin?->id ?? 1;

        $cashCoa = ChartOfAccount::where('code', '10001')->first();
        $bankCoa = ChartOfAccount::where('code', '10002')->first();

        OfficeAccount::firstOrCreate(
            ['account_number' => '00000000'],
            [
                'account_name' => 'Office Cash Drawer',
                'account_type' => 'cash',
                'chart_of_account_id' => $cashCoa?->id,
                'opening_balance' => 500000.00,
                'status' => 'active',
                'created_by' => $adminId,
            ]
        );

        OfficeAccount::firstOrCreate(
            ['account_number' => '3781901011402'],
            [
                'account_name' => 'Pubali Bank - Operations',
                'account_type' => 'bank',
                'provider_name' => 'Pubali Bank Ltd',
                'branch_name' => 'Panthapath Branch, Dhaka',
                'chart_of_account_id' => $bankCoa?->id,
                'opening_balance' => 500000.00,
                'status' => 'active',
                'created_by' => $adminId,
            ]
        );
    }
}
