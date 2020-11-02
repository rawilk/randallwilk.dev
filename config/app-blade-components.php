<?php

use App\View\Components;

return [
    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | Below is a reference of the blade components that should be registered
    | for the application.
    |
    */
    'components' => [

        'html' => [
            'class' => Components\Layouts\Html::class,
            'view' => 'components.layouts.html',
        ],

        'app' => [
            'class' => Components\Layouts\App::class,
            'view' => 'components.layouts.app',
        ],

        'authentication-form' => [
            'class' => Components\Auth\AuthenticationForm::class,
            'view' => 'components.auth.authentication-form',
        ],

        'button' => [
            'class' => Components\Button\Button::class,
            'view' => 'components.button.button',
        ],

        'alert' => [
            'class' => Components\Alerts\Alert::class,
            'view' => 'components.alerts.alert',
        ],

        'session-alert' => [
            'class' => Components\Alerts\SessionAlert::class,
            'view' => 'components.alerts.session-alert',
        ],

    ],
];
