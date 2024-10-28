<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

class DocHeaderFactory
{
    protected const array CONFIGS = [
        'laravel-app-key-rotator' => LaravelAppKeyRotatorDocHeader::class,
        'laravel-breadcrumbs' => LaravelBreadcrumbsDocHeader::class,
        'laravel-casters' => LaravelCastersDocHeader::class,
        'laravel-form-components' => LaravelFormComponentsDocHeader::class,
        'laravel-printing' => LaravelPrintingDocHeader::class,
        'laravel-settings' => LaravelSettingsDocHeader::class,
        'laravel-ups' => LaravelUPSDocHeader::class,
        'laravel-webauthn' => LaravelWebauthnDocHeader::class,
        'alpine-ripple' => AlpineRippleDocHeader::class,
        'profile-filament-plugin' => ProfileFilamentDocHeader::class,
        'vue-context' => VueContextDocHeader::class,
    ];

    public static function resolve(string $repository): ?string
    {
        return static::CONFIGS[$repository] ?? null;
    }
}
