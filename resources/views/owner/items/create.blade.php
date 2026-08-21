<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-8">
        <h1 class="text-3xl font-semibold text-white">Add Menu Item</h1>
        <form action="{{ route('owner.items.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">English Name</label>
                    <input name="name_en" value="{{ old('name_en') }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Urdu Name</label>
                    <input name="name_ur" value="{{ old('name_ur') }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                </div>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-300">Category</label>
                <select name="category_id" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                    @endforeach
                </select>
            </div>
            <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-4">
                <div class="flex items-center gap-4 mb-4">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-300">
                        <input type="radio" name="variant_mode" value="single" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded" {{ old('variant_mode', 'single') === 'single' ? 'checked' : '' }}>
                        Single Price
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-slate-300">
                        <input type="radio" name="variant_mode" value="multiple" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded" {{ old('variant_mode') === 'multiple' ? 'checked' : '' }}>
                        Multiple Variants
                    </label>
                </div>

                <div id="single-price-section" class="{{ old('variant_mode', 'single') === 'multiple' ? 'hidden' : '' }}">
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Price</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                </div>

                <div id="variants-section" class="{{ old('variant_mode') === 'multiple' ? '' : 'hidden' }}">
                    <div id="variant-rows" class="space-y-4"></div>
                    <button type="button" id="add-variant-row" class="mt-4 rounded-2xl border border-[#FFB703] px-4 py-2 text-sm font-semibold text-[#FFB703]">
                        + Add Variant Row
                    </button>
                </div>
            </div>
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Description (English)</label>
                    <textarea name="description_en" rows="3" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">{{ old('description_en') }}</textarea>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Description (Urdu)</label>
                    <textarea name="description_ur" rows="3" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">{{ old('description_ur') }}</textarea>
                </div>
            </div>
            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Image</label>
                    <input type="file" name="image" class="w-full text-sm text-slate-400" />
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_available" value="1" id="available" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded" />
                    <label for="available" class="text-sm text-slate-300">Available</label>
                </div>
            </div>
            <button type="submit" class="rounded-3xl bg-[#FFB703] px-6 py-3 font-semibold text-[#0D0D0D]">Save Item</button>
        </form>
        <script>
            const defaultVariants = [
                { name_en: 'Small', name_ur: 'سمال', price: '', is_available: true },
                { name_en: 'Medium', name_ur: 'میڈیم', price: '', is_available: true },
                { name_en: 'Large', name_ur: 'لارج', price: '', is_available: true },
                { name_en: 'XL', name_ur: 'ایکسٹرا لارج', price: '', is_available: true },
            ];
            const oldVariantNames = @json(old('variant_name_en', []));
            const oldVariantNamesUr = @json(old('variant_name_ur', []));
            const oldVariantPrices = @json(old('variant_price', []));
            const oldVariantAvailability = @json(old('variant_is_available', []));
            const variantRows = document.getElementById('variant-rows');
            let variants = oldVariantNames.length
                ? oldVariantNames.map((name, index) => ({
                    name_en: name,
                    name_ur: oldVariantNamesUr[index] || '',
                    price: oldVariantPrices[index] || '',
                    is_available: Object.prototype.hasOwnProperty.call(oldVariantAvailability, index),
                }))
                : defaultVariants;

            const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (character) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            })[character]);

            const renderVariantRows = () => {
                variantRows.innerHTML = variants.map((variant, index) =>
                    '<div class="grid gap-4 lg:grid-cols-4 items-end">' +
                        '<div><label class="mb-2 block text-sm font-semibold text-slate-300">English Label</label>' +
                        '<input type="text" name="variant_name_en[' + index + ']" value="' + escapeHtml(variant.name_en) + '" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" /></div>' +
                        '<div><label class="mb-2 block text-sm font-semibold text-slate-300">Urdu Label</label>' +
                        '<input type="text" name="variant_name_ur[' + index + ']" value="' + escapeHtml(variant.name_ur) + '" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" /></div>' +
                        '<div><label class="mb-2 block text-sm font-semibold text-slate-300">Price</label>' +
                        '<input type="number" name="variant_price[' + index + ']" value="' + escapeHtml(variant.price) + '" min="0" step="0.01" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" /></div>' +
                        '<label class="flex items-center gap-2 pb-3 text-sm text-slate-300">' +
                        '<input type="checkbox" name="variant_is_available[' + index + ']" value="1" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded" ' + (variant.is_available ? 'checked' : '') + ' /> Active</label>' +
                    '</div>'
                ).join('');
            };

            const captureVariantRows = () => {
                variants = Array.from(variantRows.children).map((row) => ({
                    name_en: row.querySelector('[name^="variant_name_en"]').value,
                    name_ur: row.querySelector('[name^="variant_name_ur"]').value,
                    price: row.querySelector('[name^="variant_price"]').value,
                    is_available: row.querySelector('[name^="variant_is_available"]').checked,
                }));
            };

            document.querySelectorAll('input[name="variant_mode"]').forEach((radio) => {
                radio.addEventListener('change', function () {
                    if (this.value === 'multiple') {
                        document.getElementById('single-price-section').classList.add('hidden');
                        document.getElementById('variants-section').classList.remove('hidden');
                    } else {
                        document.getElementById('variants-section').classList.add('hidden');
                        document.getElementById('single-price-section').classList.remove('hidden');
                    }
                });
            });

            document.getElementById('add-variant-row').addEventListener('click', () => {
                captureVariantRows();
                variants.push({ name_en: '', name_ur: '', price: '', is_available: true });
                renderVariantRows();
            });

            renderVariantRows();
        </script>
    </div>
</x-app-layout>
