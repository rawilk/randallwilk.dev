<x-slide-over-form wire:model.defer="showEdit">
    <x-slot name="title">{{ __('Edit Repository') }}</x-slot>

    <div>
        @if ($editing)
            <div class="mb-4">
                <x-badge variant="blue" large>{{ $editing->name }}</x-badge>
            </div>

            <x-form wire:submit.prevent="save" id="edit-repo-form">
                <div class="py-6 space-y-6 sm:py-0">

                    {{-- type --}}
                    <x-form-group label="{{ __('Repository Type') }}" name="editing.type" inline>
                        <x-select wire:model.defer="editing.type"
                                  name="editing.type"
                                  required
                                  autofocus
                        >
                            <option value="" disabled>{{ __('Select a type...') }}</option>
                            @foreach (\App\Models\Repository::TYPES as $value => $label)
                                <option value="{{ $value }}" wire:key="repo-type-{{ $loop->index }}">{{ $label }}</option>
                            @endforeach
                        </x-select>
                    </x-form-group>

                    {{-- documentation url --}}
                    <x-form-group label="{{ __('Documentation URL') }}" name="editing.documentation_url" inline border>
                        <x-input wire:model.defer="editing.documentation_url"
                                 name="editing.documentation_url"
                                 maxlength="255"
                        />

                        <x-slot name="helpText">{{ __('Optional') }}</x-slot>
                    </x-form-group>

                    {{-- blogpost url --}}
                    <x-form-group label="{{ __('Blog Post URL') }}" name="editing.blogpost_url" inline border>
                        <x-input wire:model.defer="editing.blogpost_url"
                                 name="editing.blogpost_url"
                                 maxlength="255"
                        />

                        <x-slot name="helpText">{{ __('Optional') }}</x-slot>
                    </x-form-group>

                    {{-- visible --}}
                    <x-form-group label="{{ __('Visible on Frontend') }}" name="editing.visible" inline border is-checkbox-group>
                        <x-checkbox wire:model.defer="editing.visible"
                                    name="editing.visible"
                        />

                        <x-slot name="helpText">{{ __('Unchecking this will prevent this repository from showing up on the front-end of the site.') }}</x-slot>
                    </x-form-group>

                    {{-- new --}}
                    <x-form-group label="{{ __('New Repository') }}" name="editing.new" inline border is-checkbox-group>
                        <x-checkbox wire:model.defer="editing.new"
                                    name="editing.new"
                        />

                        <x-slot name="helpText">{{ __('Check to display a "new" badge by the repository on the front-end.') }}</x-slot>
                    </x-form-group>

                    {{-- highlighted --}}
                    <x-form-group label="{{ __('Featured') }}" name="editing.highlighted" inline border is-checkbox-group>
                        <x-checkbox wire:model.defer="editing.highlighted"
                                    name="editing.highlighted"
                        />

                        <x-slot name="helpText">{{ __('Check to show the repository as "featured" on the front-end.') }}</x-slot>
                    </x-form-group>

                </div>
            </x-form>
        @endif
    </div>

    <x-slot name="footer">
        <div class="flex justify-end space-x-4">
            <x-button wire:click="$set('showEdit', false)"
                      variant="white"
            >
                {{ __('Cancel') }}
            </x-button>

            <x-button wire:target="save" variant="primary" type="submit" form="edit-repo-form">
                <span>{{ __('Update') }}</span>

                <x-heroicon-s-check />
            </x-button>
        </div>
    </x-slot>
</x-slide-over-form>
