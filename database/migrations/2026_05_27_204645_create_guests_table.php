<?php

use App\Enum\Language;
use App\Models\GuestGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 80)->nullable();
            $table->string('last_name', 80)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->enum('lang', Language::values())->nullable();
            $table->foreignIdFor(GuestGroup::class, 'group_id')->constrained()->restrictOnDelete();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();

            $table->integer('group_id_for_unique')
                ->virtualAs('IF(is_primary, group_id, NULL)')
                ->nullable();

            $table->unique('group_id_for_unique', 'unique_group_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
