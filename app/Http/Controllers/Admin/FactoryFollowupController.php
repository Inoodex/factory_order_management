<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FactoryFollowup;
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
                    ->orWhere('style_no', 'like', "%{$search}%");
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

    public function pipeline()
    {
        $followups = FactoryFollowup::with([
            'factoryOrder.customerOrder.customer',
            'factoryOrder.customerOrder.supplier',
        ])->latest()->get();

        return view('admin.factory-followups.pipeline', compact('followups'));
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
