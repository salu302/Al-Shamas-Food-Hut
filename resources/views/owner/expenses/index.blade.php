<x-app-layout>
    <div x-data="{
        categoryModal: false,
        editExpense: null,
        editAction: '',
        openEdit(expense, action) {
            this.editExpense = expense;
            this.editAction = action;
        },
        closeEdit() {
            this.editExpense = null;
            this.editAction = '';
        }
    }" @keydown.escape.window="categoryModal = false; closeEdit()">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[#FFB703]">Owner Finance</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Expense Tracker</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm text-slate-200 hover:bg-slate-800">Back to dashboard</a>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year'] as $key => $label)
                <div class="rounded-[28px] border border-slate-800 bg-[#181818] p-5">
                    <p class="text-sm text-slate-400">{{ $label }}</p>
                    <p class="mt-2 text-2xl font-semibold text-white">Rs. {{ number_format($analytics[$key], 2) }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[360px_1fr]">
            <div class="rounded-[28px] border border-slate-800 bg-[#181818] p-6">
                <h2 class="text-xl font-semibold text-white">Add expense</h2>
                <form action="{{ route('owner.expenses.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label for="category" class="block text-sm text-slate-300">Category</label>
                            <button type="button" @click="categoryModal = true" class="text-xs font-semibold text-[#FFB703] hover:text-yellow-300">[ + Manage Categories ]</button>
                        </div>
                        <select id="category" name="category" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white">
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="amount" class="mb-2 block text-sm text-slate-300">Amount</label>
                        <input id="amount" name="amount" type="number" min="0.01" step="0.01" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                    <div>
                        <label for="expense_date" class="mb-2 block text-sm text-slate-300">Date</label>
                        <input id="expense_date" name="expense_date" type="date" value="{{ now()->toDateString() }}" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                    <div>
                        <label for="description" class="mb-2 block text-sm text-slate-300">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-2xl bg-[#FFB703] px-5 py-3 font-semibold text-[#0D0D0D]">Save Expense</button>
                </form>
            </div>

            <div class="rounded-[28px] border border-slate-800 bg-[#181818] p-6">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <h2 class="text-xl font-semibold text-white">Expense history</h2>
                    <form method="GET" action="{{ route('owner.expenses.index') }}" class="flex gap-2">
                        <input name="search" value="{{ $search }}" placeholder="Search expenses" class="rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-2 text-sm text-white" />
                        <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-2 text-sm text-slate-200">Search</button>
                    </form>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-800 text-slate-400">
                            <tr><th class="px-3 py-3">Date</th><th class="px-3 py-3">Category</th><th class="px-3 py-3">Description</th><th class="px-3 py-3 text-right">Amount</th><th class="px-3 py-3"></th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 text-slate-300">
                            @forelse($expenses as $expense)
                                <tr>
                                    <td class="px-3 py-3 whitespace-nowrap">{{ $expense->expense_date->format('d M Y') }}</td>
                                    <td class="px-3 py-3">{{ $expense->category }}</td>
                                    <td class="px-3 py-3">{{ $expense->description ?: '—' }}</td>
                                    <td class="px-3 py-3 text-right whitespace-nowrap">Rs. {{ number_format($expense->amount, 2) }}</td>
                                    <td class="px-3 py-3 text-right whitespace-nowrap">
                                        <button
                                            type="button"
                                            @click="openEdit({ id: {{ $expense->id }}, category_id: {{ $expense->category_id ?: 'null' }}, category: @js($expense->category), amount: @js($expense->amount), expense_date: @js($expense->expense_date->format('Y-m-d')), description: @js($expense->description) }, '{{ route('owner.expenses.update', $expense->id) }}')"
                                            class="mr-2 rounded-full bg-[#FFB703] px-3 py-1 text-xs font-semibold text-[#0D0D0D] hover:bg-yellow-300"
                                        >[ Edit ]</button>
                                        <form action="{{ route('owner.expenses.destroy', $expense) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="submit" class="rounded-full bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-500">[ Delete ]</button></form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-3 py-8 text-center text-slate-500">No expenses found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="categoryModal" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
            <div @click.outside="categoryModal = false" class="w-full max-w-lg rounded-[28px] border border-slate-800 bg-[#1F1F1F] p-6 shadow-2xl">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white">Manage Expense Categories</h2>
                    <button type="button" @click="categoryModal = false" class="text-xl text-slate-400 hover:text-white">×</button>
                </div>

                <form action="{{ route('owner.expense-categories.store') }}" method="POST" class="mb-6 flex gap-2">
                    @csrf
                    <input name="name" required placeholder="New category name" class="min-w-0 flex-1 rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-2 text-white placeholder:text-slate-500" />
                    <button type="submit" class="rounded-2xl bg-[#FFB703] px-4 py-2 text-sm font-semibold text-[#0D0D0D]">Add</button>
                </form>

                <div class="space-y-3">
                    @foreach($categories as $category)
                        <div class="flex items-center gap-2 rounded-2xl border border-slate-800 bg-[#0D0D0D] p-3">
                            <form action="{{ route('owner.expense-categories.update', $category->id) }}" method="POST" class="flex min-w-0 flex-1 gap-2">
                                @csrf
                                @method('PUT')
                                <input name="name" value="{{ $category->name }}" required class="min-w-0 flex-1 rounded-xl border border-slate-700 bg-[#181818] px-3 py-2 text-sm text-white" />
                                <button type="submit" class="rounded-xl bg-slate-800 px-3 py-2 text-xs text-slate-200">Save</button>
                            </form>
                            <form action="{{ route('owner.expense-categories.destroy', $category->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-xl bg-red-600 px-3 py-2 text-xs font-semibold text-white">Delete</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div x-show="editExpense" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
            <div @click.outside="closeEdit()" class="w-full max-w-md rounded-[28px] border border-slate-800 bg-[#1F1F1F] p-6 shadow-2xl">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white">Edit Expense</h2>
                    <button type="button" @click="closeEdit()" class="text-xl text-slate-400 hover:text-white">×</button>
                </div>
                <form :action="editAction" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="edit_category" class="mb-2 block text-sm text-slate-300">Category</label>
                        <select id="edit_category" name="category" required class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" x-bind:selected="editExpense && editExpense.category_id === {{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="edit_amount" class="mb-2 block text-sm text-slate-300">Amount</label>
                        <input id="edit_amount" name="amount" type="number" min="0.01" step="0.01" required x-model="editExpense.amount" class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                    <div>
                        <label for="edit_date" class="mb-2 block text-sm text-slate-300">Date</label>
                        <input id="edit_date" name="expense_date" type="date" required x-model="editExpense.expense_date" class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                    <div>
                        <label for="edit_description" class="mb-2 block text-sm text-slate-300">Description</label>
                        <textarea id="edit_description" name="description" rows="3" x-model="editExpense.description" class="w-full rounded-2xl border border-slate-700 bg-[#0D0D0D] px-4 py-3 text-white"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-2xl bg-[#FFB703] px-5 py-3 font-semibold text-[#0D0D0D]">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
