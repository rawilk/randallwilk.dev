<?php

declare(strict_types=1);

namespace App\Support\Filament;

use App\Filament\Infolists\DateEntry;
use App\Filament\Tables\Columns\DateColumn;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Actions\ActionGroup as TableActionGroup;
use Filament\Tables\Columns\Column;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Rawilk\FilamentPasswordInput\Password;

use function App\Helpers\userTimezone;

class FilamentDefaults
{
    public static function set(): void
    {
        static::configureForms();

        static::configureTables();

        static::createMacros();

        ActionGroup::configureUsing(function (ActionGroup $group): void {
            $group
                ->color('gray')
                ->tooltip(__('actions.actions.label'))
                ->icon('heroicon-m-ellipsis-horizontal');
        });

        DateEntry::configureUsing(function (DateEntry $date): void {
            $date
                ->timezone(userTimezone())
                ->dateTime(format: DateEntry::$defaultDateTimeDisplayFormat);
        });
    }

    protected static function configureForms(): void
    {
        Field::configureUsing(function (Field $field): void {
            $field->markAsRequired(false);
        });

        Password::configureUsing(function (Password $password): void {
            $password->inlineSuffix();
        }, isImportant: true);

        DateTimePicker::configureUsing(function (DateTimePicker $picker): void {
            $picker
                ->native(false)
                ->prefixIcon('heroicon-o-calendar')
                ->closeOnDateSelection();
        });

        FormAction::configureUsing(function (FormAction $action): void {
            $action->color('gray');
        });
    }

    protected static function configureTables(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table
                ->striped()
                ->filtersLayout(FiltersLayout::Modal)
                ->filtersFormWidth(MaxWidth::ExtraLarge)
                ->filtersTriggerAction(fn (TableAction $action) => $action->slideOver())
                ->actions([], position: ActionsPosition::BeforeColumns);
        });

        Column::configureUsing(function (Column $column): void {
            $column->toggleable();
        });

        DateColumn::configureUsing(function (DateColumn $column): void {
            $column
                ->timezone(userTimezone())
                ->dateTime(format: DateColumn::$defaultDateTimeDisplayFormat);
        });
    }

    protected static function createMacros(): void
    {
        TableActionGroup::macro('tableHeader', function () {
            return $this
                ->button()
                ->tooltip(null)
                ->icon('heroicon-m-ellipsis-vertical')
                ->dropdownPlacement('bottom-end');
        });

        ActionGroup::macro('pageHeader', function () {
            return $this
                ->button()
                ->tooltip(null)
                ->icon('heroicon-m-ellipsis-vertical')
                ->dropdownPlacement('bottom-end');
        });

        TextEntry::macro('mono', function (bool $copyable = true) {
            $customClasses = data_get($this->getExtraAttributes(), 'class', '');

            $this
                ->extraAttributes([
                    'class' => "{$customClasses} font-mono",
                ])
                ->copyable($copyable);

            return $this;
        });
    }
}
