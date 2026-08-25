<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->with('orderItems.item')->get();

        return view('owner.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,received,on_the_way,delivered,cancelled'],
        ]);

        $allowed = ['received', 'on_the_way', 'delivered', 'cancelled'];
        if (! in_array($request->status, $allowed)) {
            return back()->withErrors(['status' => 'Invalid status.']);
        }

        $current = $order->status;
        $next = $request->status;

        $transitions = [
            'pending' => ['received', 'cancelled'],
            'received' => ['on_the_way', 'cancelled'],
            'on_the_way' => ['delivered', 'cancelled'],
            'delivered' => [],
            'cancelled' => [],
        ];

        if (! in_array($next, $transitions[$current] ?? [])) {
            return back()->withErrors(['status' => 'Status transition not allowed.']);
        }

        $order->update(['status' => $next]);

        return back()->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->orderItems()->delete();
            $order->delete();
        });

        return back()->with('success', 'Order deleted successfully.');
    }
}
