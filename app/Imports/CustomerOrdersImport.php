<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerOrdersImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;
    public array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // accounting for 1-based index and header row

            // Required fields
            $orderNo = trim($row['order_no'] ?? '');
            $styleNo = trim($row['style_no'] ?? '');
            $customerName = trim($row['customer'] ?? $row['customer_name'] ?? '');
            $supplierName = trim($row['supplier'] ?? $row['supplier_name'] ?? '');

            if (!$orderNo || !$styleNo || !$customerName || !$supplierName) {
                $this->errors[] = "Row {$rowNumber}: Missing required fields (Order No, Style No, Customer, or Supplier).";
                continue;
            }

            // Check if order number already exists
            if (CustomerOrder::where('order_no', $orderNo)->exists()) {
                $this->errors[] = "Row {$rowNumber}: Order #{$orderNo} already exists. Skipped.";
                continue;
            }

            // Find or create Customer
            $customer = Customer::firstOrCreate(
                ['name' => $customerName],
                [
                    'brand' => trim($row['brand'] ?? ''),
                    'session' => trim($row['session'] ?? $row['season'] ?? ''),
                ]
            );

            // Find or create Supplier
            $supplier = Supplier::firstOrCreate(
                ['name' => $supplierName],
                [
                    'location' => trim($row['location'] ?? ''),
                ]
            );

            $orderDate = !empty($row['order_date']) ? date('Y-m-d', strtotime($row['order_date'])) : null;
            $etdDate = !empty($row['etd_date']) ? date('Y-m-d', strtotime($row['etd_date'])) : null;

            CustomerOrder::create([
                'customer_id' => $customer->id,
                'supplier_id' => $supplier->id,
                'order_no' => $orderNo,
                'style_no' => $styleNo,
                'style_name' => trim($row['style_name'] ?? ''),
                'composition' => trim($row['composition'] ?? ''),
                'color_name' => trim($row['color_name'] ?? ''),
                'color_qty' => (int) ($row['color_qty'] ?? 0),
                'order_date' => $orderDate,
                'etd_date' => $etdDate,
                'price' => (float) ($row['price'] ?? 0),
                'notes' => trim($row['notes'] ?? ''),
            ]);

            $this->importedCount++;
        }
    }
}
