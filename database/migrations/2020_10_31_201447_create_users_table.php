<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->index()->unique();
            $table->string('password');
            $table->string('avatar_path')->nullable();
            $table->unsignedInteger('github_id')->nullable();
            $table->string('github_username')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }
}
