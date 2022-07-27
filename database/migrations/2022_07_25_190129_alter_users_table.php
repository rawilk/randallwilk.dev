<?php

use App\Models\User\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Rawilk\LaravelCasters\Support\Name;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->after('id', function (Blueprint $table) {
                $table->string('first_name');
                $table->string('last_name')->nullable();
            });

            $table->dropColumn('is_admin');
        });

        User::cursor()->each(function (User $user) {
            $name = Name::from($user->name);

            $user->first_name = $name->first;
            $user->last_name = $name->last;
            $user->timestamps = false;

            $user->save();
        });

        Schema::table('users', static function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
