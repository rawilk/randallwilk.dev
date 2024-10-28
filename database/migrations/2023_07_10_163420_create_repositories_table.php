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
            $table->id();
            $table->humanKey();
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
            $table->timestamp('repository_created_at')->useCurrent();
            $table->boolean('new')->default(false);
            $table->boolean('highlighted')->default(false);
            $table->string('type')->nullable();
            $table->string('language')->nullable();
            $table->boolean('visible')->default(false);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }
};
