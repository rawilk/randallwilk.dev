<?php

declare(strict_types=1);

namespace App\Support\Macros;

use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Database\Schema\ForeignKeyDefinition;

class MacroHelper
{
    public static function buildForeignIdMacro(
        ForeignIdColumnDefinition $builder,
        bool $nullable,
        bool $cascade,
        string $table,
    ): ForeignKeyDefinition {
        if ($nullable) {
            return $cascade
                ? $builder->nullable()->constrained($table)->nullOnDelete()
                : $builder->nullable()->constrained($table)->noActionOnDelete();
        }

        return $cascade
            ? $builder->constrained($table)->cascadeOnDelete()
            : $builder->constrained($table)->noActionOnDelete();
    }
}
