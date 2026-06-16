<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Users;

use App\Actions\Users\DeleteUserAction as Deleter;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Rawilk\ProfileFilament\Auth\Sudo\Actions\Concerns\RequiresSudoChallenge;

class DeleteUserAction extends DeleteAction
{
    use RequiresSudoChallenge;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerSudoChallenge();

        $this->modalAlignment(Alignment::Left);

        $this->modalDescription(fn (User $record): Htmlable => new HtmlString(Blade::render(<<<'HTML'
        <div class="fi-modal-description text-pretty">{{ str($description)->markdown()->toHtmlString() }}</div>
        HTML, [
            'description' => __('users/resource.actions.delete.modal_description', ['name' => e($record->name->full)]),
        ])));

        $this->using(function (Deleter $deleter, User $record): bool {
            if ($this->shouldChallengeForSudo()) {
                $this->cancel();
            }

            $deleter($record);

            return true;
        });
    }
}
