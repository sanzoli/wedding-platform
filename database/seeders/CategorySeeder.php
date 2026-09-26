<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::upsert([
            ['name' => 'Familia', 'color' => '#deb181'],
            ['name' => 'Amigos', 'color' => '#8fccc3'],
            ['name' => 'David', 'color' => '#81bdde'],
            ['name' => 'Lauana', 'color' => '#d6a0db'],
        ], uniqueBy: ['name'], update: ['color']);
    }
}
