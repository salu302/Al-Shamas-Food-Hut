<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Confirmation</title>
</head>
<body style="margin: 0; padding: 0; background: #fff7ed; color: #111827; font-family: Arial, sans-serif;">
    <div style="max-width: 680px; margin: 0 auto; padding: 32px 20px;">
        <div style="background: #ffffff; border: 1px solid #fed7aa; border-radius: 20px; padding: 28px;">
            <h2 style="margin: 0 0 16px; color: #c2410c; font-size: 28px;">Thank you for your order, {{ $order->customer_name }}!</h2>

            <p style="margin: 0 0 12px; color: #374151; font-size: 16px;">We will deliver your order soon!</p>

            <div style="background: #fff7ed; border: 1px solid #fdba74; border-radius: 12px; padding: 16px; margin: 20px 0;">
                <p style="margin: 0 0 8px; color: #111827;"><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p style="margin: 0 0 8px; color: #111827;"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                <p style="margin: 0 0 8px; color: #111827;"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                @if($order->customer_email)
                    <p style="margin: 0; color: #111827;"><strong>Email:</strong> {{ $order->customer_email }}</p>
                @endif
            </div>

            <div style="margin: 20px 0;">
                <h3 style="margin: 0 0 12px; color: #111827;">Order Summary</h3>
                @foreach($order->orderItems()->with('item')->get() as $orderItem)
                    <p style="margin: 5px 0; color: #374151;">
                        {{ $orderItem->item?->name_en ?? 'Item' }} x {{ $orderItem->quantity }} — Rs. {{ number_format($orderItem->subtotal, 2) }}
                    </p>
                @endforeach
            </div>

            <p style="margin: 20px 0 8px; color: #111827;"><strong>Total Bill:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>
            <p style="margin: 0 0 8px; color: #111827;"><strong>Payment Method:</strong> Cash on Delivery (COD)</p>

            <div style="margin-top: 26px; padding-top: 20px; border-top: 1px solid #fed7aa; color: #374151;">
                <p style="margin: 0 0 6px; font-weight: bold; color: #111827;">Store Contact</p>
                <p style="margin: 0;">Al-Shamas Pizza Hut</p>
                <p style="margin: 0;">WhatsApp: +92 342 8544110</p>
                <p style="margin: 0;">Email: sa33766an@gmail.com</p>
            </div>
        </div>
    </div>
</body>
</html>
