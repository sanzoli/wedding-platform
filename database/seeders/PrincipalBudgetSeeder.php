<?php

namespace Database\Seeders;

use App\Models\Budget;
use Illuminate\Database\Seeder;

class PrincipalBudgetSeeder extends Seeder
{
    public function run(): void
    {
        Budget::factory()
            ->create([
                'name' => 'Wedding Budget',
            ]);
    }
}
