<?php

declare(strict_types=1);

namespace App\View\Components\Feedback;

use App\View\Components\Concerns\AcceptsComponentSlots;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

use function Filament\Support\get_color_css_variables;

class Alert extends Component
{
    use AcceptsComponentSlots;

    public function __construct(
        public readonly string $color = 'danger',
        public readonly bool $dismiss = false,
        public readonly bool $removeParentOnDismiss = false,
        public readonly bool $compact = false,

        // Slots
        public $icon = 'heroicon-o-information-circle',
        public $title = false,
        public $actions = false,
        public $dismissCallback = false,
    ) {
    }

    public function classes(): string
    {
        return Arr::toCssClasses([
            'rounded-md',
            'px-4',
            'py-6' => ! $this->compact,
            'py-3' => $this->compact,
            'bg-custom-50 dark:bg-custom-500/10',
            'dark:border dark:border-custom-400',
        ]);
    }

    public function styles(): string
    {
        return Arr::toCssStyles([
            get_color_css_variables($this->color, [50, 100, 200, 300, 400, 500, 600, 700, 800]),
        ]);
    }

    public function render(): View
    {
        return view('components.feedback.alert');
    }
}
