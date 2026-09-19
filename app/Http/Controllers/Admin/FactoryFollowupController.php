<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FactoryFollowup;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FactoryFollowupController extends Controller
{
    public function index(Request $request)
    {
        $query = FactoryFollowup::with([
            'factoryOrder.customerOrder.customer',
            'factoryOrder.customerOrder.supplier',
        ])->latest();

        if ($search = $request->input('search')) {
            $query->whereHas('factoryOrder.customerOrder', function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                    ->orWhere('style_no', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('knitting_status')) {
            $query->where('knitting_status', $status);
        }

        if ($status = $request->input('dyeing_status')) {
            $query->where('dyeing_status', $status);
        }

        if ($status = $request->input('cutting_status')) {
            $query->where('cutting_status', $status);
        }

        $followups = $query->paginate(15)->withQueryString();

        return view('admin.factory-followups.index', compact('followups'));
    }

    public function pipeline(Request $request)
    {
        $query = FactoryFollowup::with([
            'factoryOrder.customerOrder.customer',
            'factoryOrder.customerOrder.supplier',
        ])->latest();

        if ($search = $request->input('search')) {
            $query->whereHas('factoryOrder.customerOrder', function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                    ->orWhere('style_no', 'like', "%{$search}%")
                    ->orWhere('style_name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($customerId = $request->input('customer_id')) {
            $query->whereHas('factoryOrder.customerOrder', function ($q) use ($customerId) {
                $q->where('customer_id', $customerId);
            });
        }

        if ($supplierId = $request->input('supplier_id')) {
            $query->whereHas('factoryOrder.customerOrder', function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            });
        }

        $allFollowups = $query->get();

        // Calculate KPI Metrics
        $totalOrders = $allFollowups->count();
        $totalPieces = $allFollowups->sum(function ($fu) {
            return $fu->factoryOrder?->customerOrder?->color_qty ?? 0;
        });
        $totalValue = $allFollowups->sum(function ($fu) {
            $ord = $fu->factoryOrder?->customerOrder;
            return $ord ? ($ord->color_qty * $ord->price) : 0;
        });

        $now = Carbon::now();
        $weekLater = Carbon::now()->addDays(7);

        $overdueCount = $allFollowups->filter(function ($fu) use ($now) {
            $etd = $fu->factoryOrder?->customerOrder?->etd_date;
            return $etd && $etd->isPast() && $fu->current_stage !== 'completed';
        })->count();

        $dueThisWeekCount = $allFollowups->filter(function ($fu) use ($now, $weekLater) {
            $etd = $fu->factoryOrder?->customerOrder?->etd_date;
            return $etd && $etd->between($now, $weekLater) && $fu->current_stage !== 'completed';
        })->count();

        // Group into Kanban Columns
        $columns = [
            'sampling' => $allFollowups->filter(fn($fu) => $fu->current_stage === 'sampling'),
            'knitting' => $allFollowups->filter(fn($fu) => $fu->current_stage === 'knitting'),
            'dyeing' => $allFollowups->filter(fn($fu) => $fu->current_stage === 'dyeing'),
            'cutting' => $allFollowups->filter(fn($fu) => $fu->current_stage === 'cutting'),
            'completed' => $allFollowups->filter(fn($fu) => $fu->current_stage === 'completed'),
        ];

        $customers = Customer::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.factory-followups.pipeline', compact(
            'allFollowups',
            'columns',
            'totalOrders',
            'totalPieces',
            'totalValue',
            'overdueCount',
            'dueThisWeekCount',
            'customers',
            'suppliers'
        ));
    }

    public function edit(FactoryFollowup $factoryFollowup)
    {
        $factoryFollowup->load([
            'factoryOrder.customerOrder.customer',
            'factoryOrder.customerOrder.supplier',
        ]);

        return view('admin.factory-followups.edit', compact('factoryFollowup'));
    }


    public function update(Request $request, FactoryFollowup $factoryFollowup)
    {
        $validated = $request->validate([
            'pps_date' => 'nullable|date',
            'pps_comments_status' => 'required|string|max:100',
            'shs_sending_date' => 'nullable|date',
            'shs_comments_status' => 'required|string|max:100',
            'knitting_status' => 'required|string|max:100',
            'dyeing_status' => 'required|string|max:100',
            'cutting_status' => 'required|string|max:100',
            'fob_price' => 'nullable|numeric|min:0',
            'sub_price' => 'nullable|numeric|min:0',
        ]);

        $factoryFollowup->update($validated);

        return redirect()->route('admin.factory-followups.index')
            ->with('success', "Follow-up for Order #{$factoryFollowup->factoryOrder->customerOrder->order_no} updated successfully.");
    }
}
