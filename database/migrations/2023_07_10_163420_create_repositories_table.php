<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('h_key')->unique();
            $table->string('name');
            $table->string('scoped_name')
                ->index()
                ->nullable()
                ->comment('The name of the repo with the owner\'s username prefixed.');
            $table->text('description')->nullable();
            $table->json('topics')->nullable();
            $table->string('documentation_url')->nullable();
            $table->string('blogpost_url')->nullable();
            $table->unsignedInteger('stars')->default(0);
            $table->unsignedInteger('downloads')->default(0);
            $table->dateTime('repository_created_at')->useCurrent();
            $table->boolean('new')->default(false);
            $table->boolean('highlighted')->default(false);
            $table->string('type')->nullable();
            $table->string('language')->nullable();
            $table->boolean('visible')->default(false);
            $table->dateTimestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }
};
