<?php

declare(strict_types=1);

namespace Tests\TestSupport\Actions;

use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Actions\ConfigureCeremonyStepManagerFactoryAction;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;

class ConfigureWebauthnCeremonyForBrowserTestAction extends ConfigureCeremonyStepManagerFactoryAction
{
    public function __invoke(): CeremonyStepManagerFactory
    {
        $factory = new CeremonyStepManagerFactory;
        $factory->setAllowedOrigins([config('app.url')]);

        return $factory;
    }
}
