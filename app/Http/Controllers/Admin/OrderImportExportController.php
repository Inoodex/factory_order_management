<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomerOrdersExport;
use App\Http\Controllers\Controller;
use App\Imports\CustomerOrdersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderImportExportController extends Controller
{
    public function export()
    {
        $fileName = 'factory_orders_' . date('Y_m_d_His') . '.xlsx';
        return Excel::download(new CustomerOrdersExport, $fileName);
    }

    public function importView()
    {
        return view('admin.customer-orders.import');
    }

    public function downloadTemplate()
    {
        $headers = [
            'order_no',
            'customer_name',
            'brand',
            'season',
            'supplier_name',
            'location',
            'style_no',
            'style_name',
            'composition',
            'color_name',
            'color_qty',
            'price',
            'order_date',
            'etd_date',
            'notes',
        ];

        $sampleRow = [
            'ORD-2026-00001',
            'Zara Sourcing Ltd',
            'Zara Man',
            'Summer 2026',
            'Apex Textile Mills',
            'Gazipur, Dhaka',
            'STY-9081',
            'Men Crewneck Jersey',
            '100% Organic Cotton',
            'Navy Blue',
            '5000',
            '4.85',
            '2026-09-01',
            '2026-11-15',
            'Enzyme washed, export packaging',
        ];

        $output = implode(',', $headers) . "\n" . implode(',', $sampleRow) . "\n";

        return response($output, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_import_template.csv"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new CustomerOrdersImport();

        try {
            Excel::import($import, $request->file('file'));

            $message = "Successfully imported {$import->importedCount} orders.";
            if (!empty($import->errors)) {
                return redirect()->route('admin.customer-orders.index')
                    ->with('warning', $message . ' Some rows had warnings: ' . implode(' | ', array_slice($import->errors, 0, 5)));
            }

            return redirect()->route('admin.customer-orders.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => 'Import failed: ' . $e->getMessage()]);
        }
    }
}
