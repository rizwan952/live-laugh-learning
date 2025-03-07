<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            LanguageSeeder::class,
            LanguageLevelSeeder::class,
            TagSeeder::class,
            CourseSeeder::class,
            CourseTagSeeder::class,
            CourseDurationSeeder::class,
            CoursePackageSeeder::class
        ]);
    }
}
