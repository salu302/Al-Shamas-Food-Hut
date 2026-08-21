<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:expense_categories,name'],
        ]);

        ExpenseCategory::create($validated);

        return back()->with('success', 'Expense category added successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:expense_categories,name,' . $category->id],
        ]);

        $category->update($validated);
        Expense::where('category_id', $category->id)->update(['category' => $category->name]);

        return back()->with('success', 'Expense category updated successfully.');
    }

    public function destroy($id)
    {
        $category = ExpenseCategory::findOrFail($id);

        if ($category->expenses()->exists()) {
            return back()->withErrors(['category' => 'Categories used by expenses cannot be deleted.']);
        }

        $category->delete();

        return back()->with('success', 'Expense category deleted successfully.');
    }
}
