<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-white">Manage Categories</h1>
                <p class="text-slate-400">Create, rename, and remove categories for your restaurant menu.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-[32px] bg-[#222222] border border-slate-800 p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Add Category</h2>
                <form action="{{ route('owner.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-300">English Name</label>
                        <input name="name_en" value="{{ old('name_en') }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-300">Urdu Name</label>
                        <input name="name_ur" value="{{ old('name_ur') }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-300">Icon / Image</label>
                        <input type="file" name="image" class="w-full text-sm text-slate-400" />
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="status" value="1" id="status" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded" checked />
                        <label for="status" class="text-sm text-slate-300">Active</label>
                    </div>
                    <button type="submit" class="rounded-3xl bg-[#FFB703] px-6 py-3 font-semibold text-[#0D0D0D]">Save Category</button>
                </form>
            </div>

            <div class="rounded-[32px] bg-[#222222] border border-slate-800 p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Category List</h2>
                <div class="space-y-4">
                    @foreach($categories as $category)
                        <div x-data="{ editing: false }" class="rounded-3xl border border-slate-800 bg-[#181818] p-4">
                            <div x-show="! editing" class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-white">{{ $category->name_en }} / {{ $category->name_ur }}</p>
                                    <p class="text-sm text-slate-400">Status: {{ $category->status ? 'Active' : 'Inactive' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="editing = true" class="rounded-full bg-[#FFB703] px-4 py-2 text-sm font-semibold text-[#0D0D0D]">Edit</button>
                                    <form action="{{ route('owner.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm text-white">Delete</button>
                                    </form>
                                </div>
                            </div>

                            <form x-show="editing" action="{{ route('owner.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="grid gap-4 lg:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-slate-300">English Name</label>
                                        <input name="name_en" value="{{ old('name_en', $category->name_en) }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                                    </div>
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-slate-300">Urdu Name</label>
                                        <input name="name_ur" value="{{ old('name_ur', $category->name_ur) }}" class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                                    </div>
                                </div>
                                <div class="grid gap-4 lg:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-slate-300">Icon / Image</label>
                                        <input type="file" name="image" class="w-full text-sm text-slate-400" />
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="status" value="1" id="status-{{ $category->id }}" class="h-4 w-4 text-[#FFB703] bg-slate-900 rounded" {{ $category->status ? 'checked' : '' }} />
                                        <label for="status-{{ $category->id }}" class="text-sm text-slate-300">Active</label>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" class="rounded-3xl bg-[#FFB703] px-5 py-2 text-sm font-semibold text-[#0D0D0D]">Save</button>
                                    <button type="button" @click="editing = false" class="rounded-3xl bg-slate-900 px-5 py-2 text-sm text-slate-300">Cancel</button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
