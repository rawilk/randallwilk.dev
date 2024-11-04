<?php

declare(strict_types=1);

use App\Notifications\Auth\ResetPassword;
use App\Notifications\Auth\ResetPasswordInvalidUser;
use Illuminate\Contracts\Queue\ShouldQueue;

arch()->expect('App\Notifications')
    ->toHaveConstructor()
    ->ignoring('App\Notifications\Concerns')
    ->toHaveSuffix('Notification')
    ->ignoring([
        'App\Notifications\Concerns',
        ResetPassword::class,
        ResetPasswordInvalidUser::class,
    ]);

arch()->expect('App\Notifications')
    ->classes()
    ->toExtend(Illuminate\Notifications\Notification::class);

arch()->expect('App\Notifications')
    ->classes()
    ->toImplement(ShouldQueue::class)
    ->ignoring([
        ResetPassword::class,
        ResetPasswordInvalidUser::class,
    ]);
