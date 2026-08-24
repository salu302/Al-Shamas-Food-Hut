<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-white">Owner Order Management</h1>
                <p class="text-slate-400">View the incoming orders and update the status with one click.</p>
            </div>
        </div>

        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="rounded-[32px] bg-[#222222] border border-slate-800 p-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm text-slate-400">Order #{{ $order->id }} · Placed {{ $order->created_at->timezone('Asia/Karachi')->format('d M Y, h:i A') }}</p>
                            @if($order->status === 'delivered')
                                <p class="mt-1 text-sm text-emerald-300">Delivered {{ $order->updated_at->timezone('Asia/Karachi')->format('d M Y, h:i A') }}</p>
                            @endif
                            <div class="text-lg font-semibold text-white">{{ $order->customer_name }} <span class="text-sm font-normal text-gray-400">· {{ $order->customer_phone }}</span></div>
                            <div class="text-sm text-gray-400">{{ $order->delivery_address }}</div>
                        </div>
                        <div class="flex flex-wrap gap-3 text-sm">
                            <span class="rounded-full bg-slate-900 px-4 py-2 text-slate-300">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                            <span class="rounded-full bg-slate-900 px-4 py-2 text-slate-300">Rs. {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 lg:grid-cols-2">
                        <div class="rounded-3xl bg-[#0D0D0D] p-4 text-slate-300">
                            <h3 class="font-semibold text-white">Items</h3>
                            <ul class="mt-3 space-y-2">
                                @foreach($order->orderItems as $item)
                                    <li class="flex items-center justify-between gap-3 text-sm">
                                        <span>{{ $item->item->name_en }} x{{ $item->quantity }}</span>
                                        <span class="text-[#FFB703]">Rs. {{ number_format($item->subtotal, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <form action="{{ route('owner.orders.updateStatus', $order) }}" method="POST" class="space-y-3 rounded-3xl bg-[#0D0D0D] p-4">
                            @csrf
                            <h3 class="font-semibold text-white">Update Status</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['received' => 'Mark as Received', 'on_the_way' => 'Mark as On the Way', 'delivered' => 'Mark as Delivered', 'cancelled' => 'Cancel'] as $value => $label)
                                    <button type="submit" name="status" value="{{ $value }}" class="rounded-full bg-slate-900 px-4 py-2 text-sm text-slate-300 hover:bg-slate-800">{{ $label }}</button>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
