<?php

use Rawilk\LaravelBase\Features;

return [
    /*
    |--------------------------------------------------------------------------
    | LaravelBase Routes Middleware
    |--------------------------------------------------------------------------
    |
    | Here you may specify which middleware LaravelBase will assign to the routes
    | that it registers with the application. If necessary, you may change
    | these middleware but typically this provided default is preferred.
    |
    */
    'middleware' => ['web', \Rawilk\LaravelBase\Http\Middleware\EnsureActiveUserMiddleware::class],
    'admin_middleware' => ['web', 'admin', \Rawilk\LaravelBase\Http\Middleware\EnsureActiveUserMiddleware::class],

    'views' => [
        'users' => [
            'index' => 'admin.users.index.index',
            'create' => 'admin.users.create.index',
            'edit' => 'admin.users.edit.index',
            'abilities' => 'admin.users.edit.abilities',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of the LaravelBase features are optional. You may disable the features
    | by removing them from this array. You're free to only remove some of
    | these features or you can remove all of these if you need to.
    |
    */
    'features' => [
        Features::registration(),
        // Features::emailVerification(),
        Features::resetPasswords(),
        Features::twoFactorAuthentication([
            'confirmPassword' => true, // Forces confirm password when enabling, disabling, etc.
        ]),
        Features::webauthn([
            'confirmPassword' => true, // Forces confirm password when enabling, disabling, etc.
        ]),

        // Profile features...
        Features::avatars(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::accountDeletion(),

        // Admin features...
        Features::userManagement(),
        Features::roleManagement(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin View Layout
    |--------------------------------------------------------------------------
    |
    | Here you may customize the path to the layout view that should be used
    | to render any admin views from this package's routes. We've set a
    | default for you if you are using the stubs from this package.
    |
    */
    'admin_view_layout' => 'layouts.admin.base',

    /*
    |--------------------------------------------------------------------------
    | Authenticator App
    |--------------------------------------------------------------------------
    |
    | Here you may customize the table name and model used for a user's stored
    | two-factor authenticator apps.
    |
    */
    'authenticator_apps' => [
        'table' => 'authenticator_apps',

        /*
         * You may extend our model, or use your own. If you use your
         * own model, it must implement the \Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp
         * contract.
         */
        'model' => \Rawilk\LaravelBase\Models\AuthenticatorApp::class,

        /*
         * You may restrict the amount of authenticator apps a user may register to their account.
         * Set to `null` for unlimited registrations per user.
         */
        'max_per_user' => env('AUTHENTICATOR_APP_USER_MAX', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | WebAuthn
    |--------------------------------------------------------------------------
    |
    | If you wish to restrict users on how many WebAuthn security keys
    | they may register to their account, you may do so here. A `null` value
    | will not have any limits on the keys.
    |
    */
    'webauthn' => [
        'max_security_keys_per_user' => env('WEBAUTHN_MAX_SECURITY_KEYS', 5),

        'max_internal_keys_per_user' => env('WEBAUTHN_MAX_INTERNAL_KEYS'),
    ],

    'user_route_key' => 'id',

];
