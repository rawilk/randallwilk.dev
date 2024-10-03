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
            $table->uuid('id')->primary();
            $table->string('h_key')->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('timezone')->default('UTC');
            $table->string('password')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('avatar_path')->nullable();
            $table->unsignedInteger('github_id')->nullable();
            $table->string('github_username')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->datetimes();
        });
    }
};
