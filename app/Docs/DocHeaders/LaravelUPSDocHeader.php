<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

class LaravelUPSDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'CreateShipment.php',
            'composer.json',
        ];
    }

    public static function snippetLanguage(string $version): string
    {
        return 'php';
    }

    public static function snippet(string $version): string
    {
        return <<<'PHP'
        $response = (new ShipConfirm)
            ->withShipment($shipment)
            ->getDigest();

        return $response->shipment_identification_number;
        PHP;
    }
}
