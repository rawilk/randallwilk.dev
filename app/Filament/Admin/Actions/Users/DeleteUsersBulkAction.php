<?php

declare(strict_types=1);

namespace App\Filament\Admin\Actions\Users;

use App\Actions\Users\DeleteUserAction as Deleter;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Rawilk\ProfileFilament\Auth\Sudo\Actions\Concerns\RequiresSudoChallenge;

class DeleteUsersBulkAction extends DeleteBulkAction
{
    use RequiresSudoChallenge;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerSudoChallenge();

        $this->modalDescription(fn (): Htmlable => new HtmlString(Blade::render(<<<'HTML'
        <div>{{ str(__('users/resource.actions.delete_bulk.modal_description'))->markdown()->toHtmlString() }}</div>
        HTML,
        )));

        $this->using(function (Collection $records, Deleter $deleter) {
            if ($this->shouldChallengeForSudo()) {
                $this->cancel();
            }

            $records
                ->each(function (User $user) use ($deleter): void {
                    if (Gate::allows('delete', $user)) {
                        $deleter($user);
                    }
                });
        });
    }
}
