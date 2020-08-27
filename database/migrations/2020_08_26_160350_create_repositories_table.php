<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRepositoriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('repositories', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('topics')->nullable();
            $table->string('documentation_url')->nullable();
            $table->string('blogpost_url')->nullable();
            $table->unsignedInteger('stars')->default(0);
            $table->unsignedInteger('downloads')->nullable();
            $table->datetime('repository_created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('new')->default(false);
            $table->boolean('highlighted')->default(false);
            $table->string('type')->nullable();
            $table->string('language')->nullable();
            $table->boolean('visible')->default(true);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }
}
