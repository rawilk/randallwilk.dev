<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addTempColumns();
        $this->addTempForeignKeys();
        $this->populateNewIds();
        $this->updateForeignKeyReferences();
        $this->dropConstraints();
        $this->dropAndRenameColumns();
        $this->setPrimaryKeysAndConstraints();

        DB::table('sessions')
            ->whereNotNull('user_id')
            ->delete();
    }

    protected function addTempColumns(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('new_id')->nullable();
        });

        Schema::table('repositories', function (Blueprint $table) {
            $table->unsignedBigInteger('new_id')->nullable();
        });
    }

    protected function addTempForeignKeys(): void
    {
        Schema::table('webauthn_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('new_user_id')->nullable();
        });

        Schema::table('authenticator_apps', function (Blueprint $table) {
            $table->unsignedBigInteger('new_user_id')->nullable();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('new_user_id')->nullable();
        });

        Schema::table('old_user_emails', function (Blueprint $table) {
            $table->unsignedBigInteger('new_user_id')->nullable();
        });

        Schema::table('pending_user_emails', function (Blueprint $table) {
            $table->unsignedBigInteger('new_user_id')->nullable();
        });
    }

    protected function populateNewIds(): void
    {
        DB::statement('SET @counter = 0');
        DB::statement('UPDATE users SET new_id = @counter := @counter + 1');

        DB::statement('SET @counter = 0');
        DB::statement('UPDATE repositories SET new_id = @counter := @counter + 1');
    }

    protected function updateForeignKeyReferences(): void
    {
        DB::statement(<<<'SQL'
        UPDATE webauthn_keys t
        JOIN users u on t.user_id = u.id
        SET t.new_user_id = u.new_id
        SQL);

        DB::statement(<<<'SQL'
        UPDATE authenticator_apps t
        JOIN users u on t.user_id = u.id
        SET t.new_user_id = u.new_id
        SQL);

        DB::statement(<<<'SQL'
        UPDATE sessions t
        JOIN users u on t.user_id = u.id
        SET t.new_user_id = u.new_id
        SQL);

        DB::statement(<<<'SQL'
        UPDATE old_user_emails t
        JOIN users u on t.user_id = u.id
        SET t.new_user_id = u.new_id
        SQL);

        DB::statement(<<<'SQL'
        UPDATE pending_user_emails t
        JOIN users u on t.user_id = u.id
        SET t.new_user_id = u.new_id
        SQL);
    }

    protected function dropConstraints(): void
    {
        Schema::table('webauthn_keys', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('authenticator_apps', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('old_user_emails', function (Blueprint $table) {
            $table->dropIndex(['user_type', 'user_id']);
        });

        Schema::table('pending_user_emails', function (Blueprint $table) {
            $table->dropIndex(['user_type', 'user_id']);
        });
    }

    protected function dropAndRenameColumns(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('new_id', 'id');
        });

        Schema::table('repositories', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
        });

        Schema::table('repositories', function (Blueprint $table) {
            $table->renameColumn('new_id', 'id');
        });

        Schema::table('webauthn_keys', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('webauthn_keys', function (Blueprint $table) {
            $table->renameColumn('new_user_id', 'user_id');
        });

        Schema::table('authenticator_apps', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('authenticator_apps', function (Blueprint $table) {
            $table->renameColumn('new_user_id', 'user_id');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->renameColumn('new_user_id', 'user_id');
        });

        Schema::table('old_user_emails', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('old_user_emails', function (Blueprint $table) {
            $table->renameColumn('new_user_id', 'user_id');
        });

        Schema::table('pending_user_emails', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('pending_user_emails', function (Blueprint $table) {
            $table->renameColumn('new_user_id', 'user_id');
        });
    }

    protected function setPrimaryKeysAndConstraints(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->primary('id');
        });

        Schema::table('repositories', function (Blueprint $table) {
            $table->primary('id');
        });

        Schema::table('webauthn_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });

        Schema::table('authenticator_apps', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('old_user_emails', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();

            $table->index(['user_type', 'user_id']);
        });

        Schema::table('pending_user_emails', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();

            $table->index(['user_type', 'user_id']);
        });
    }
};
