<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FactoryOrder;
use Illuminate\Http\Request;

class FactoryOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = FactoryOrder::with(['customerOrder.customer', 'customerOrder.supplier', 'followup'])->latest();

        if ($search = $request->input('search')) {
            $query->whereHas('customerOrder', function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                    ->orWhere('style_no', 'like', "%{$search}%")
                    ->orWhere('style_name', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('overdue_only')) {
            $query->whereDate('aetd_date', '<', today());
        }

        $factoryOrders = $query->paginate(15)->withQueryString();

        return view('admin.factory-orders.index', compact('factoryOrders'));
    }

    public function edit(FactoryOrder $factoryOrder)
    {
        $factoryOrder->load(['customerOrder.customer', 'customerOrder.supplier']);
        return view('admin.factory-orders.edit', compact('factoryOrder'));
    }

    public function update(Request $request, FactoryOrder $factoryOrder)
    {
        $validated = $request->validate([
            'etd_price' => 'nullable|numeric|min:0',
            'sub_price' => 'nullable|numeric|min:0',
            'aetd_date' => 'nullable|date',
            'fob_price' => 'nullable|numeric|min:0',
        ]);

        $factoryOrder->update($validated);

        return redirect()->route('admin.factory-orders.index')
            ->with('success', "Factory Order for #{$factoryOrder->customerOrder->order_no} updated successfully.");
    }
}
