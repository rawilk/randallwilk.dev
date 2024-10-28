<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('profile-filament.table_names.webauthn_key'), function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->text('credential_id');
            $table->text('public_key');
            $table->string('attachment_type', 50)->nullable();
            $table->boolean('is_passkey')->default(false);
            $table->dateTime('last_used_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }
};
