<?php

declare(strict_types=1);

namespace App\View\Components\Feedback;

use App\Enums\SessionAlert as SessionAlertEnum;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SessionAlert extends Component
{
    public readonly SessionAlertEnum $type;

    public function __construct(
        string|SessionAlertEnum $type = SessionAlertEnum::Success,
        protected readonly bool $pullFromSession = false,
    ) {
        $this->type = is_string($type)
            ? SessionAlertEnum::from($type)
            : $type;
    }

    public function message(): ?string
    {
        return $this->type->message(pull: $this->pullFromSession);
    }

    public function exists(): bool
    {
        return $this->type->exists();
    }

    public function render(): View
    {
        return view('components.feedback.session-alert');
    }
}
