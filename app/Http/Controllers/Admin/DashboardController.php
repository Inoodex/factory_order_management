<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Customer, CustomerOrder, FactoryOrder, FactoryFollowup, Payment, Salary, Supplier};
use HasinHayder\Tyro\Models\{Privilege, Role};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userModel = config('tyro-dashboard.user_model', 'App\\Models\\User');

        $totalOrderQty = CustomerOrder::sum('color_qty') ?? 0;
        $totalOrderValue = CustomerOrder::get()->sum('total_price') ?? 0;
        $overdueCount = CustomerOrder::whereDate('etd_date', '<', today())->count();
        $totalOrdersCount = CustomerOrder::count();
        $onTimeCount = max(0, $totalOrdersCount - $overdueCount);

        $stats = [
            'total_users' => class_exists($userModel) ? $userModel::count() : 0,
            'total_roles' => class_exists(Role::class) ? Role::count() : 0,
            'total_privileges' => class_exists(Privilege::class) ? Privilege::count() : 0,

            // Factory Order Stats
            'total_orders' => $totalOrdersCount,
            'total_order_qty' => $totalOrderQty,
            'total_order_value' => $totalOrderValue,
            'overdue_orders' => $overdueCount,
            'on_time_orders' => $onTimeCount,
            'on_time_rate' => $totalOrdersCount > 0 ? round(($onTimeCount / $totalOrdersCount) * 100) : 100,
            'total_customers' => Customer::count(),
            'total_suppliers' => Supplier::count(),

            // Finance & Revenue
            'total_revenue' => Payment::where('payment_status', 'completed')->sum('amount'),

            // Salary & HR stats
            'total_salary_expense' => Salary::sum('net_salary'),
            'total_salary_paid' => Salary::where('payment_status', 'paid')->sum('paid_amount'),
            'total_salary_pending' => Salary::where('payment_status', 'pending')->sum('net_salary'),
            'total_salary_partial' => Salary::where('payment_status', 'partial')->sum(DB::raw('net_salary - paid_amount')),
        ];

        // 1. Monthly Trends (Last 6 Months)
        $monthlyLabels = [];
        $monthlyQuantities = [];
        $monthlyValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');

            $monthOrders = CustomerOrder::whereYear('order_date', $date->year)
                ->whereMonth('order_date', $date->month)
                ->get();

            $monthlyQuantities[] = (int) $monthOrders->sum('color_qty');
            $monthlyValues[] = round((float) $monthOrders->sum(function ($o) {
                return $o->color_qty * $o->price;
            }), 2);
        }

        // 2. Production Pipeline Breakdown
        $pipelineStages = [
            'PPS Sampling' => 0,
            'Knitting' => 0,
            'Dyeing' => 0,
            'Cutting & Sewing' => 0,
            'Finishing / Ready' => 0,
        ];

        $allOrders = CustomerOrder::with('factoryOrder.followup')->get();
        foreach ($allOrders as $order) {
            $f = $order->factoryOrder?->followup;
            if (!$f) {
                $pipelineStages['PPS Sampling']++;
                continue;
            }

            if ($f->cutting_status === 'Completed') {
                $pipelineStages['Finishing / Ready']++;
            } elseif ($f->cutting_status === 'In Progress') {
                $pipelineStages['Cutting & Sewing']++;
            } elseif ($f->dyeing_status === 'In Progress' || $f->dyeing_status === 'Completed') {
                $pipelineStages['Dyeing']++;
            } elseif ($f->knitting_status === 'In Progress' || $f->knitting_status === 'Completed') {
                $pipelineStages['Knitting']++;
            } else {
                $pipelineStages['PPS Sampling']++;
            }
        }

        // 3. Top Buyers
        $topBuyers = Customer::withCount('customerOrders')
            ->withSum('customerOrders', 'color_qty')
            ->orderByDesc('customer_orders_sum_color_qty')
            ->take(5)
            ->get()
            ->map(function ($c) {
                return [
                    'name' => $c->brand ? $c->name . ' (' . $c->brand . ')' : $c->name,
                    'qty' => (int) ($c->customer_orders_sum_color_qty ?? 0),
                    'orders_count' => (int) $c->customer_orders_count,
                ];
            });

        // 4. Factory Allocation
        $factoryAllocation = Supplier::withCount('customerOrders')
            ->withSum('customerOrders', 'color_qty')
            ->orderByDesc('customer_orders_sum_color_qty')
            ->take(5)
            ->get()
            ->map(function ($s) {
                return [
                    'name' => $s->name,
                    'qty' => (int) ($s->customer_orders_sum_color_qty ?? 0),
                    'orders_count' => (int) $s->customer_orders_count,
                ];
            });

        // 5. Overdue and Urgent Orders
        $overdueOrders = CustomerOrder::with(['customer', 'supplier'])
            ->whereDate('etd_date', '<', today())
            ->latest('etd_date')
            ->take(5)
            ->get();

        $recentOrders = CustomerOrder::with(['customer', 'supplier', 'factoryOrder.followup'])->latest()->take(10)->get();
        $recentPayments = Payment::with(['officeAccount', 'collector'])->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'recentPayments',
            'monthlyLabels',
            'monthlyQuantities',
            'monthlyValues',
            'pipelineStages',
            'topBuyers',
            'factoryAllocation',
            'overdueOrders'
        ));
    }
}
