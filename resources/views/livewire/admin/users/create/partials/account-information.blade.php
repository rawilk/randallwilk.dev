<div>
    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-blue-gray-200"></div>
        </div>
    </div>

    <x-two-column-card-form title="{{ __('users.labels.account_info_title') }}" class="mt-10 sm:mt-0">
        <x-slot name="description">{{ __('users.labels.account_info_sub_title') }}</x-slot>

        <x-card>
            <div class="space-y-6">

                {{-- password --}}
                <x-form-group label="{{ __('users.form.labels.password') }}" name="password" inline>
                    <x-password wire:model.defer="password"
                                name="password"
                                max-width=" sm:max-w-lg"
                                placeholder="{{ __('labels.forms.optional') }}"
                    />

                    <x-slot name="helpText">
                        {{ __('users.form.labels.new_user_password_help') }}
                    </x-slot>
                </x-form-group>

                {{-- is admin --}}
                @if (auth()->user()->is_admin)
                    <x-form-group name="is_admin" label="{{ __('users.form.labels.is_admin') }}" inline border is-checkbox-group>
                        <x-checkbox wire:model.defer="user.is_admin"
                                    name="is_admin"
                        />

                        <x-slot name="helpText">{{ __('users.form.labels.is_admin_help') }}</x-slot>
                    </x-form-group>
                @endif

            </div>
        </x-card>
    </x-two-column-card-form>
</div>
