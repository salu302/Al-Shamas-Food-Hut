<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-white">Manage Owners</h1>
                <p class="text-slate-400">Add new co-owners and keep the ownership list updated.</p>
            </div>

            <button
                type="button"
                x-data
                @click="$dispatch('open-owner-modal')"
                class="inline-flex items-center rounded-3xl bg-[#FFB703] px-5 py-3 text-sm font-semibold text-[#0D0D0D]"
            >
                + Add New Owner
            </button>
        </div>

        <div x-data="{
            show: false,
            open() { this.show = true; },
            close() { this.show = false; }
        }"
             @open-owner-modal.window="open()"
             @keydown.escape.window="close()"
             class="relative"
        >
            <div x-show="show" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
                <div class="w-full max-w-md rounded-[28px] border border-slate-800 bg-[#1F1F1F] p-6 shadow-2xl">
                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white">Add New Owner</h2>
                        <button type="button" @click="close()" class="text-xl text-slate-400 hover:text-white">×</button>
                    </div>

                    <form action="{{ route('owner.manage_owners.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white placeholder:text-slate-500" placeholder="Owner name" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white placeholder:text-slate-500" placeholder="03001234567" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white placeholder:text-slate-500" placeholder="owner@example.com" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Password</label>
                            <input type="password" name="password" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white placeholder:text-slate-500" placeholder="Minimum 8 characters" />
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="close()" class="rounded-full bg-slate-800 px-4 py-2 text-sm text-slate-300">Cancel</button>
                            <button type="submit" class="rounded-full bg-[#FFB703] px-4 py-2 text-sm font-semibold text-[#0D0D0D]">Save Owner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-[32px] border border-slate-800 bg-[#181818] shadow-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-slate-300">
                    <thead class="bg-[#0D0D0D] text-xs uppercase tracking-[0.08em] text-slate-400">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($owners as $owner)
                            <tr class="border-t border-slate-800">
                                <td class="px-6 py-4 font-medium text-white">{{ $owner->name }}</td>
                                <td class="px-6 py-4">{{ $owner->email }}</td>
                                <td class="px-6 py-4 uppercase text-[#FFB703]">{{ $owner->role }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('owner.manage_owners.destroy', $owner->id) }}" method="POST" onsubmit="return confirm('Delete this owner?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">[ Delete ]</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400">No co-owners found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
