<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('profile-filament.table_names.authenticator_app'), function (Blueprint $table) {
            $table->id();
            $table->user(nullable: false);
            $table->string('name')->nullable();
            $table->text('secret')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }
};
