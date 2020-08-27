<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    public function up(): void
    {
        Schema::create('issues', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('repository_id');
            $table->string('number');
            $table->string('title');
            $table->string('url');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('repository_id')
                ->references('id')
                ->on('repositories')
                ->onDelete('cascade');
        });
    }
}
