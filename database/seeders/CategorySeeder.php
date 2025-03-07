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
            ['name' => 'Technology',"status" => false],
            ['name' => 'Health'],
            ['name' => 'Education'],
            ['name' => 'Sports'],
            ['name' => 'Entertainment'],
        ];
        $categories = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $categories);
        Category::insert($categories);
    }
}
