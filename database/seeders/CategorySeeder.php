<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Language Essentials'],
            ['name' => 'Business',"status" => false],
            ['name' => 'Test Preparation',"status" => false],
            ['name' => 'Kids',"status" => false],
            ['name' => 'Conversation'],
            ['name' => 'Medical',"status" => false],
            ['name' => 'Technology',"status" => false]
        ];
        $categories = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $categories);
        Category::insert($categories);
    }
}
