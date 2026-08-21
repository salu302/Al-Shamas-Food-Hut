<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ItemVariant::truncate();
        Item::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            ['name_en' => 'Pizzas', 'name_ur' => 'پیزا', 'status' => true],
            ['name_en' => 'Burgers', 'name_ur' => 'برگر', 'status' => true],
            ['name_en' => 'Shawarma', 'name_ur' => 'شوارما', 'status' => true],
            ['name_en' => 'Pasta', 'name_ur' => 'پاستا', 'status' => true],
            ['name_en' => 'Fries', 'name_ur' => 'فرائز', 'status' => true],
            ['name_en' => 'Rolls', 'name_ur' => 'رول', 'status' => true],
            ['name_en' => 'Wings', 'name_ur' => 'وِنگز', 'status' => true],
            ['name_en' => 'Deals', 'name_ur' => 'ڈیلز', 'status' => true],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        $pizzaCategory = Category::where('name_en', 'Pizzas')->first();
        $specialPizzaCategory = Category::where('name_en', 'Deals')->first();
        $burgerCategory = Category::where('name_en', 'Burgers')->first();
        $shawarmaCategory = Category::where('name_en', 'Shawarma')->first();
        $pastaCategory = Category::where('name_en', 'Pasta')->first();
        $friesCategory = Category::where('name_en', 'Fries')->first();
        $rollsCategory = Category::where('name_en', 'Rolls')->first();
        $wingsCategory = Category::where('name_en', 'Wings')->first();

        $pizzaItems = [
            ['name_en' => 'Chicken Fajita', 'name_ur' => 'چکن فجیتا', 'category_id' => $pizzaCategory->id],
            ['name_en' => 'Chicken Tikka', 'name_ur' => 'چکن تکہ', 'category_id' => $pizzaCategory->id],
            ['name_en' => 'Hot & Spicy', 'name_ur' => 'ہاٹ اینڈ سپائسی', 'category_id' => $pizzaCategory->id],
            ['name_en' => 'Achari Pizza', 'name_ur' => 'اچاری پیزا', 'category_id' => $pizzaCategory->id],
        ];

        foreach ($pizzaItems as $item) {
            $created = Item::create(array_merge($item, [
                'description_en' => null,
                'description_ur' => null,
                'price' => 0,
                'image' => null,
                'is_available' => true,
            ]));

            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'Small', 'name_ur' => 'سمال', 'price' => 500.00, 'is_available' => true]);
            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'Medium', 'name_ur' => 'میڈیم', 'price' => 800.00, 'is_available' => true]);
            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'Large', 'name_ur' => 'لارج', 'price' => 1250.00, 'is_available' => true]);
            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'XL', 'name_ur' => 'ایکسٹرا لارج', 'price' => 1700.00, 'is_available' => true]);
        }

        $specialPizzaItems = [
            ['name_en' => 'Malai Boti', 'name_ur' => 'ملائی بوٹی', 'category_id' => $pizzaCategory->id],
            ['name_en' => 'Crown Crust', 'name_ur' => 'کراؤن کرسٹ', 'category_id' => $pizzaCategory->id],
        ];

        foreach ($specialPizzaItems as $item) {
            $created = Item::create(array_merge($item, [
                'description_en' => null,
                'description_ur' => null,
                'price' => 0,
                'image' => null,
                'is_available' => true,
            ]));

            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'Small', 'name_ur' => 'سمال', 'price' => 600.00, 'is_available' => true]);
            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'Medium', 'name_ur' => 'میڈیم', 'price' => 1000.00, 'is_available' => true]);
            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'Large', 'name_ur' => 'لارج', 'price' => 1500.00, 'is_available' => true]);
            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'XL', 'name_ur' => 'ایکسٹرا لارج', 'price' => 1800.00, 'is_available' => true]);
        }

        $burgers = [
            ['name_en' => 'Chicken Burger', 'name_ur' => 'چکن برگر', 'price' => 170.00],
            ['name_en' => 'Zinger Burger', 'name_ur' => 'زنگر برگر', 'price' => 330.00],
            ['name_en' => 'Double Zinger Burger', 'name_ur' => 'ڈبل زنگر برگر', 'price' => 600.00],
            ['name_en' => 'Patty Burger', 'name_ur' => 'پیٹی برگر', 'price' => 300.00],
            ['name_en' => 'Patty Cheese Burger', 'name_ur' => 'پیٹی چیز برگر', 'price' => 350.00],
            ['name_en' => 'Zinger Cheese Burger', 'name_ur' => 'زنگر چیز برگر', 'price' => 350.00],
            ['name_en' => 'Tower Burger', 'name_ur' => 'ٹاور برگر', 'price' => 450.00],
            ['name_en' => 'Double Patty Burger', 'name_ur' => 'ڈبل پیٹی برگر', 'price' => 500.00],
        ];

        foreach ($burgers as $item) {
            Item::create(array_merge($item, [
                'category_id' => $burgerCategory->id,
                'description_en' => null,
                'description_ur' => null,
                'image' => null,
                'is_available' => true,
            ]));
        }

        $shawarmas = [
            ['name_en' => 'Small Shawarma', 'name_ur' => 'سمال شوارما', 'price' => 150.00],
            ['name_en' => 'Large Shawarma', 'name_ur' => 'لارج شوارما', 'price' => 200.00],
            ['name_en' => 'Malai Boti Shawarma', 'name_ur' => 'ملائی بوٹی شوارما', 'price' => 250.00],
            ['name_en' => 'Zinger Shawarma', 'name_ur' => 'زنگر شوارما', 'price' => 300.00],
            ['name_en' => 'Zinger Cheese Shawarma', 'name_ur' => 'زنگر چیز شوارما', 'price' => 350.00],
        ];

        foreach ($shawarmas as $item) {
            Item::create(array_merge($item, [
                'category_id' => $shawarmaCategory->id,
                'description_en' => null,
                'description_ur' => null,
                'image' => null,
                'is_available' => true,
            ]));
        }

        $pastas = [
            ['name_en' => 'Macaroni Pasta', 'name_ur' => 'میکرونی پاستا', 'small_price' => 300.00, 'large_price' => 600.00],
            ['name_en' => 'Crunchy Pasta', 'name_ur' => 'کرنچی پاستا', 'small_price' => 350.00, 'large_price' => 700.00],
            ['name_en' => 'Vegetable Pasta', 'name_ur' => 'ویجیٹیبل پاستا', 'small_price' => 300.00, 'large_price' => 600.00],
            ['name_en' => 'Alfredo Pasta', 'name_ur' => 'الفریڈو پاستا', 'small_price' => 350.00, 'large_price' => 700.00],
        ];

        foreach ($pastas as $item) {
            $created = Item::create([
                'name_en' => $item['name_en'],
                'name_ur' => $item['name_ur'],
                'category_id' => $pastaCategory->id,
                'description_en' => null,
                'description_ur' => null,
                'price' => 0,
                'image' => null,
                'is_available' => true,
            ]);

            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'Small', 'name_ur' => 'سمال', 'price' => $item['small_price'], 'is_available' => true]);
            ItemVariant::create(['item_id' => $created->id, 'name_en' => 'Large', 'name_ur' => 'لارج', 'price' => $item['large_price'], 'is_available' => true]);
        }

        $fries = [
            ['name_en' => 'Small Fries', 'name_ur' => 'سمال فرائز', 'price' => 200.00],
            ['name_en' => 'Large Fries', 'name_ur' => 'لارج فرائز', 'price' => 300.00],
            ['name_en' => 'Loaded Fries', 'name_ur' => 'لوڈڈ فرائز', 'price' => 350.00],
        ];

        foreach ($fries as $item) {
            Item::create(array_merge($item, [
                'category_id' => $friesCategory->id,
                'description_en' => null,
                'description_ur' => null,
                'image' => null,
                'is_available' => true,
            ]));
        }

        $rolls = [
            ['name_en' => 'Chicken Roll', 'name_ur' => 'چکن رول', 'price' => 180.00],
            ['name_en' => 'Zinger Roll', 'name_ur' => 'زنگر رول', 'price' => 240.00],
            ['name_en' => 'Shawarma Roll', 'name_ur' => 'شوارما رول', 'price' => 220.00],
            ['name_en' => 'Cheese Roll', 'name_ur' => 'چیز رول', 'price' => 260.00],
        ];

        foreach ($rolls as $item) {
            Item::create(array_merge($item, [
                'category_id' => $rollsCategory->id,
                'description_en' => null,
                'description_ur' => null,
                'image' => null,
                'is_available' => true,
            ]));
        }

        $wings = [
            ['name_en' => '6 pc Wings', 'name_ur' => '6 پیس وِنگز', 'price' => 720.00],
            ['name_en' => '10 pc Wings', 'name_ur' => '10 پیس وِنگز', 'price' => 1050.00],
            ['name_en' => 'Spicy Wings', 'name_ur' => 'سپائسی وِنگز', 'price' => 780.00],
            ['name_en' => 'BBQ Wings', 'name_ur' => 'بی بی کیو وِنگز', 'price' => 800.00],
        ];

        foreach ($wings as $item) {
            Item::create(array_merge($item, [
                'category_id' => $wingsCategory->id,
                'description_en' => null,
                'description_ur' => null,
                'image' => null,
                'is_available' => true,
            ]));
        }

        $deals = [
            ['name_en' => 'Deal 950', 'name_ur' => 'ڈیل 950', 'price' => 950.00],
        ];

        foreach ($deals as $item) {
            Item::create(array_merge($item, [
                'category_id' => $specialPizzaCategory->id,
                'description_en' => null,
                'description_ur' => null,
                'image' => null,
                'is_available' => true,
            ]));
        }
    }
}
