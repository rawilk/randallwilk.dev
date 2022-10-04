<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authenticator_apps', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->text('secret')->nullable();
            $table->dateTime('last_used_at')->nullable();
            $table->dateTimestamps();
        });
    }
};
