<?php

use App\Enum\Importance;
use App\Models\Budget;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Budget::class);
            $table->string('name', 80);
            $table->enum('importance', Importance::names())->nullable();
            $table->decimal('expected_amount', 16)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};
