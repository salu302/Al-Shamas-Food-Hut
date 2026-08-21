<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with('orderItems.item')->where('user_id', $request->user()->id)->latest()->paginate(12);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        abort_if($order->user_id !== $request->user()->id, 403);

        $order->load('orderItems.item');

        return view('customer.orders.show', compact('order'));
    }

    public function status(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);

        $map = [
            'pending' => ['en' => 'Pending', 'ur' => 'پینڈنگ'],
            'received' => ['en' => 'Preparing', 'ur' => 'آرڈر موصول ہو گیا'],
            'on_the_way' => ['en' => 'On the Way', 'ur' => 'راستے میں ہے'],
            'delivered' => ['en' => 'Delivered', 'ur' => 'پہنچا دیا گیا'],
            'cancelled' => ['en' => 'Cancelled', 'ur' => 'منسوخ ہو گیا'],
        ];

        $status = $order->fresh()->status;

        return response()->json([
            'status' => $status,
            'label_en' => $map[$status]['en'] ?? ucfirst($status),
            'label_ur' => $map[$status]['ur'] ?? $status,
        ]);
    }
}
