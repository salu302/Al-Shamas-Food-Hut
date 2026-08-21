<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-white">My Orders</h1>
                <p class="text-slate-400">Review your recent orders and track progress.</p>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="rounded-[20px] bg-[#0D0D0D] border border-slate-800 p-4 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-slate-400">Order #{{ $order->id }} • {{ $order->created_at->format('d M Y, H:i') }}</div>
                        @php
                            $statusMap = [
                                'pending' => ['en' => 'Pending', 'ur' => 'پینڈنگ'],
                                'received' => ['en' => 'Preparing', 'ur' => 'آرڈر موصول ہو گیا'],
                                'on_the_way' => ['en' => 'On the Way', 'ur' => 'راستے میں ہے'],
                                'delivered' => ['en' => 'Delivered', 'ur' => 'پہنچا دیا گیا'],
                                'cancelled' => ['en' => 'Cancelled', 'ur' => 'منسوخ ہو گیا'],
                            ];
                            $lbl = $statusMap[$order->status] ?? ['en' => ucfirst($order->status), 'ur' => ''];
                        @endphp
                        <h3 class="text-lg text-white font-semibold">Rs. {{ number_format($order->total_amount, 2) }} • {{ $lbl['en'] }} / {{ $lbl['ur'] }}</h3>
                        <div class="text-sm text-slate-400">Payment: {{ $order->payment_method ?? 'COD' }}</div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('customer.orders.show', $order) }}" class="rounded-full bg-[#FFB703] px-4 py-2 text-sm font-semibold text-[#0D0D0D]">View</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
