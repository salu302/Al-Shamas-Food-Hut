<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-8">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-white">Quick Checkout</h1>
                <p class="text-slate-400">Enter your details and place your order with cash on delivery.</p>
            </div>
            <span class="rounded-full bg-slate-900 px-4 py-3 text-sm text-slate-300">COD Only</span>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-4 rounded-[32px] bg-[#222222] p-6">
                <h2 class="text-xl font-semibold text-white">Your Cart</h2>
                @foreach($cartItems as $line)
                    <div class="flex items-center gap-4 rounded-3xl border border-slate-800 bg-[#181818] p-4" data-cart-key="{{ $line['key'] }}">
                        <button type="button" class="text-slate-400 hover:text-red-400 mr-2 cart-remove" data-key="{{ $line['key'] }}">✕</button>
                        <div class="h-16 w-16 rounded-3xl bg-[#0D0D0D] text-2xl flex items-center justify-center">🍽️</div>
                        <div class="flex-1">
                            <p class="font-semibold text-white">{{ $line['item']->name_en }} @if(!empty($line['variant_name'])) ({{ $line['variant_name'] }}) @endif</p>
                            <p class="text-sm text-slate-400">Qty: {{ $line['quantity'] }}</p>
                        </div>
                        <span class="font-semibold text-[#FFB703] line-subtotal">Rs. {{ number_format($line['subtotal'], 2) }}</span>
                    </div>
                @endforeach
                <div class="rounded-3xl bg-[#0D0D0D] p-4 text-sm text-slate-300">
                    <div class="flex justify-between"><span>Total</span><span id="cart-total" class="font-semibold text-white">Rs. {{ number_format($total, 2) }}</span></div>
                </div>
            </div>

            <form action="{{ route('checkout.place') }}" method="POST" class="space-y-6 rounded-[32px] bg-[#222222] p-6">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Name</label>
                    <input name="customer_name" value="{{ old('customer_name') }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white focus:border-[#FFB703] focus:outline-none" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Phone</label>
                    <input name="customer_phone" value="{{ old('customer_phone') }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white focus:border-[#FFB703] focus:outline-none" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Email Address</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="Email (Optional)" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white placeholder:text-slate-500 focus:border-[#FFB703] focus:outline-none" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Delivery Address</label>
                    <textarea name="delivery_address" rows="4" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white focus:border-[#FFB703] focus:outline-none">{{ old('delivery_address') }}</textarea>
                </div>
                <button type="submit" class="w-full rounded-3xl bg-[#FFB703] px-5 py-3 text-sm font-semibold text-[#0D0D0D] hover:bg-[#e6a900]">Place Order</button>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    document.querySelectorAll('.cart-remove').forEach(btn => {
        btn.addEventListener('click', function () {
            const key = this.dataset.key;
            if (! key) return;

            if (! confirm('Remove this item from cart?')) return;

            fetch(@json(route('cart.remove')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ cart_key: key })
            }).then(res => res.json()).then(data => {
                if (data && data.success) {
                    // remove line from DOM
                    const el = document.querySelector('[data-cart-key="' + key + '"]');
                    if (el) el.remove();

                    // update total
                    const totalEl = document.getElementById('cart-total');
                    if (totalEl) totalEl.textContent = 'Rs. ' + Number(data.cart_total).toFixed(2);

                    // update nav cart count if present
                    const cartCountEl = document.getElementById('cart-count');
                    if (cartCountEl) cartCountEl.textContent = data.cart_count;
                }
            }).catch(err => {
                console.error(err);
                alert('Unable to remove item.');
            });
        });
    });
});
</script>
