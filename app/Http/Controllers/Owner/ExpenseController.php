<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $today = now()->toDateString();

        $expenses = Expense::query()
            ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->latest('expense_date')
            ->latest()
            ->get();

        $categories = ExpenseCategory::orderBy('name')->get();

        $weekStart = now()->startOfWeek()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $yearStart = now()->startOfYear()->toDateString();

        $analytics = [
            'today' => Expense::whereDate('expense_date', $today)->sum('amount'),
            'week' => Expense::whereBetween('expense_date', [$weekStart, $today])->sum('amount'),
            'month' => Expense::whereBetween('expense_date', [$monthStart, $today])->sum('amount'),
            'year' => Expense::whereBetween('expense_date', [$yearStart, $today])->sum('amount'),
        ];

        return view('owner.expenses.index', compact('expenses', 'analytics', 'search', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string'],
            'expense_date' => ['required', 'date'],
        ]);

        $category = is_numeric($validated['category'])
            ? ExpenseCategory::findOrFail($validated['category'])
            : ExpenseCategory::firstOrCreate(['name' => $validated['category']]);
        $validated['category_id'] = $category->id;
        $validated['category'] = $category->name;

        Expense::create($validated);

        return back()->with('success', 'Expense recorded successfully.');
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $validated = $request->validate([
            'category' => ['required'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string'],
            'expense_date' => ['required', 'date'],
        ]);

        $category = is_numeric($validated['category'])
            ? ExpenseCategory::findOrFail($validated['category'])
            : ExpenseCategory::firstOrCreate(['name' => $validated['category']]);
        $expense->update([
            'category_id' => $category->id,
            'category' => $category->name,
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'expense_date' => $validated['expense_date'],
        ]);

        return back()->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Expense deleted successfully.');
    }
}