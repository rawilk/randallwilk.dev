<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToRepositories extends Migration
{
    public function up(): void
    {
        Schema::table('repositories', static function (Blueprint $table) {
            $table->dateTime('deleted_at')->nullable();
        });
    }
}
