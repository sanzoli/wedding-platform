<?php

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->ulid('id');
            $table->foreignIdFor(Wedding::class)->constrained();
            $table->string('email', 80)->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
