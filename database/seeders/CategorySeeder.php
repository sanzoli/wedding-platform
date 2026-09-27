<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::upsert([
            ['name' => 'Familia', 'color' => '#cc8131'],
            ['name' => 'Amigos', 'color' => '#47baa9'],
            ['name' => 'David', 'color' => '#5999bd'],
            ['name' => 'Lauana', 'color' => '#ae5eb5'],
        ], uniqueBy: ['name'], update: ['color']);
    }
}
