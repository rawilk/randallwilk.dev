<?php

declare(strict_types=1);

namespace Tests\TestSupport\Browser;

use Closure;
use Pest\Browser\Playwright\Context;

final class VirtualWebauthn
{
    /**
     * @param  array<string, mixed>  $options
     * @return array<string, string>
     */
    public static function create(Context $context, string $rpId, array $options = []): array
    {
        $messages = self::execute($context, 'credentialsCreate', [
            ...$options,
            'rpId' => $rpId,
        ]);

        foreach ($messages as $message) {
            if (isset($message['result']['credential']) && is_array($message['result']['credential'])) {
                /** @var array<string, string> $credential */
                $credential = $message['result']['credential'];

                return $credential;
            }
        }

        return [];
    }

    public static function install(Context $context): void
    {
        self::execute($context, 'credentialsInstall');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function execute(Context $context, string $method, array $parameters = []): array
    {
        $callback = Closure::bind(
            fn (): array => iterator_to_array($this->sendMessage($method, $parameters)),
            $context,
            $context::class,
        );

        return $callback();
    }
}
