<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-[32px] border border-slate-800 bg-[#181818] p-8">
            <div class="mb-6">
                <p class="text-sm uppercase tracking-[0.24em] text-[#FFB703]">Deals</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Edit Deal</h1>
            </div>

            <form action="{{ route('owner.deals.update', $deal) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-300">Deal Name (EN)</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $deal->name_en) }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-300">Deal Name (UR)</label>
                        <input type="text" name="name_ur" value="{{ old('name_ur', $deal->name_ur) }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" required>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-300">Price</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $deal->price) }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" required>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-300">Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-sm text-slate-300 file:mr-4 file:rounded-full file:border-0 file:bg-[#FFB703] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#0D0D0D]">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Deal Items</label>
                    <textarea name="deal_items" rows="4" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">{{ old('deal_items', $deal->description_en ?: $deal->description_ur) }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_available" value="1" {{ $deal->is_available ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-700 bg-[#0D0D0D] text-[#FFB703]">
                    <label class="text-sm text-slate-300">Available for sale</label>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('owner.deals.index') }}" class="rounded-full bg-slate-800 px-5 py-3 text-sm font-semibold text-white">Cancel</a>
                    <button type="submit" class="rounded-full bg-[#FFB703] px-5 py-3 text-sm font-semibold text-[#0D0D0D]">Update Deal</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
