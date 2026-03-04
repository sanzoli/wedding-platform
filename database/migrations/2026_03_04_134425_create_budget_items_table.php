<?php

use App\Enum\Importance;
use App\Enum\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 80);
            $table->enum('importance', Importance::names())->nullable();
            $table->enum('status', array_column(Status::cases(), 'name'))->default(Status::Pending);
            $table->decimal('expected_amount', 16)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};
