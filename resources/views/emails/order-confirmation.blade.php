<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body style="margin: 0; padding: 0; background: #111827; color: #f8fafc; font-family: Arial, sans-serif;">
    <div style="max-width: 640px; margin: 0 auto; padding: 32px 20px;">
        <div style="background: #18181b; border: 1px solid #334155; border-radius: 20px; padding: 24px;">
            <h2 style="margin: 0 0 12px; color: #fbbf24;">Al-Shamas Pizza Hut</h2>

            @if($isCustomerCopy)
                <p style="margin: 0 0 16px; color: #e2e8f0;">Hi {{ $order->customer_name }}, your order has been received.</p>
            @else
                <p style="margin: 0 0 16px; color: #e2e8f0;">New order received from {{ $order->customer_name }}.</p>
            @endif

            <p style="margin: 0 0 8px; color: #e2e8f0;"><strong>Order ID:</strong> #{{ $order->id }}</p>
            <p style="margin: 0 0 8px; color: #e2e8f0;"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
            @if($order->customer_email)
                <p style="margin: 0 0 8px; color: #e2e8f0;"><strong>Email:</strong> {{ $order->customer_email }}</p>
            @endif
            <p style="margin: 0 0 8px; color: #e2e8f0;"><strong>Address:</strong> {{ $order->delivery_address }}</p>
            <p style="margin: 0 0 8px; color: #e2e8f0;"><strong>Payment:</strong> COD</p>
            <p style="margin: 0 0 20px; color: #e2e8f0;"><strong>Total:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>

            <div style="background: #0f172a; padding: 16px; border-radius: 12px;">
                <p style="margin: 0 0 8px; color: #cbd5e1; font-weight: bold;">Items</p>
                @foreach($order->orderItems()->with('item')->get() as $orderItem)
                    <p style="margin: 4px 0; color: #e2e8f0;">
                        {{ $orderItem->item?->name_en ?? 'Item' }} x {{ $orderItem->quantity }} — Rs. {{ number_format($orderItem->subtotal, 2) }}
                    </p>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
