---
title: Installation
sort: 3
---

## Installation

laravel-settings can be installed via composer:

```bash
composer require rawilk/laravel-settings:1.0
```

## Migrations
When using the `database` or `eloquent` drivers, you should publish the migration files. You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Rawilk\Settings\SettingsServiceProvider" --tag="migrations"
php artisan migrate
```

## Configuration
You can publish the configuration file with:

```bash
php artisan vendor:publish --provider="Rawilk\Settings\SettingsServiceProvider" --tag="config"
```

The default content of `config/settings.php`:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Settings Table
    |--------------------------------------------------------------------------
    |
    | Database table used to store settings in.
    |
    */
    'table' => 'settings',

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | If enabled, all settings are cached after accessing them.
    |
    */
    'cache' => true,

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | Specify a prefix to prepend to any setting key being cached.
    |
    */
    'cache_key_prefix' => 'settings.',

    /*
    |--------------------------------------------------------------------------
    | Encryption
    |--------------------------------------------------------------------------
    |
    | If enabled, all values are encrypted and decrypted.
    |
    */
    'encryption' => true,

    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    |
    | The driver to use to store and retrieve settings from. You are free
    | to add more drivers in the `drivers` configuration below.
    |
    */
    'driver' => env('SETTINGS_DRIVER', 'eloquent'),

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the driver information for each repository that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with this package. You are free to add more.
    |
    | Each driver you add must implement the \Rawilk\Settings\Contracts\Driver interface.
    |
    */
    'drivers' => [
        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CONNECTION', 'mysql'),
        ],
        'eloquent' => [
            'driver' => 'eloquent',

            /*
             * You can use any model you like for the setting, but it needs to implement
             * the \Rawilk\Settings\Contracts\Setting interface.
             */
            'model' => \Rawilk\Settings\Models\Setting::class,
        ],
    ],
];
```
