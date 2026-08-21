<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ManualSaleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        Order::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => 'WhatsApp order',
            'delivery_address' => 'WhatsApp delivery',
            'total_amount' => $validated['total_amount'],
            'payment_method' => 'COD',
            'status' => 'pending',
            'source' => 'whatsapp',
        ]);

        return back()->with('success', 'WhatsApp sale recorded successfully.');
    }
}