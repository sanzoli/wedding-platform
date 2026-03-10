<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('budget_items')
            ->where('importance', 'Innegociable')
            ->update(['importance' => 'MustHave']);
    }

    public function down(): void
    {
        DB::table('budget_items')
            ->where('importance', 'MustHave')
            ->update(['importance' => 'Innegociable']);
    }
};
