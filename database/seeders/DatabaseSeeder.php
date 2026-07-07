<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Администратор
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        // Категории
        $categories = [
            ['name' => 'Футболки', 'slug' => 't-shirts'],
            ['name' => 'Джинсы', 'slug' => 'jeans'],
            ['name' => 'Куртки', 'slug' => 'jackets'],
            ['name' => 'Обувь', 'slug' => 'shoes'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Товары
        $products = [
            [
                'category_id' => 1,
                'name' => 'Черная футболка',
                'slug' => 'black-t-shirt',
                'description' => 'Классическая черная футболка из 100% хлопка',
                'price' => 1490,
                'stock' => 25,
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Белая футболка',
                'slug' => 'white-t-shirt',
                'description' => 'Белая футболка с круглым вырезом',
                'price' => 1290,
                'stock' => 30,
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Классические джинсы',
                'slug' => 'classic-jeans',
                'description' => 'Синие джинсы прямого кроя',
                'price' => 3990,
                'stock' => 15,
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Кожаная куртка',
                'slug' => 'leather-jacket',
                'description' => 'Натуральная кожа, подкладка на утеплителе',
                'price' => 14990,
                'stock' => 5,
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'Кроссовки белые',
                'slug' => 'white-sneakers',
                'description' => 'Удобные кроссовки для повседневной носки',
                'price' => 5990,
                'stock' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
