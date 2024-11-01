<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->humanKey();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('timezone')->default('UTC');
            $table->string('password')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('avatar_path')->nullable();
            $table->unsignedInteger('github_id')->nullable();
            $table->string('github_username')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });
    }
};
