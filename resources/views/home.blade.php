<x-app-layout>
    <style>html{scroll-behavior:smooth;}</style>

    <div x-data="menuPage()" class="bg-[#0D0D0D] text-white">
        @guest
            <div x-data="{ open: true }" x-show="open" x-transition x-cloak class="bg-[#FFB703] text-[#0D0D0D]">
                <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                    <div class="text-center text-sm font-semibold sm:text-left">
                        TO TRACK YOUR ORDER KINDLY <a href="{{ route('login') }}" class="underline underline-offset-2 hover:text-white">LOG IN</a> / <a href="{{ route('register') }}" class="underline underline-offset-2 hover:text-white">REGISTER</a>
                        <span class="ml-2">(اپنا آرڈر ٹریک کرنے کے لیے لاگ ان کریں)</span>
                    </div>
                    <button type="button" @click="open = false" class="rounded-full bg-[#111111] px-3 py-1 text-xs font-bold text-white">Close</button>
                </div>
            </div>
        @endguest

        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,183,3,0.14),_transparent_35%)]"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] items-center">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-3 rounded-full bg-[#181818] px-4 py-2 text-sm font-semibold text-[#FFB703] border border-slate-800">
                            MAIN SARGODHA ROAD, KHAI ADDA
                        </div>
                        <h1 class="text-5xl font-semibold tracking-tight text-white">Hot, Fresh & Delivered Fast</h1>
                        <p class="max-w-2xl text-slate-300">Wood-style pizzas, loaded burgers, sizzling shawarma and party-ready deals — all from one kitchen, right to your door.</p>

                        <div class="flex flex-wrap gap-3">
                            <a href="#menu-section" class="inline-flex items-center justify-center rounded-full bg-[#FFB703] px-6 py-3 text-sm font-semibold text-[#0D0D0D]">View Menu</a>
                            <a href="https://wa.me/923428544110?text=Hi%20Al-Shamas%20Pizza%20Hut,%20I%20would%20like%20to%20place%20an%20order." target="_blank" rel="noreferrer" class="inline-flex items-center justify-center rounded-full bg-[#181818] border border-slate-800 px-6 py-3 text-sm font-semibold text-white">Order on WhatsApp</a>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-3xl bg-[#181818] border border-slate-800 px-5 py-4 text-sm text-slate-200">🚀 Free Home Delivery on orders above Rs. 1000</div>
                            <div class="rounded-3xl bg-[#181818] border border-slate-800 px-5 py-4 text-sm text-slate-200">💵 Cash on Delivery available</div>
                        </div>
                    </div>

                    <div class="rounded-[32px] border border-slate-800 bg-[#181818] p-8 shadow-2xl shadow-black/20">
                        <div class="rounded-[32px] bg-[#0D0D0D] p-8 border border-slate-800">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Featured</p>
                                    <h2 class="mt-3 text-3xl font-semibold text-white">Al-Shamas Bestsellers</h2>
                                </div>
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#FFB703]/15 text-3xl text-[#FFB703]">🍕</div>
                            </div>

                            <div class="mt-8 grid gap-4">
                                <div class="rounded-3xl bg-[#181818] border border-slate-800 p-5">
                                    <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Popular Choice</p>
                                    <p class="mt-2 text-xl font-semibold text-white">Zinger Cheese Burger</p>
                                    <p class="mt-2 text-sm text-slate-400">Juicy chicken patty loaded with cheese and fresh toppings.</p>
                                </div>
                                <div class="rounded-3xl bg-[#181818] border border-slate-800 p-5">
                                    <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Family Deal</p>
                                    <p class="mt-2 text-xl font-semibold text-white">Deal 950</p>
                                    <p class="mt-2 text-sm text-slate-400">Perfect party combo with pizza, fries, and drinks.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="menu-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-3xl font-semibold text-white">Menu Categories</h2>
                    <p class="mt-2 text-slate-400">Choose from pizzas, burgers, shawarma, pasta, fries, rolls, wings, and deals.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <a href="{{ route('home') }}?category={{ urlencode($category->name_en) }}#menu-section" class="whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition {{ ($selectedCategory && $selectedCategory->id === $category->id) ? 'bg-[#FFB703] text-[#0D0D0D]' : 'bg-[#181818] text-slate-300 hover:bg-slate-900' }}">
                            <span class="mr-2">{{ $category->emoji }}</span>{{ $locale === 'ur' ? $category->name_ur : $category->name_en }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                @forelse($selectedCategory?->items ?? [] as $item)
                    <div class="group relative overflow-hidden rounded-[32px] bg-[#181818] border border-slate-800 p-6">
                        <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-[#FFB703]/10 blur-2xl"></div>
                        <div class="relative">
                            <div class="absolute right-0 top-0 flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-[#222222] text-4xl text-[#FFB703] shadow-inner shadow-black/30">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name_en }}" class="h-full w-full object-cover" />
                                @else
                                    {{ $item->category?->emoji ?? '🍽️' }}
                                @endif
                            </div>
                            <div class="mb-6 pt-4">
                                <p class="text-sm text-slate-500">{{ $locale === 'ur' ? $item->category->name_ur : $item->category->name_en }}</p>
                                <h3 class="mt-3 text-2xl font-semibold text-white">{{ $locale === 'ur' ? $item->name_ur : $item->name_en }}</h3>
                                <p class="mt-2 text-sm text-slate-400">{{ $locale === 'ur' ? $item->description_ur : $item->description_en }}</p>
                            </div>

                            <div class="mt-8 flex items-center justify-between gap-4">
                                <div>
                                    <span class="text-sm text-slate-400">{{ $item->variants->count() ? 'From' : '' }}</span>
                                    <p class="text-xl font-semibold text-white">
                                        Rs. {{ number_format($item->variants->count() ? $item->variants->min('price') : $item->price, 0) }}
                                    </p>
                                </div>
                                @if($item->is_available)
                                    @if($item->variants->count())
                                        @php
                                            $itemPayload = [
                                                'id' => $item->id,
                                                'name_en' => $item->name_en,
                                                'name_ur' => $item->name_ur,
                                                'variants' => $item->variants->map(function ($variant) {
                                                    return [
                                                        'id' => $variant->id,
                                                        'name_en' => $variant->name_en,
                                                        'name_ur' => $variant->name_ur,
                                                        'price' => $variant->price,
                                                    ];
                                                })->values()->all(),
                                            ];
                                        @endphp
                                        <button type="button"
                                                data-item='@json($itemPayload)'
                                                @click.prevent="openVariantModal($el.dataset.item)"
                                                class="inline-flex items-center gap-2 rounded-3xl bg-[#FFB703] px-4 py-2 text-sm font-semibold text-[#0D0D0D]">+ ADD</button>
                                    @else
                                        <button type="button" @click.prevent="addSingleToCart({{ $item->id }})" class="inline-flex items-center gap-2 rounded-3xl bg-[#FFB703] px-4 py-2 text-sm font-semibold text-[#0D0D0D]">+ ADD</button>
                                    @endif
                                @else
                                    <span class="rounded-full bg-slate-900 px-4 py-2 text-sm text-slate-400">Out of Stock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="lg:col-span-3 rounded-[32px] bg-[#181818] border border-slate-800 p-10 text-center text-slate-400">No items found in this category.</div>
                @endforelse
            </div>
        </section>

        @php $dealsCategory = $categories->firstWhere('name_en', 'Deals'); @endphp
        @if($dealsCategory && $dealsCategory->items->count())
            <section id="deals-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-8">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-[#FFB703]">Deals</p>
                            <h2 class="mt-2 text-3xl font-semibold text-white">Hot Deals for Your Table</h2>
                        </div>
                        <a href="https://wa.me/923428544110?text=Hi%20Al-Shamas%20Pizza%20Hut,%20I%20would%20like%20to%20place%20an%20order." target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-full bg-[#FFB703] px-5 py-3 text-sm font-semibold text-[#0D0D0D]">Order on WhatsApp</a>
                    </div>

                    <div class="mt-8 grid gap-6 lg:grid-cols-3">
                        @foreach($dealsCategory->items as $deal)
                            <div class="rounded-[32px] bg-[#0D0D0D] border border-slate-800 p-6">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm text-slate-400">{{ $locale === 'ur' ? $deal->name_ur : $deal->name_en }}</p>
                                        <p class="mt-2 text-2xl font-semibold text-white">Rs. {{ number_format($deal->price, 0) }}</p>
                                    </div>
                                    <div class="h-16 w-16 rounded-full bg-[#FFB703]/10 flex items-center justify-center text-3xl text-[#FFB703]">🔥</div>
                                </div>

                                <div class="mt-5 flex items-center justify-between gap-3">
                                    <p class="text-sm text-slate-400">{{ $locale === 'ur' ? ($deal->description_ur ?: $deal->description_en) : ($deal->description_en ?: $deal->description_ur) }}</p>
                                    @if($deal->is_available)
                                        <button type="button" @click.prevent="addSingleToCart({{ $deal->id }})" class="inline-flex items-center gap-2 rounded-3xl bg-[#FFB703] px-4 py-2 text-sm font-semibold text-[#0D0D0D]">+ ADD</button>
                                    @else
                                        <span class="rounded-full bg-slate-900 px-4 py-2 text-sm text-slate-400">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section id="location-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-8">
                <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-[#FFB703]">Location</p>
                        <h2 class="mt-3 text-3xl font-semibold text-white">Visit or Order From Our Kitchen</h2>
                        <p class="mt-4 text-slate-300">MAIN SARGODHA ROAD, KHAI ADDA • Open daily from 11:00 AM to 12:00 AM</p>
                    </div>
                    <div class="rounded-[32px] bg-[#0D0D0D] border border-slate-800 p-8 text-center">
                        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-[#FFB703]/15 text-4xl text-[#FFB703]">📍</div>
                        <p class="mt-6 text-sm text-slate-400">Have a question or want to order directly on WhatsApp?</p>
                        <a href="https://wa.me/923428544110?text=Hi%20Al-Shamas%20Pizza%20Hut,%20I%20would%20like%20to%20place%20an%20order." target="_blank" rel="noreferrer" class="mt-6 inline-flex items-center justify-center rounded-full bg-[#FFB703] px-5 py-3 text-sm font-semibold text-[#0D0D0D]">Chat on WhatsApp</a>
                    </div>
                </div>
            </div>
        </section>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4 py-8" style="display: none;">
            <div class="w-full max-w-2xl rounded-[32px] bg-[#121212] border border-slate-800 p-6 shadow-2xl">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-white" x-text="modalItem ? (modalItem.name_en + ' (' + modalItem.name_ur + ')') : ''"></h2>
                        <p class="text-sm text-slate-400">Choose size and quantity before adding to cart.</p>
                    </div>
                    <button type="button" @click="open = false" class="rounded-full bg-slate-900 px-4 py-2 text-sm text-slate-300">Close</button>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" x-on:submit.prevent="submitVariantAdd" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="item_id" x-bind:value="modalItem ? modalItem.id : ''">
                    <input type="hidden" name="variant_id" x-bind:value="selectedVariantId">

                    <div class="grid gap-3">
                        <template x-if="modalItem">
                            <div>
                                <div class="grid gap-3 lg:grid-cols-2">
                                    <template x-for="(variant, index) in modalItem.variants" :key="variant.id">
                                        <label :class="selectedVariantId == variant.id ? 'border-[#FFB703] bg-[#FFB703]/10 text-white' : 'border-slate-800 bg-[#0D0D0D] text-slate-300'" class="w-full rounded-3xl border p-4 text-left transition cursor-pointer">
                                            <input type="radio" name="variant_id" x-model="selectedVariantId" :value="variant.id" class="sr-only">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>
                                                    <div class="font-semibold" x-text="variant.name_en + ' / ' + variant.name_ur"></div>
                                                    <div class="text-sm text-slate-400" x-text="'Rs. ' + Number(variant.price).toFixed(0)"></div>
                                                </div>
                                                <div class="text-sm uppercase tracking-[0.2em] text-slate-500" x-text="selectedVariantId == variant.id ? 'Selected' : 'Choose'"></div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-300">Quantity</label>
                            <input type="number" name="quantity" x-model.number="quantity" min="1" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                        </div>
                        <div class="flex items-end justify-end">
                            <button type="submit" class="w-full rounded-3xl bg-[#FFB703] px-6 py-3 text-sm font-semibold text-[#0D0D0D]">Add to Cart</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function menuPage() {
    return {
        open: false,
        modalItem: null,
        selectedVariantId: null,
        quantity: 1,
        csrf: @json(csrf_token()),
        addSingleToCart(itemId) {
            fetch(@json(route('cart.add')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ item_id: itemId, quantity: 1 })
            }).then(res => res.json()).then(data => {
                if (data && data.success) {
                    // update header count
                    const el = document.getElementById('cart-count');
                    if (el) el.textContent = data.cart_count;
                    this.showToast('✓ Item added to cart!', 'آئٹم کارٹ میں شامل کر دیا گیا ہے');
                    return;
                }
                throw new Error('Unable to add to cart');
            }).catch(err => {
                console.error(err);
                alert('Unable to add to cart.');
            });
        },
        submitVariantAdd() {
            if (! this.modalItem || ! this.selectedVariantId) {
                alert('Please select a variant');
                return;
            }

            fetch(@json(route('cart.add')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ item_id: this.modalItem.id, variant_id: this.selectedVariantId, quantity: this.quantity })
            }).then(res => res.json()).then(data => {
                if (data && data.success) {
                    // close modal
                    this.open = false;
                    // update header count
                    const el = document.getElementById('cart-count');
                    if (el) el.textContent = data.cart_count;
                    this.showToast('✓ Item added to cart!', 'آئٹم کارٹ میں شامل کر دیا گیا ہے');
                    return;
                }
                throw new Error('Unable to add to cart');
            }).catch(err => {
                console.error(err);
                alert('Unable to add to cart.');
            });
        },

        showToast(enText, urText = null) {
            // create toast element
            const id = 'cart-toast';
            let el = document.getElementById(id);
            if (el) {
                el.remove();
            }
            el = document.createElement('div');
            el.id = id;
            el.className = 'fixed z-50 right-6 bottom-6 max-w-xs rounded-lg bg-black/90 text-white p-3 shadow-lg';
            el.style.backdropFilter = 'saturate(180%) blur(6px)';
            el.innerHTML = `<div class="text-sm font-semibold">${enText}</div><div class="text-xs text-slate-400 mt-1">${urText || ''}</div>`;
            document.body.appendChild(el);
            setTimeout(() => { el.classList.add('opacity-0'); el.style.transition = 'opacity 300ms'; }, 2200);
            setTimeout(() => { el.remove(); }, 2600 + 300);
        },
        openVariantModal(itemJson) {
            try {
                this.modalItem = JSON.parse(itemJson);
                this.selectedVariantId = this.modalItem.variants && this.modalItem.variants.length ? this.modalItem.variants[0].id : null;
                this.quantity = 1;
                this.open = true;
            } catch (e) {
                console.error('Failed to open variant modal', e);
            }
        }
    }
}
</script>
