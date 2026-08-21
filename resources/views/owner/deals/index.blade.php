<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-white">Deal Management</h1>
                <p class="text-slate-400">Create and manage combo deals for the public menu.</p>
            </div>
            <a href="{{ route('owner.deals.create') }}" class="inline-flex items-center rounded-3xl bg-[#FFB703] px-5 py-3 text-sm font-semibold text-[#0D0D0D]">Add Deal</a>
        </div>

        <div class="space-y-4">
            @forelse($deals as $deal)
                <div class="rounded-[32px] border border-slate-800 bg-[#181818] p-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ $deal->name_en }} / {{ $deal->name_ur }}</h2>
                            <p class="mt-2 text-slate-400">Rs. {{ number_format($deal->price, 2) }} · {{ $deal->is_available ? 'Available' : 'Unavailable' }}</p>
                            <p class="mt-2 text-sm text-slate-300">{{ $deal->description_en ?: $deal->description_ur }}</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('owner.deals.edit', $deal) }}" class="rounded-full bg-[#FFB703] px-4 py-2 text-sm font-semibold text-[#0D0D0D]">Edit</a>
                            <form action="{{ route('owner.deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('Delete this deal?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm text-white">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-[32px] border border-dashed border-slate-700 bg-[#0D0D0D] p-8 text-center text-slate-400">
                    No deals added yet.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
