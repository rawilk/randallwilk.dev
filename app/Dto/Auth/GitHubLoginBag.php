<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use App\Models\User;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Rawilk\ProfileFilament\Filament\Pages\Profile\ProfileInfo;

class GitHubLoginBag
{
    protected ?User $user = null;

    protected readonly bool $isLoginRequest;

    public function __construct(protected GithubProvider $provider)
    {
        $this->isLoginRequest = auth()->guest();
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function isLoginRequest(): bool
    {
        return $this->shouldLogin();
    }

    public function shouldLogin(): bool
    {
        return $this->isLoginRequest;
    }

    public function gitHubUser(): ?SocialiteUser
    {
        return once(fn () => $this->provider->stateless()->user());
    }

    public function redirect(): string
    {
        return session()->pull(
            'next',
            $this->defaultRedirect(),
        );
    }

    public function panelId(): ?string
    {
        return once(fn (): ?string => session()->pull('panel', 'admin'));
    }

    protected function defaultRedirect(): string
    {
        return $this->isLoginRequest
            ? session('url.intended', '/' . filament()->getPanel($this->panelId())->getPath())
            : ProfileInfo::getUrl(panel: $this->panelId());
    }
}
