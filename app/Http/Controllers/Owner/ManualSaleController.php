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

    public function update(Request $request, Order $sale)
    {
        $this->ensureManualSale($sale);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $sale->update($validated);

        return back()->with('success', 'WhatsApp sale updated successfully.');
    }

    public function destroy(Order $sale)
    {
        $this->ensureManualSale($sale);
        $sale->delete();

        return back()->with('success', 'WhatsApp sale deleted successfully.');
    }

    private function ensureManualSale(Order $sale): void
    {
        abort_unless($sale->source === 'whatsapp', 404);
    }
}