<?php

declare(strict_types=1);

namespace App\Filament\Schemas\Infolists\Repositories;

use App\Filament\Schemas\CopyableText;
use App\Filament\Schemas\Infolists\DateEntry;
use App\Models\Repository;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

class ViewRepositoryInfolist
{
    public static function make(): array
    {
        return [
            static::info(),
            static::gitHubMeta(),
            static::meta(),
        ];
    }

    protected static function info(): Section
    {
        return Section::make(__('repositories/view.sections.info.heading'))
            ->collapsible()
            ->schema([
                Group::make([
                    TextEntry::make('name')
                        ->label(__('repositories/view.attributes.name.label'))
                        ->url(
                            fn (Repository $record): string => $record->url,
                            shouldOpenInNewTab: true,
                        ),

                    TextEntry::make('scoped_name')
                        ->label(__('repositories/view.attributes.scoped_name.label'))
                        ->placeholder(__('repositories/view.attributes.scoped_name.placeholder'))
                        ->belowContent(__('repositories/view.attributes.scoped_name.help')),
                ])->columns(['md' => 2]),

                TextEntry::make('description')
                    ->placeholder('-')
                    ->label(__('repositories/view.attributes.description.label')),

                TextEntry::make('type')
                    ->placeholder(__('repositories/view.attributes.type.placeholder'))
                    ->label(__('repositories/view.attributes.type.label'))
                    ->badge(),

                TextEntry::make('language')
                    ->label(__('repositories/view.attributes.language.label'))
                    ->badge(),

                TextEntry::make('documentation_url')
                    ->label(__('repositories/view.attributes.documentation_url.label'))
                    ->placeholder('-')
                    ->url(
                        fn (Repository $record): ?string => $record->documentation_url,
                        shouldOpenInNewTab: true,
                    ),

                TextEntry::make('blogpost_url')
                    ->label(__('repositories/view.attributes.blogpost_url.label'))
                    ->placeholder('-')
                    ->url(
                        fn (Repository $record): ?string => $record->blogpost_url,
                        shouldOpenInNewTab: true,
                    ),

                IconEntry::make('highlighted')
                    ->label(__('repositories/view.attributes.highlighted.label')),

                IconEntry::make('new')
                    ->label(__('repositories/view.attributes.new.label')),
            ]);
    }

    protected static function gitHubMeta(): Section
    {
        return Section::make(__('repositories/view.sections.github_meta.heading'))
            ->collapsible()
            ->schema([
                DateEntry::make('repository_created_at')
                    ->label(__('repositories/view.attributes.repository_created_at.label'))
                    ->belowContent(__('repositories/view.attributes.repository_created_at.help')),

                TextEntry::make('stars')
                    ->label(__('repositories/view.attributes.stars.label'))
                    ->numeric(),

                TextEntry::make('downloads')
                    ->label(__('repositories/view.attributes.downloads.label'))
                    ->numeric()
                    ->visible(fn (Repository $record): bool => $record->isPackage()),

                TextEntry::make('topics')
                    ->label(__('repositories/view.attributes.topics.label'))
                    ->placeholder('-')
                    ->badge(),
            ]);
    }

    protected static function meta(): Section
    {
        return Section::make(__('repositories/view.sections.meta.heading'))
            ->collapsible()
            ->inlineLabel()
            ->schema([
                CopyableText::make('id')
                    ->label(__('repositories/view.attributes.id.label'))
                    ->fontMono(),

                CopyableText::make('h_key')
                    ->label(__('repositories/view.attributes.h_key.label'))
                    ->fontMono(),

                DateEntry::make('created_at')
                    ->label(__('filament/models.created_at.label')),

                DateEntry::make('updated_at')
                    ->label(__('filament/models.updated_at.label')),
            ]);
    }
}
