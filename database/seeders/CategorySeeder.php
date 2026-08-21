<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_en' => 'Burgers', 'name_ur' => 'برگر', 'image' => null, 'status' => true],
            ['name_en' => 'Pizza', 'name_ur' => 'پیزا', 'image' => null, 'status' => true],
            ['name_en' => 'Shawarma', 'name_ur' => 'شوارما', 'image' => null, 'status' => true],
            ['name_en' => 'Drinks', 'name_ur' => 'مشروبات', 'image' => null, 'status' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
