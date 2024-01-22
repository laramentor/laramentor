<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SocialProvider;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('socialites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->unique();
            $table->string('provider_id')->unique();
            $table->string('email')->unique();
            $table->enum('provider', SocialProvider::values());
            $table->string('nickname')->nullable();
            $table->string('name')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socialites');
    }
};
