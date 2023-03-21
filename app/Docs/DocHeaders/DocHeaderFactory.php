<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

final class DocHeaderFactory
{
    private const CONFIGS = [
        'laravel-app-key-rotator' => LaravelAppKeyRotatorDocHeader::class,
        'laravel-breadcrumbs' => LaravelBreadcrumbsDocHeader::class,
        'laravel-casters' => LaravelCastersDocHeader::class,
        'laravel-form-components' => LaravelFormComponentsDocHeader::class,
        'laravel-printing' => LaravelPrintingDocHeader::class,
        'laravel-settings' => LaravelSettingsDocHeader::class,
        'laravel-ups' => LaravelUPSDocHeader::class,
        'laravel-webauthn' => LaravelWebauthnDocHeader::class,
        'alpine-ripple' => AlpineRippleDocHeader::class,
        'vue-context' => VueContextDocHeader::class,
    ];

    public static function resolve(string $repository): ?string
    {
        return self::CONFIGS[$repository] ?? null;
    }
}
