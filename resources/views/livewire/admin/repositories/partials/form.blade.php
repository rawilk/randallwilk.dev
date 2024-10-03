<x-slide-over-form wire:model.defer="showEdit">
    <x-slot:title>{{ __('repos.edit.title') }}</x-slot:title>

    <div>
        @if ($repository)
            <div class="mb-4">
                <x-badge variant="blue" large>{{ $repository->name }}</x-badge>
            </div>

            <x-form wire:submit.prevent="save" id="edit-repo-form">
                <div class="py-6 sm:py-0">

                    {{-- type --}}
                    <x-form-group :label="__('repos.labels.form.type')" name="type" inline>
                        <x-select
                                wire:model.defer="state.type"
                                name="type"
                                required
                                focus
                        >
                            <option value="" @disabled($repository->type)>{{ __('Select a type...') }}</option>
                            @foreach (\App\Enums\RepositoryType::cases() as $case)
                                <option value="{{ $case->value }}">{{ $case->label() }}</option>
                            @endforeach
                        </x-select>
                    </x-form-group>

                    {{-- scoped name --}}
                    <x-form-group :label="__('repos.labels.scoped_name')" name="scoped_name" inline>
                        <x-input
                                wire:model.defer="state.scoped_name"
                                name="scoped_name"
                                maxlength="255"
                        />

                        <x-slot:help-text>{{ __('repos.labels.scoped_name_help') }}</x-slot:help-text>
                    </x-form-group>

                    {{-- docs url --}}
                    <x-form-group :label="__('repos.labels.form.documentation_url')" name="documentation_url" inline
                                  optional>
                        <x-input
                                wire:model.defer="state.documentation_url"
                                name="documentation_url"
                                maxlength="255"
                        />
                    </x-form-group>

                    {{-- blogpost url --}}
                    <x-form-group :label="__('repos.labels.form.blogpost_url')" name="blogpost_url" inline optional>
                        <x-input
                                wire:model.defer="state.blogpost_url"
                                name="blogpost_url"
                                maxlength="255"
                        />
                    </x-form-group>

                    {{-- visible --}}
                    @isset($state['visible'])
                        <x-form-group :label="__('repos.labels.form.visible')" name="visible" inline is-checkbox-group>
                            <x-switch-toggle short wire:model.defer="state.visible" name="visible" />

                            <x-slot:helpText>{{ __('repos.labels.form.visible_help') }}</x-slot:helpText>
                        </x-form-group>
                    @endisset

                    {{-- new --}}
                    @isset($state['new'])
                        <x-form-group :label="__('repos.labels.form.new')" name="new" inline is-checkbox-group>
                            <x-switch-toggle short wire:model.defer="state.new" name="new" />

                            <x-slot:helpText>{{ __('repos.labels.form.new_help') }}</x-slot:helpText>
                        </x-form-group>
                    @endisset

                    {{-- featured --}}
                    @isset($state['highlighted'])
                        <x-form-group :label="__('repos.labels.form.featured')" name="highlighted" inline
                                      is-checkbox-group>
                            <x-switch-toggle short wire:model.defer="state.highlighted" name="highlighted" />

                            <x-slot:helpText>{{ __('repos.labels.form.featured_help') }}</x-slot:helpText>
                        </x-form-group>
                    @endisset

                </div>
            </x-form>
        @endif
    </div>

    <x-slot:footer>
        <div class="flex flex-row-reverse justify-start space-x-4 space-x-reverse">
            <x-button wire:target="save" color="blue" type="submit" form="edit-repo-form">
                {{ __('base::messages.update_button') }}
            </x-button>

            <x-button wire:click="$set('showEdit', false)" color="slate" variant="text">
                {{ __('base::messages.cancel_button') }}
            </x-button>
        </div>
    </x-slot:footer>
</x-slide-over-form>
