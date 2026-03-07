<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->for(Wedding::factory()->create([
                'name' => 'Lau & David',
                'date' => '2026-04-08',
            ]), 'currentWedding')
            ->create([
                'name' => 'Sanzoli Admin',
                'email' => 'sanzoli.team@gmail.com',
            ]);

    }
}
