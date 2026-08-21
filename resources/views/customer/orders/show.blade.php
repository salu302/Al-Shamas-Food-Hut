<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="rounded-[20px] bg-[#0D0D0D] border border-slate-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-semibold text-white">Order #{{ $order->id }}</h2>
                    <p class="text-slate-400">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="text-sm text-slate-400">Status: <span class="text-white">{{ ucfirst(str_replace('_',' ', $order->status)) }}</span></div>
            </div>

            <div class="mb-6">
                <h3 class="text-lg text-white font-semibold mb-2">Tracking</h3>
                <div class="flex items-center gap-4">
                    @php
                        $steps = ['pending' => 1, 'received' => 2, 'on_the_way' => 3, 'delivered' => 4];
                        $current = $steps[$order->status] ?? 1;
                    @endphp
                    @foreach(['Pending','Received','On The Way','Delivered'] as $i => $label)
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full flex items-center justify-center step-circle {{ $i+1 <= $current ? 'active' : '' }}">{{ $i+1 }}</div>
                            <div class="text-sm step-label {{ $i+1 <= $current ? 'active' : '' }}">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-lg text-white font-semibold mb-2">Items</h3>

                <div class="space-y-3">
                    @foreach($order->orderItems as $oi)
                        <div class="flex items-center justify-between bg-[#111111] p-3 rounded-md">
                            <div>
                                <div class="text-white font-medium">{{ $oi->item->name_en }} @if($oi->variant_name) ({{ $oi->variant_name }}) @endif / {{ $oi->item->name_ur }}</div>
                                <div class="text-sm text-slate-400">Qty: {{ $oi->quantity }} • Rs. {{ number_format($oi->unit_price,2) }}</div>
                            </div>
                            <div class="text-white">Rs. {{ number_format($oi->subtotal, 2) }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 text-right">
                    <div class="text-slate-400">Delivery Address: <span class="text-white">{{ $order->delivery_address }}</span></div>
                    <div class="text-slate-400">Phone: <span class="text-white">{{ $order->customer_phone }}</span></div>
                    <div class="text-white font-semibold text-lg mt-2">Total: Rs. {{ number_format($order->total_amount,2) }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const orderId = {{ $order->id }};
    const statusLabelEl = document.querySelector('.text-sm.text-white') || document.querySelector('.step-label.active');
    const stepCircles = document.querySelectorAll('.step-circle');
    const stepLabels = document.querySelectorAll('.step-label');

    function applyStatus(status) {
        const map = { 'pending':1, 'received':2, 'on_the_way':3, 'delivered':4 };
        const current = map[status] || 1;

        stepCircles.forEach((el, idx) => {
            el.classList.toggle('active', (idx+1) <= current);
        });
        stepLabels.forEach((el, idx) => {
            el.classList.toggle('active', (idx+1) <= current);
        });
    }

    async function fetchStatus(){
        try{
            const res = await fetch(`{{ url('/my-orders') }}/${orderId}/status`);
            if(!res.ok) return;
            const data = await res.json();
            applyStatus(data.status);
            // update any label text if needed
        }catch(e){/* ignore */}
    }

    // poll every 5 seconds
    fetchStatus();
    setInterval(fetchStatus, 5000);
});
</script>
