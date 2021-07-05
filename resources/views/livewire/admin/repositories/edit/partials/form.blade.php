<x-slide-over-form wire:model.defer="showEdit">
    <x-slot name="title">{{ __('Edit Repository') }}</x-slot>

    <x-form wire:submit.prevent="save" id="edit-repo-form">
        <div class="py-6 space-y-6 sm:py-0">

            {{-- type --}}
            <x-form-group label="{{ __('Repository Type') }}" name="type" inline>
                <x-select wire:model.defer="state.type"
                          name="type"
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
            <x-form-group label="{{ __('Documentation URL') }}" name="documentation_url" inline border>
                <x-input wire:model.defer="state.documentation_url"
                         name="documentation_url"
                         maxlength="255"
                />

                <x-slot name="helpText">{{ __('Optional') }}</x-slot>
            </x-form-group>

            {{-- blogpost url --}}
            <x-form-group label="{{ __('Blog Post URL') }}" name="blogpost_url" inline border>
                <x-input wire:model.defer="state.blogpost_url"
                         name="blogpost_url"
                         maxlength="255"
                />

                <x-slot name="helpText">{{ __('Optional') }}</x-slot>
            </x-form-group>

            {{-- visible --}}
            <x-form-group label="{{ __('Visible on Frontend') }}" name="visible" inline border is-checkbox-group>
                <x-checkbox wire:model.defer="state.visible"
                            name="visible"
                />

                <x-slot name="helpText">{{ __('Unchecking this will prevent this repository from showing up on the front-end of the site.') }}</x-slot>
            </x-form-group>

            {{-- new --}}
            <x-form-group label="{{ __('New Repository') }}" name="new" inline border is-checkbox-group>
                <x-checkbox wire:model.defer="state.new"
                            name="new"
                />

                <x-slot name="helpText">{{ __('Check to display a "new" badge by the repository on the front-end.') }}</x-slot>
            </x-form-group>

            {{-- highlighted --}}
            <x-form-group label="{{ __('Featured') }}" name="highlighted" inline border is-checkbox-group>
                <x-checkbox wire:model.defer="state.highlighted"
                            name="highlighted"
                />

                <x-slot name="helpText">{{ __('Check to show the repository as "featured" on the front-end.') }}</x-slot>
            </x-form-group>

        </div>
    </x-form>

    <x-slot name="footer">
        <div class="flex justify-end space-x-4">
            <x-button wire:click="$set('showEdit', false)"
                      variant="white"
            >
                {{ __('Cancel') }}
            </x-button>

            <x-button wire:target="save" variant="blue" type="submit" form="edit-repo-form">
                <span>{{ __('Update') }}</span>

                <x-heroicon-s-check />
            </x-button>
        </div>
    </x-slot>
</x-slide-over-form>
