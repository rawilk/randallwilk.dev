<?php

declare(strict_types=1);

namespace App\Support\Macros;

use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Database\Schema\ForeignKeyDefinition;

readonly class MigrationMacroHelper
{
    public static function register(): void
    {
        Blueprint::macro('user', function (
            string $column = 'user_id',
            bool $nullable = true,
            bool $cascade = true,
            ?Closure $callback = null,
        ): ForeignKeyDefinition {
            $builder = $this->foreignId($column);

            return MigrationMacroHelper::buildForeignIdMacro(
                builder: $builder,
                nullable: $nullable,
                cascade: $cascade,
                table: 'users',
                callback: $callback,
            );
        });

        Blueprint::macro('humanKey', function () {
            return $this->string('h_key')->unique();
        });
    }

    public static function buildForeignIdMacro(
        ForeignIdColumnDefinition $builder,
        bool $nullable,
        bool $cascade,
        string $table,
        ?Closure $callback = null,
    ): ForeignKeyDefinition {
        if ($nullable) {
            $builder->nullable();
        }

        $foreignKey = $builder->constrained($table);

        if ($cascade) {
            $nullable
                ? $foreignKey->nullOnDelete()
                : $foreignKey->cascadeOnDelete();
        }

        if ($callback instanceof Closure) {
            $foreignKey = $callback($foreignKey) ?? $foreignKey;
        }

        return $foreignKey;
    }
}
