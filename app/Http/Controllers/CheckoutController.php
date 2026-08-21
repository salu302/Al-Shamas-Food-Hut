<?php

namespace App\Http\Controllers;

use App\Mail\CustomerOrderConfirmationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $cart = session('cart', []);
        $itemIds = collect($cart)->pluck('item_id')->unique()->filter()->values();
        $items = Item::whereIn('id', $itemIds)->get()->keyBy('id');

        $cartItems = collect($cart)->map(function ($line, $key) use ($items) {
            $item = $items->get($line['item_id']);
            if (! $item) {
                return null;
            }

            return array_merge($line, [
                'key' => $key,
                'item' => $item,
                'subtotal' => $line['unit_price'] * $line['quantity'],
            ]);
        })->filter()->values();

        $total = $cartItems->sum('subtotal');

        return view('checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'delivery_address' => ['required', 'string', 'max:1000'],
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $itemIds = collect($cart)->pluck('item_id')->unique()->filter()->values();
        $items = Item::whereIn('id', $itemIds)->get()->keyBy('id');

        $cartItems = collect($cart)->map(function ($line) use ($items) {
            $item = $items->get($line['item_id']);
            if (! $item) {
                return null;
            }

            return array_merge($line, [
                'item' => $item,
                'subtotal' => $line['unit_price'] * $line['quantity'],
            ]);
        })->filter()->values();

        $total = $cartItems->sum('subtotal');

        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'delivery_address' => $request->delivery_address,
            'total_amount' => $total,
            'payment_method' => 'COD',
            'status' => 'pending',
        ]);

        foreach ($cartItems as $line) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $line['item_id'],
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'subtotal' => $line['subtotal'],
                'variant_name' => $line['variant_name'] ?? null,
            ]);
        }

        $notification = new OrderPlacedNotification($order);
        $notification->logPayload();
        $order->notify($notification);

        try {
            Mail::to(config('mail.from.address', 'owner@example.com'))->send(new OrderConfirmationMail($order));
            if ($order->customer_email) {
                Mail::to($order->customer_email)->send(new CustomerOrderConfirmationMail($order));
            }
        } catch (\Exception $e) {
            \Log::error("Email notification failed: " . $e->getMessage());
        }

        session()->forget('cart');

        return redirect()->route('order.success', $order)->with('success', 'Order placed successfully.');
    }

    public function thankyou(Order $order)
    {
        return view('thankyou', compact('order'));
    }
}
