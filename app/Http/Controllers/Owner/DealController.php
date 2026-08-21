<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DealController extends Controller
{
    public function index()
    {
        $deals = Item::with('category')
            ->whereHas('category', fn ($query) => $query->where('name_en', 'Deals'))
            ->latest()
            ->get();

        return view('owner.deals.index', compact('deals'));
    }

    public function create()
    {
        return view('owner.deals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],
            'deal_items' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $category = Category::where('name_en', 'Deals')->first();

        $data = [
            'category_id' => $category?->id ?? Category::firstOrCreate(['name_en' => 'Deals', 'name_ur' => 'ڈیلز', 'status' => true])->id,
            'name_en' => $request->name_en,
            'name_ur' => $request->name_ur,
            'description_en' => $request->filled('deal_items') ? $request->deal_items : $request->description_en,
            'description_ur' => $request->filled('deal_items') ? $request->deal_items : $request->description_ur,
            'price' => $request->price,
            'is_available' => $request->boolean('is_available', true),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('owner.deals.index')->with('success', 'Deal added successfully.');
    }

    public function edit(Item $deal)
    {
        return view('owner.deals.edit', compact('deal'));
    }

    public function update(Request $request, Item $deal)
    {
        $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],
            'deal_items' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name_en' => $request->name_en,
            'name_ur' => $request->name_ur,
            'description_en' => $request->filled('deal_items') ? $request->deal_items : $request->description_en,
            'description_ur' => $request->filled('deal_items') ? $request->deal_items : $request->description_ur,
            'price' => $request->price,
            'is_available' => $request->boolean('is_available', true),
        ];

        if ($request->hasFile('image')) {
            if ($deal->image) {
                Storage::disk('public')->delete($deal->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $deal->update($data);

        return redirect()->route('owner.deals.index')->with('success', 'Deal updated successfully.');
    }

    public function destroy(Item $deal)
    {
        if ($deal->image) {
            Storage::disk('public')->delete($deal->image);
        }

        $deal->delete();

        return redirect()->route('owner.deals.index')->with('success', 'Deal deleted successfully.');
    }
}
