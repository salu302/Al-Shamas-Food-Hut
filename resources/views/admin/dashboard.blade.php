<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid gap-6 lg:grid-cols-4">
            <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-6">
                <p class="text-sm text-slate-400">Total Orders</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $totalOrders }}</p>
            </div>
            <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-6">
                <p class="text-sm text-slate-400">Total Revenue</p>
                <p class="mt-3 text-3xl font-semibold text-white">Rs. {{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-6">
                <p class="text-sm text-slate-400">Restaurant Owners</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $owners }}</p>
            </div>
            <div class="rounded-[32px] bg-[#181818] border border-slate-800 p-6">
                <p class="text-sm text-slate-400">Customers</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $customers }}</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach([
                ['label' => 'Today', 'revenue' => $todayRevenue, 'expenses' => $todayExpenses, 'profit' => $todayProfit],
                ['label' => 'This Month', 'revenue' => $monthRevenue, 'expenses' => $monthExpenses, 'profit' => $monthProfit],
                ['label' => 'This Year', 'revenue' => $yearRevenue, 'expenses' => $yearExpenses, 'profit' => $yearProfit],
            ] as $metric)
                <div class="rounded-[32px] border border-slate-800 bg-[#181818] p-6">
                    <p class="text-sm uppercase tracking-[0.18em] text-[#FFB703]">{{ $metric['label'] }}</p>
                    <div class="mt-5 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-slate-400">Gross Revenue</p>
                            <p class="mt-1 text-xl font-semibold text-white">Rs. {{ number_format($metric['revenue'], 2) }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Expenses</p>
                            <p class="mt-1 text-xl font-semibold text-red-300">Rs. {{ number_format($metric['expenses'], 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-5 border-t border-slate-800 pt-4">
                        <p class="text-sm text-slate-400">Net Profit</p>
                    <p class="mt-1 text-2xl font-semibold {{ $metric['profit'] >= 0 ? 'text-emerald-300' : 'text-red-300' }}">Rs. {{ number_format($metric['profit'], 2) }}</p>
                    </div>
                </div>
                @if($metric['label'] === 'Today')
                    <div class="p-5 bg-[#121212] border border-gray-800 rounded-xl">
                        <span class="text-xs font-bold tracking-wider text-yellow-500 uppercase">THIS WEEK</span>
                        <div class="flex justify-between mt-4">
                            <div>
                                <p class="text-xs text-gray-400">Gross Revenue</p>
                                <p class="text-lg font-bold text-white">Rs. {{ number_format($weekRevenue, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Expenses</p>
                                <p class="text-lg font-bold text-red-400">Rs. {{ number_format($weekExpenses, 2) }}</p>
                            </div>
                        </div>
                        <div class="pt-3 mt-3 border-t border-gray-800">
                            <p class="text-xs text-gray-400">Net Profit</p>
                            <p class="text-xl font-bold text-emerald-400">Rs. {{ number_format($weekNetProfit, 2) }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if(Auth::check() && Auth::user()->role === 'owner')
            <div class="mt-8 rounded-[32px] border border-slate-800 bg-[#181818] p-6">
                <div class="mb-5">
                    <p class="text-sm uppercase tracking-[0.24em] text-[#FFB703]">WhatsApp / Manual Sales</p>
                    <h2 class="mt-2 text-2xl font-semibold text-white">Record a quick sale</h2>
                </div>
                <form action="{{ route('owner.quick-sale.store') }}" method="POST" class="grid gap-4 md:grid-cols-[1fr_1fr_auto] md:items-end">
                    @csrf
                    <div>
                        <label for="customer_name" class="mb-2 block text-sm font-semibold text-slate-300">Customer name</label>
                        <input id="customer_name" name="customer_name" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                    <div>
                        <label for="total_amount" class="mb-2 block text-sm font-semibold text-slate-300">Total amount</label>
                        <input id="total_amount" name="total_amount" type="number" min="0.01" step="0.01" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                    <button type="submit" class="rounded-2xl bg-[#FFB703] px-5 py-3 font-semibold text-[#0D0D0D]">Save WhatsApp Sale</button>
                </form>
            </div>
        @endif

        @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']))
            <div class="mt-8 rounded-[32px] border border-red-500/40 bg-red-500/5 p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.24em] text-red-300">System</p>
                        <h2 class="mt-2 text-2xl font-semibold text-white">Reset Store Data</h2>
                    </div>
                    <div x-data="{ open: false }">
                        <button type="button" @click="open = true" class="rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-500">Reset System Data</button>

                        <div x-show="open" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4" style="display: none;">
                            <div class="w-full max-w-md rounded-[32px] border border-red-500/40 bg-[#121212] p-6 shadow-2xl">
                                <h3 class="text-2xl font-semibold text-white">Confirm Reset</h3>
                                <p class="mt-3 text-slate-300">Are you sure you want to erase all store metrics?</p>
                                <form action="{{ route('admin.reset-dashboard') }}" method="POST" class="mt-6 flex justify-end gap-3">
                                    @csrf
                                    <button type="button" @click="open = false" class="rounded-full bg-slate-800 px-4 py-2 text-sm text-slate-200">Cancel</button>
                                    <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white">Yes, Reset</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-10 rounded-[32px] bg-[#181818] border border-slate-800 p-6">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.24em] text-[#FFB703]">Reviews</p>
                    <h2 class="mt-2 text-2xl font-semibold text-white">Complaints & Suggestions</h2>
                </div>
                <span class="rounded-full bg-[#FFB703]/15 px-3 py-1 text-sm font-semibold text-[#FFB703]">{{ $complaints->count() }} total</span>
            </div>

            @if($complaints->isEmpty())
                <div class="rounded-3xl border border-dashed border-slate-700 bg-[#0D0D0D] p-6 text-slate-400">
                    No complaints or suggestions submitted yet.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($complaints as $complaint)
                        <div class="rounded-3xl border border-slate-800 bg-[#0D0D0D] p-5">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-lg font-semibold text-white">{{ $complaint->subject }}</p>
                                    <p class="text-sm text-slate-400">{{ $complaint->name }} • {{ $complaint->phone }}</p>
                                </div>
                                <span class="inline-flex rounded-full bg-slate-800 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-300">
                                    {{ $complaint->status ?? 'new' }}
                                </span>
                            </div>
                            <p class="mt-4 text-slate-300">{{ $complaint->message }}</p>
                            <p class="mt-3 text-xs text-slate-500">Submitted: {{ $complaint->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
