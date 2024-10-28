<?php

declare(strict_types=1);

namespace App\Filament\Actions\Auth;

use Filament\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

class GitHubLoginAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->color('gray');

        $this->icon('svg-github');

        $this->size(ActionSize::Large);

        $this->label('Continue with GitHub');

        $this->extraAttributes([
            'class' => 'w-full',
        ]);

        $this->alpineClickHandler(Blade::render(<<<'JS'
        const showGitHubAuth = () => {
            // Calculate 40% of window width and 40% of screen width
            const windowBasedWidth = Math.floor(window.innerWidth * 0.4);
            const screenBasedWidth = Math.floor(window.screen.width * 0.4);

            // Choose the larger of the two widths
            const windowWidth = Math.max(windowBasedWidth, screenBasedWidth);

            // Calculate the window height (still 80% of the window height)
            const windowHeight = Math.floor(window.innerHeight * 0.8);

            // Calculate the position to center the window
            const left = Math.floor(window.screenX + (window.innerWidth - windowWidth) / 2);
            const top = Math.floor(window.screenY + (window.innerHeight - windowHeight) / 2);

            // Construct the features string
            const features = `width=${windowWidth},height=${windowHeight},top=${top},left=${left}`;

            const authWindow = window.open(
                @js($url),
                null,
                features,
            );

            let redirectUrl = undefined;

            window.addEventListener('message', function (event) {
                // Ensure the message is from the expected origin
                if (event.origin !== window.location.origin) {
                    return;
                }

                if (event.data.type === 'AUTH_COMPLETE') {
                    redirectUrl = event.data.redirectUrl;

                    authWindow.close();
                }
            });

            const authCheckInterval = window.setInterval(() => {
                if (authWindow.closed) {
                    window.clearInterval(authCheckInterval);
                    window.location.replace(redirectUrl ?? @js(session('next', '/' . filament()->getCurrentPanel()->getPath())));
                }
            }, 500);
        };

        showGitHubAuth();
        JS, [
            'url' => URL::temporarySignedRoute(
                'login.github',
                now()->addMinutes(10),
                [
                    'p' => filament()->getId(),
                ],
            ),
        ]));
    }

    public static function getDefaultName(): ?string
    {
        return 'githubLogin';
    }
}
