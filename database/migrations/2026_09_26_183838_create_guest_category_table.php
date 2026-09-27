<?php

use App\Models\Category;
use App\Models\Guest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_guest', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Guest::class);
            $table->timestamps();

            $table->unique(['category_id', 'guest_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_category');
    }
};
