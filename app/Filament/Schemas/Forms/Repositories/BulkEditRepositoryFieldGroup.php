<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Repositories;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;

class BulkEditRepositoryFieldGroup
{
    public static function make(Field $field): Component
    {
        return Group::make([
            Select::make("{$field->getName()}_status")
                ->label($field->getLabel())
                ->default('keep')
                ->options([
                    'keep' => __('filament/forms.bulk_edit.field_status.options.keep'),
                    'edit' => __('filament/forms.bulk_edit.field_status.options.edit'),
                ])
                ->selectablePlaceholder(false),

            $field
                ->label(__('filament/forms.bulk_edit.new_value.label'))
                ->visibleJs("\$get('{$field->getName()}_status') === 'edit'"),
        ])
            ->columns(['md' => 2]);
    }
}
