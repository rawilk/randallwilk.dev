<?php

declare(strict_types=1);

use App\Models\WebauthnKey;

return [
    'models' => [
        /**
         * Authenticator App
         *
         * This model is responsible for storing a user's verified authenticator apps
         * for 2fa.
         */
        'authenticator_app' => Rawilk\ProfileFilament\Models\AuthenticatorApp::class,

        /**
         * Pending User Email
         *
         * This model is responsible for storing tokens for when a user wants to
         * change their email address.
         */
        'pending_user_email' => Rawilk\ProfileFilament\Models\PendingUserEmail::class,

        /**
         * Old User Email
         *
         * This model is responsible for storing a user's old email addresses, which
         * can be used to revert a change if it wasn't made by the user.
         */
        'old_user_email' => Rawilk\ProfileFilament\Models\OldUserEmail::class,

        /**
         * Webauthn Key
         *
         * This model is responsible for storing webauthn keys for a user, such
         * as hardware security keys or passkeys.
         */
        'webauthn_key' => WebauthnKey::class,
    ],
];
