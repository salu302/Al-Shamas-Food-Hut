<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-12 text-center">
        <h1 class="text-4xl font-semibold text-white">Thank you, {{ $order->customer_name }}!</h1>
        <p class="mt-4 text-slate-400">Your order #{{ $order->id }} has been placed successfully.</p>
        <div class="mt-8 rounded-[32px] bg-[#0D0D0D] p-8 text-left text-slate-300">
            <p><span class="font-semibold text-white">Order #:</span> {{ $order->id }}</p>
            <p class="mt-2"><span class="font-semibold text-white">Customer:</span> {{ $order->customer_name }} &middot; {{ $order->customer_phone }}</p>
            <p class="mt-2"><span class="font-semibold text-white">Delivery Address:</span> {{ $order->delivery_address }}</p>
            <hr class="my-4 border-slate-800">
            <div>
                <p class="font-semibold text-white">Items Ordered</p>
                <ul class="mt-2 space-y-2">
                    @foreach($order->orderItems()->with('item')->get() as $oi)
                        <li class="text-sm text-slate-300">{{ $oi->item->name_en }} @if($oi->variant_name) ({{ $oi->variant_name }}) @endif — x{{ $oi->quantity }} — Rs. {{ number_format($oi->subtotal, 2) }}</li>
                    @endforeach
                </ul>
            </div>
            <hr class="my-4 border-slate-800">
            <p class="mt-2"><span class="font-semibold text-white">Total Bill:</span> Rs. {{ number_format($order->total_amount, 2) }}</p>
            <p class="mt-2"><span class="font-semibold text-white">Payment:</span> {{ $order->payment_method }}</p>
        </div>
        @php
            $itemsRaw = $order->orderItems()->with('item')->get()->map(function($oi){
                return ($oi->item->name_en ?? '') . ($oi->variant_name ? ' (' . $oi->variant_name . ')' : '') . ' x' . $oi->quantity;
            })->implode(', ');

            $waText = "Hi Al-Shamas Pizza Hut! I just placed Order #{$order->id}.\n\nCustomer: {$order->customer_name}\nPhone: {$order->customer_phone}\nItems: {$itemsRaw}\nTotal Bill: Rs. {$order->total_amount}\n\nPlease confirm my delivery!";
            $waUrl = 'https://wa.me/923428544110?text=' . urlencode($waText);
        @endphp

        <div class="mt-8 flex items-center justify-center">
            <a href="{{ route('home') }}" class="inline-flex rounded-3xl bg-[#FFB703] px-6 py-3 text-sm font-semibold text-[#0D0D0D]">Back to Menu</a>
        </div>
    </div>
</x-app-layout>
