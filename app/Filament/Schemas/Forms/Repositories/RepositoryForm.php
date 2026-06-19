<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Forms\Repositories;

use App\Models\Repository;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class RepositoryForm
{
    public static function make(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    RepositoryTypeSelect::make(),
                ])->columns(['md' => 2])->columnSpanFull(),

                Group::make([
                    TextInput::make('scoped_name')
                        ->label(__('repositories/resource.form.scoped_name.label'))
                        ->belowContent(__('repositories/resource.form.scoped_name.help'))
                        ->hint(__('filament/forms.optional_field'))
                        ->placeholder(__('repositories/resource.form.scoped_name.placeholder'))
                        ->maxLength(255)
                        ->unique(
                            table: Repository::class,
                            ignoreRecord: true,
                        ),
                ])->columns(['md' => 2])->columnSpanFull(),

                TextInput::make('documentation_url')
                    ->label(__('repositories/resource.form.documentation_url.label'))
                    ->placeholder(__('repositories/resource.form.documentation_url.placeholder'))
                    ->hint(__('filament/forms.optional_field'))
                    ->maxLength(255)
                    ->url(),

                TextInput::make('blogpost_url')
                    ->label(__('repositories/resource.form.blogpost_url.label'))
                    ->placeholder(__('repositories/resource.form.blogpost_url.placeholder'))
                    ->hint(__('filament/forms.optional_field'))
                    ->maxLength(255)
                    ->url(),

                Group::make([
                    RepositoryVisibilityCheckbox::make(),

                    Checkbox::make('highlighted')
                        ->label(__('repositories/resource.form.highlighted.label'))
                        ->belowContent(__('repositories/resource.form.highlighted.help')),

                    Checkbox::make('new')
                        ->label(__('repositories/resource.form.new.label'))
                        ->belowContent(__('repositories/resource.form.new.help')),
                ])->columnSpanFull(),
            ]);
    }
}
