@env(['local', 'staging'])
    <div class="mt-6 rounded-lg bg-gray-100 dark:bg-gray-600 p-3 text-sm" data-test="dev-logins">
        <div class="text-lg text-gray-600 dark:text-white font-semibold mb-3">Development Logins</div>

        <div class="space-y-2">

            <x-login-link
                :email="config('randallwilk.dev_credentials.email')"
                label="Login as admin ({{ config('randallwilk.dev_credentials.email') }})"
                :redirect-url="\Filament\Pages\Dashboard::getUrl(panel: 'admin')"
                :user-attributes="[
                    'timezone' => config('randallwilk.timezone'),
                    'is_admin' => true,
                ]"
            />

        </div>
    </div>
@endenv
