<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerOrder::with(['customer', 'supplier', 'factoryOrder.followup'])->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                    ->orWhere('style_no', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('style_name', 'like', "%{$search}%")
                    ->orWhere('color_name', 'like', "%{$search}%")
                    ->orWhere('composition', 'like', "%{$search}%");
            });
        }

        if ($customerId = $request->input('customer_id')) {
            $query->where('customer_id', $customerId);
        }

        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('etd_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('etd_date', '<=', $request->date_to);
        }

        if ($request->boolean('overdue_only')) {
            $query->whereDate('etd_date', '<', today());
        }

        $orders = $query->paginate(15)->withQueryString();
        $customers = Customer::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.customer-orders.index', compact('orders', 'customers', 'suppliers'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        // Suggest a new unique Order No
        $lastId = CustomerOrder::max('id') ?? 0;
        $suggestedOrderNo = 'ORD-' . date('Y') . '-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        return view('admin.customer-orders.create', compact('customers', 'suppliers', 'suggestedOrderNo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_no' => 'required|string|max:100|unique:customer_orders,order_no',
            'style_no' => 'required|string|max:100',
            'style_name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:100',
            'composition' => 'nullable|string|max:255',
            'color_name' => 'nullable|string|max:100',
            'color_qty' => 'required|integer|min:0',
            'order_date' => 'nullable|date',
            'etd_date' => 'nullable|date',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:2000',
            'style_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        if ($request->hasFile('style_image')) {
            $path = $request->file('style_image')->store('styles', 'public');
            $validated['style_image'] = $path;
        }

        $order = CustomerOrder::create($validated);

        return redirect()->route('admin.customer-orders.show', $order)
            ->with('success', "Order #{$order->order_no} created successfully. Factory order and production tracking initialized.");
    }

    public function show(CustomerOrder $customerOrder)
    {
        $customerOrder->load(['customer', 'supplier', 'factoryOrder.followup', 'payments', 'invoices']);
        return view('admin.customer-orders.show', compact('customerOrder'));
    }

    public function edit(CustomerOrder $customerOrder)
    {
        $customers = Customer::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.customer-orders.edit', compact('customerOrder', 'customers', 'suppliers'));
    }

    public function update(Request $request, CustomerOrder $customerOrder)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_no' => 'required|string|max:100|unique:customer_orders,order_no,' . $customerOrder->id,
            'style_no' => 'required|string|max:100',
            'style_name' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:100',
            'composition' => 'nullable|string|max:255',
            'color_name' => 'nullable|string|max:100',
            'color_qty' => 'required|integer|min:0',
            'order_date' => 'nullable|date',
            'etd_date' => 'nullable|date',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:2000',
            'style_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        if ($request->hasFile('style_image')) {
            if ($customerOrder->style_image && Storage::disk('public')->exists($customerOrder->style_image)) {
                Storage::disk('public')->delete($customerOrder->style_image);
            }
            $validated['style_image'] = $request->file('style_image')->store('styles', 'public');
        }

        $customerOrder->update($validated);

        return redirect()->route('admin.customer-orders.show', $customerOrder)
            ->with('success', "Order #{$customerOrder->order_no} updated successfully.");
    }

    public function destroy(CustomerOrder $customerOrder)
    {
        if ($customerOrder->style_image && Storage::disk('public')->exists($customerOrder->style_image)) {
            Storage::disk('public')->delete($customerOrder->style_image);
        }

        $orderNo = $customerOrder->order_no;
        $customerOrder->delete();

        return redirect()->route('admin.customer-orders.index')
            ->with('success', "Order #{$orderNo} deleted successfully.");
    }
}
