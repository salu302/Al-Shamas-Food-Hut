<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $manualSales = Order::where('source', 'whatsapp')->latest()->get();
        $totalRevenue = Order::sum('total_amount');
        $todayRevenue = Order::whereDate('created_at', today())->sum('total_amount');
        $weekRevenue = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount');
        $monthRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        $yearRevenue = Order::whereYear('created_at', now()->year)->sum('total_amount');
        $todayExpenses = Expense::whereDate('expense_date', today())->sum('amount');
        $weekExpenses = Expense::whereBetween('expense_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount');
        $monthExpenses = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');
        $yearExpenses = Expense::whereYear('expense_date', now()->year)->sum('amount');
        $todayProfit = $todayRevenue - $todayExpenses;
        $weekNetProfit = $weekRevenue - $weekExpenses;
        $monthProfit = $monthRevenue - $monthExpenses;
        $yearProfit = $yearRevenue - $yearExpenses;
        $owners = User::whereIn('role', ['owner', 'admin', 'super_admin'])->count();
        $customers = User::where('role', 'customer')->count();
        $complaints = Complaint::latest()->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'manualSales', 'totalRevenue', 'owners', 'customers', 'complaints',
            'todayRevenue', 'weekRevenue', 'monthRevenue', 'yearRevenue',
            'todayExpenses', 'weekExpenses', 'monthExpenses', 'yearExpenses',
            'todayProfit', 'weekNetProfit', 'monthProfit', 'yearProfit'
        ));
    }

    public function resetData(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized.');
        }

        DB::transaction(function () {
            OrderItem::query()->delete();
            Order::query()->delete();
            Complaint::query()->delete();
            Expense::query()->delete();
        });

        return redirect()->route('admin.dashboard')->with('success', 'System data reset successfully.');
    }
}
