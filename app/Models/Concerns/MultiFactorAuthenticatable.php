<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Rawilk\ProfileFilament\Auth\Multifactor\App\Concerns\InteractsWithAppAuthentication;
use Rawilk\ProfileFilament\Auth\Multifactor\Concerns\InteractsWithMultiFactorAuthentication;
use Rawilk\ProfileFilament\Auth\Multifactor\Recovery\Concerns\InteractsWithAuthenticationRecovery;
use Rawilk\ProfileFilament\Auth\Multifactor\Webauthn\Concerns\InteractsWithWebauthn;

trait MultiFactorAuthenticatable
{
    use InteractsWithAppAuthentication;
    use InteractsWithAuthenticationRecovery;
    use InteractsWithMultiFactorAuthentication;
    use InteractsWithWebauthn;
}
