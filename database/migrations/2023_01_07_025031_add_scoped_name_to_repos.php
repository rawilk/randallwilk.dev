<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repositories', function (Blueprint $table) {
            $table->string('scoped_name')
                ->after('name')
                ->index()
                ->nullable()
                ->comment('The name of the repo with the owner\'s username prefixed.');
        });
    }
};
