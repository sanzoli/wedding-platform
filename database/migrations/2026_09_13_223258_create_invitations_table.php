<?php

use App\Enum\InvitationResponse;
use App\Enum\InvitationType;
use App\Enum\Language;
use App\Models\Guest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->enum('type', array_column(InvitationType::cases(), 'name'));
            $table->foreignIdFor(Guest::class)->constrained();
            $table->enum('default_language', Language::values())->nullable();
            $table->enum('response', array_column(InvitationResponse::cases(), 'value'))->nullable();
            $table->timestamps();

            $table->unique(['type', 'guest_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
