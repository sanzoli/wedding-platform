<?php

use App\Enum\Importance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support modifying column constraints, so we need to recreate the table
        Schema::dropIfExists('budget_items');
        
        Schema::create('budget_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(\App\Models\Budget::class);
            $table->string('name', 80);
            $table->enum('importance', Importance::names())->nullable();
            $table->decimal('expected_amount', 16)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Revert to old enum values (with Innegociable instead of MustHave)
        Schema::dropIfExists('budget_items');
        
        Schema::create('budget_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(\App\Models\Budget::class);
            $table->string('name', 80);
            $table->enum('importance', ['Innegociable', 'High', 'Normal', 'Low'])->nullable();
            $table->decimal('expected_amount', 16)->nullable();
            $table->timestamps();
        });
    }
};
