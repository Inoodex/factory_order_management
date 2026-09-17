<?php

namespace App\Exports;

use App\Models\CustomerOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomerOrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return CustomerOrder::with(['customer', 'supplier', 'factoryOrder.followup'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Order No',
            'Customer / Buyer',
            'Brand',
            'Season (Session)',
            'Supplier / Factory',
            'Style No',
            'Style Name',
            'Composition',
            'Color Name',
            'Color Qty',
            'Unit Price',
            'Total Order Value',
            'Order Date',
            'ETD Date',
            'AETD Date',
            'ETD Price',
            'Sub Price',
            'FOB Price',
            'PPS Comments Status',
            'SHS Comments Status',
            'Knitting Status',
            'Dyeing Status',
            'Cutting Status',
        ];
    }

    public function map($order): array
    {
        $fo = $order->factoryOrder;
        $fu = $fo?->followup;

        return [
            $order->order_no,
            $order->customer?->name ?? 'N/A',
            $order->brand ?: ($order->customer?->brand ?? 'N/A'),
            $order->customer?->session ?? 'N/A',
            $order->supplier?->name ?? 'N/A',
            $order->style_no,
            $order->style_name ?? 'N/A',
            $order->composition ?? 'N/A',
            $order->color_name ?? 'N/A',
            $order->color_qty,
            $order->price,
            $order->total_price,
            $order->order_date?->format('Y-m-d') ?? 'N/A',
            $order->etd_date?->format('Y-m-d') ?? 'N/A',
            $fo?->aetd_date?->format('Y-m-d') ?? 'N/A',
            $fo?->etd_price ?? 'N/A',
            $fo?->sub_price ?? 'N/A',
            $fo?->fob_price ?? 'N/A',
            $fu?->pps_comments_status ?? 'Pending',
            $fu?->shs_comments_status ?? 'Pending',
            $fu?->knitting_status ?? 'Not Started',
            $fu?->dyeing_status ?? 'Not Started',
            $fu?->cutting_status ?? 'Not Started',
        ];
    }
}
