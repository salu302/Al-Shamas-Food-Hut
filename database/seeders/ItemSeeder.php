<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'category' => 'Burgers',
                'name_en' => 'Zinger Burger',
                'name_ur' => 'زنگر برگر',
                'description_en' => 'Crispy chicken zinger with fresh lettuce and spicy mayo.',
                'description_ur' => 'خستہ چکن زنگر تازہ لیٹش اور مسالہ میو کے ساتھ۔',
                'price' => 330.00,
                'image' => null,
                'is_available' => true,
            ],
            [
                'category' => 'Pizza',
                'name_en' => 'Fajita Pizza',
                'name_ur' => 'فجیتا پیزا',
                'description_en' => 'Sizzling fajita toppings, cheese, and golden crust.',
                'description_ur' => 'سزلنگ فجیتا ٹاپنگ، پنیر، اور سنہری کرسٹ۔',
                'price' => 950.00,
                'image' => null,
                'is_available' => true,
            ],
            [
                'category' => 'Shawarma',
                'name_en' => 'Chicken Shawarma',
                'name_ur' => 'چکن شوارما',
                'description_en' => 'Juicy chicken shawarma wrapped in soft flatbread.',
                'description_ur' => 'چکن شوارما نرم روٹی میں لپٹا ہوا۔',
                'price' => 420.00,
                'image' => null,
                'is_available' => true,
            ],
            [
                'category' => 'Drinks',
                'name_en' => 'Mango Shake',
                'name_ur' => 'آم شیک',
                'description_en' => 'Rich mango shake with cream and ice.',
                'description_ur' => 'کریمی اور برف کے ساتھ رچ آم شیک۔',
                'price' => 180.00,
                'image' => null,
                'is_available' => true,
            ],
        ];

        foreach ($items as $itemData) {
            $category = Category::where('name_en', $itemData['category'])->first();
            if (! $category) {
                continue;
            }

            Item::create([
                'category_id' => $category->id,
                'name_en' => $itemData['name_en'],
                'name_ur' => $itemData['name_ur'],
                'description_en' => $itemData['description_en'],
                'description_ur' => $itemData['description_ur'],
                'price' => $itemData['price'],
                'image' => $itemData['image'],
                'is_available' => $itemData['is_available'],
            ]);
        }
    }
}
