<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-8">
        <h1 class="text-3xl font-semibold text-white">Edit Menu Item</h1>
        @php
            $variantData = old('variant_name_en')
                ? array_map(function ($nameEn, $index) {
                    return [
                        'name_en' => $nameEn,
                        'name_ur' => old('variant_name_ur')[$index] ?? '',
                        'price' => old('variant_price')[$index] ?? '',
                        'is_available' => isset(old('variant_is_available')[$index]),
                    ];
                }, old('variant_name_en'), array_keys(old('variant_name_en')))
                : $item->variants->map(function ($variant) {
                    return [
                        'name_en' => $variant->name_en,
                        'name_ur' => $variant->name_ur,
                        'price' => number_format($variant->price, 2, '.', ''),
                        'is_available' => $variant->is_available,
                    ];
                })->values();
        @endphp
        <form action="{{ route('owner.items.update', $item) }}" method="POST" enctype="multipart/form-data" x-data='{ variantMode: @json(old('variant_mode', $item->variants->count() ? 'multiple' : 'single')), variants: @json($variantData) }' class="mt-8 space-y-6">
            @csrf
            @method('PUT')
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">English Name</label>
                    <input name="name_en" value="{{ old('name_en', $item->name_en) }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Urdu Name</label>
                    <input name="name_ur" value="{{ old('name_ur', $item->name_ur) }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                </div>
            </div>
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Category</label>
                    <select name="category_id" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $item->category_id === $category->id ? 'selected' : '' }}>{{ $category->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Price</label>
                    <input name="price" value="{{ old('price', $item->price) }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                </div>
            </div>
            <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-4">
                <div class="flex items-center gap-4 mb-4">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-300">
                        <input type="radio" name="variant_mode" value="single" x-model="variantMode" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded">
                        Single Price
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-slate-300">
                        <input type="radio" name="variant_mode" value="multiple" x-model="variantMode" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded">
                        Multiple Variants
                    </label>
                </div>

                <template x-if="variantMode === 'single'">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-300">Price</label>
                        <input name="price" value="{{ old('price', $item->price) }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                </template>

                <template x-if="variantMode === 'multiple'">
                    <div class="space-y-4">
                        <template x-for="(variant, index) in variants" :key="index">
                            <div class="grid gap-4 lg:grid-cols-4 items-end">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-300">English Label</label>
                                    <input type="text" x-model="variant.name_en" name="variant_name_en[]" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-300">Urdu Label</label>
                                    <input type="text" x-model="variant.name_ur" name="variant_name_ur[]" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-300">Price</label>
                                    <input type="text" x-model="variant.price" name="variant_price[]" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                                </div>
                                <div class="flex items-center gap-3">
                                    <input type="hidden" :name="`variant_is_available[${index}]`" value="0">
                                    <label class="inline-flex items-center gap-2 text-sm text-slate-300">
                                        <input type="checkbox" :name="`variant_is_available[${index}]`" x-model="variant.is_available" value="1" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded" />
                                        Active
                                    </label>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Description (English)</label>
                    <textarea name="description_en" rows="3" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">{{ old('description_en', $item->description_en) }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Description (Urdu)</label>
                    <textarea name="description_ur" rows="3" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">{{ old('description_ur', $item->description_ur) }}</textarea>
                </div>
            </div>
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Image</label>
                    <input type="file" name="image" class="w-full text-sm text-slate-400" />
                    @if($item->image)
                        <p class="mt-2 text-sm text-slate-400">Current: {{ $item->image }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_available" value="1" id="available" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded" {{ $item->is_available ? 'checked' : '' }} />
                    <label for="available" class="text-sm text-slate-300">Available</label>
                </div>
            </div>
            <button type="submit" class="rounded-3xl bg-[#FFB703] px-6 py-3 font-semibold text-[#0D0D0D]">Update Item</button>
        </form>
    </div>
</x-app-layout>
