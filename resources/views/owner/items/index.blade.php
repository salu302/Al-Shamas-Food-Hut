<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-white">Menu Items</h1>
            <p class="text-slate-400">Manage the restaurant menu, availability, and details.</p>
        </div>
        <a href="{{ route('owner.items.create') }}" class="inline-flex items-center rounded-3xl bg-[#FFB703] px-5 py-3 text-sm font-semibold text-[#0D0D0D]">Add Item</a>
    </div>

    <form method="get" action="{{ route('owner.items.index') }}" class="mb-6 grid gap-4 lg:grid-cols-[1.5fr_1fr_1fr_0.75fr]">
        <input
            type="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search items..."
            class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white placeholder:text-slate-500"
        />

        <select name="category_id" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name_en }}</option>
            @endforeach
        </select>

        <select name="status" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">
            <option value="">All Status</option>
            <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
            <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Out of Stock</option>
        </select>

        <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-[#FFB703] px-5 py-3 text-sm font-semibold text-[#0D0D0D]">Filter</button>
    </form>

    <div class="space-y-4">
        @foreach($items as $item)
            <div class="rounded-[32px] bg-[#222222] border border-slate-800 p-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ $item->name_en }} / {{ $item->name_ur }}</h2>
                    <p class="text-slate-400">{{ $item->category->name_en }} · Rs. {{ number_format($item->price, 2) }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form action="{{ route('owner.items.toggleStock', $item) }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-sm text-slate-300 hover:bg-slate-800">{{ $item->is_available ? 'Set Out of Stock' : 'Set Available' }}</button>
                    </form>
                    <a href="{{ route('owner.items.edit', $item) }}" class="rounded-full bg-[#FFB703] px-4 py-2 text-sm font-semibold text-[#0D0D0D]">Edit</a>
                    <form action="{{ route('owner.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm text-white">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</x-app-layout>
