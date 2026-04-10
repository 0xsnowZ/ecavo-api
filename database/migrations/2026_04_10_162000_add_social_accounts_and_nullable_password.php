<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make password nullable so OAuth-only users never need a hashed password
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
        });

        // Scalable social accounts — one user can link Google, GitHub, Apple, etc.
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider');          // 'google', 'github', etc.
            $table->string('provider_id');       // the ID returned by the OAuth provider
            $table->string('avatar')->nullable(); // store the provider's avatar URL
            $table->timestamps();

            $table->unique(['provider', 'provider_id']);  // one row per provider account
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');

        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
        });
    }
};
