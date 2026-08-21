<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('name_en', 'like', '%'.$request->search.'%')
                    ->orWhere('name_ur', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('is_available', true);
            } elseif ($request->status === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        $items = $query->with('variants')->latest()->paginate(12)->withQueryString();
        $categories = Category::where('status', true)->get();

        return view('owner.items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();

        return view('owner.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $variantMode = $request->input('variant_mode', 'single');

        $rules = [
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
            'variant_mode' => ['required', 'in:single,multiple'],
        ];

        if ($variantMode === 'single') {
            $rules['price'] = ['required', 'numeric', 'min:0'];
        } else {
            $rules['price'] = ['nullable', 'numeric', 'min:0'];
            $rules['variant_name_en.*'] = ['required', 'string', 'max:255'];
            $rules['variant_name_ur.*'] = ['required', 'string', 'max:255'];
            $rules['variant_price.*'] = ['required', 'numeric', 'min:0'];
        }

        $request->validate($rules);

        $data = $request->only(['name_en', 'name_ur', 'description_en', 'description_ur', 'category_id']);
        $data['is_available'] = $request->boolean('is_available');
        $data['price'] = $variantMode === 'single' ? $request->input('price', 0) : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        if ($variantMode === 'multiple') {
            $variantNames = $request->input('variant_name_en', []);
            $variantNamesUr = $request->input('variant_name_ur', []);
            $variantPrices = $request->input('variant_price', []);
            $variantAvailable = $request->input('variant_is_available', []);

            foreach ($variantNames as $index => $nameEn) {
                if (trim($nameEn) === '') {
                    continue;
                }

                ItemVariant::create([
                    'item_id' => $item->id,
                    'name_en' => $nameEn,
                    'name_ur' => $variantNamesUr[$index] ?? $nameEn,
                    'price' => $variantPrices[$index] ?? 0,
                    'is_available' => isset($variantAvailable[$index]) ? true : false,
                ]);
            }
        }

        return redirect()->route('owner.items.index')->with('success', 'Menu item added.');
    }

    public function edit(Item $item)
    {
        $categories = Category::where('status', true)->get();

        return view('owner.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $variantMode = $request->input('variant_mode', 'single');

        $rules = [
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
            'variant_mode' => ['required', 'in:single,multiple'],
        ];

        if ($variantMode === 'single') {
            $rules['price'] = ['required', 'numeric', 'min:0'];
        } else {
            $rules['price'] = ['nullable', 'numeric', 'min:0'];
            $rules['variant_name_en.*'] = ['required', 'string', 'max:255'];
            $rules['variant_name_ur.*'] = ['required', 'string', 'max:255'];
            $rules['variant_price.*'] = ['required', 'numeric', 'min:0'];
        }

        $request->validate($rules);

        $data = $request->only(['name_en', 'name_ur', 'description_en', 'description_ur', 'category_id']);
        $data['is_available'] = $request->boolean('is_available');
        $data['price'] = $variantMode === 'single' ? $request->input('price', 0) : 0;

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        $item->variants()->delete();

        if ($variantMode === 'multiple') {
            $variantNames = $request->input('variant_name_en', []);
            $variantNamesUr = $request->input('variant_name_ur', []);
            $variantPrices = $request->input('variant_price', []);
            $variantAvailable = $request->input('variant_is_available', []);

            foreach ($variantNames as $index => $nameEn) {
                if (trim($nameEn) === '') {
                    continue;
                }

                ItemVariant::create([
                    'item_id' => $item->id,
                    'name_en' => $nameEn,
                    'name_ur' => $variantNamesUr[$index] ?? $nameEn,
                    'price' => $variantPrices[$index] ?? 0,
                    'is_available' => isset($variantAvailable[$index]) ? true : false,
                ]);
            }
        }

        return redirect()->route('owner.items.index')->with('success', 'Menu item updated.');
    }

    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return back()->with('success', 'Item deleted.');
    }

    public function toggleAvailability(Item $item)
    {
        $item->update(['is_available' => ! $item->is_available]);

        return back()->with('success', 'Item availability updated.');
    }

    public function toggleStock(Item $item)
    {
        $item->update(['is_available' => ! $item->is_available]);

        return back()->with('success', 'Stock status updated.');
    }
}
