<?php

namespace Database\Seeders;

use App\Models\AccountingPeriod;
use Illuminate\Database\Seeder;

class AccountingPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountingPeriod::firstOrCreate(
            ['name' => 'FY-2026'],
            [
                'year' => 2026,
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'type' => 'fiscal_year',
                'status' => 'open',
                'remarks' => 'Fiscal Year 2026',
            ]
        );
    }
}
