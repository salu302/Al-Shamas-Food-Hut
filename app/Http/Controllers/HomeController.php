<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemVariant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $locale = session('locale', config('app.locale'));
        $activeCategory = $request->query('category');
        $categories = Category::where('status', true)
            ->has('items')
            ->with(['items' => function ($query) {
                $query->with('variants')->where('is_available', true);
            }])
            ->get();

        $selectedCategory = $categories->isNotEmpty() ? (
            $activeCategory ? $categories->firstWhere('name_en', $activeCategory) ?? $categories->first() : $categories->first()
        ) : null;

        $cart = session('cart', []);
        $cartItems = collect($cart)->map(function ($line) {
            $item = Item::find($line['item_id']);

            if (! $item) {
                return null;
            }

            return array_merge($line, [
                'item' => $item,
                'subtotal' => $line['unit_price'] * $line['quantity'],
            ]);
        })->filter()->values();

        $subtotal = $cartItems->sum('subtotal');

        return view('home', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'cartItems' => $cartItems,
            'cartTotal' => $subtotal,
            'locale' => $locale,
        ]);
    }

    public function switchLocale(string $locale)
    {
        if (! in_array($locale, ['en', 'ur'])) {
            $locale = 'en';
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return back();
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'variant_id' => ['nullable', 'exists:item_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $item = Item::findOrFail($request->input('item_id'));
        $quantity = $request->input('quantity', 1);
        $variantId = $request->input('variant_id');

        if ($variantId) {
            $variant = ItemVariant::findOrFail($variantId);
            $variantName = $variant->name_en;
            $unitPrice = $variant->price;
            $cartKey = "item_{$item->id}_variant_{$variant->id}";
        } else {
            $variantName = null;
            $unitPrice = $item->price;
            $cartKey = "item_{$item->id}";
        }

        $cart = session('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = max(1, $cart[$cartKey]['quantity'] + $quantity);
        } else {
            $cart[$cartKey] = [
                'item_id' => $item->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'variant_id' => $variantId,
                'variant_name' => $variantName,
            ];
        }

        session(['cart' => $cart]);

        if ($request->ajax() || $request->wantsJson()) {
            $count = collect($cart)->sum('quantity');
            $total = collect($cart)->sum(function ($line) {
                return ($line['unit_price'] ?? 0) * ($line['quantity'] ?? 0);
            });

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart.',
                'cart_count' => $count,
                'cart_total' => $total,
            ]);
        }

        return back()->with('success', 'Item added to cart.');
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'cart_key' => ['required', 'string'],
        ]);

        $cart = session('cart', []);
        unset($cart[$request->input('cart_key')]);
        session(['cart' => $cart]);

        if ($request->ajax() || $request->wantsJson()) {
            $count = collect($cart)->sum('quantity');
            $total = collect($cart)->sum(function ($line) {
                return ($line['unit_price'] ?? 0) * ($line['quantity'] ?? 0);
            });

            return response()->json([
                'success' => true,
                'cart_count' => $count,
                'cart_total' => $total,
            ]);
        }

        return back();
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_key' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session('cart', []);

        if (isset($cart[$request->input('cart_key')])) {
            $cart[$request->input('cart_key')]['quantity'] = $request->input('quantity');
            session(['cart' => $cart]);
        }

        return back();
    }
}
