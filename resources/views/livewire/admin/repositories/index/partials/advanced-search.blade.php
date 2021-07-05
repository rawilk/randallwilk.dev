<div>
    <div class="mt-4 lg:mt-0 mb-2">
        <x-button.link wire:click="toggleShowFilters" class="text-xs">
            {{ __('labels.show_advanced_search') }}
        </x-button.link>
    </div>

    <x-elements.advanced-search slide-out wire:model.defer="showFilters">
        @if ($showFilters)
            <div class="grid grid-cols-2 gap-4 w-full">
                <x-form-group label="{{ __('Type') }}">
                    <x-select wire:model="filters.type" id="filters_type">
                        <option value="">{{ __('Select option...') }}</option>
                        @foreach (\App\Models\Repository::TYPES as $value => $label)
                            <option value="{{ $value }}" wire:key="type-filter-{{ $loop->index }}">{{ $label }}</option>
                        @endforeach
                    </x-select>
                </x-form-group>

                <x-form-group label="{{ __('Visible') }}">
                    <x-select wire:model="filters.visible" id="filters_visible">
                        <option value="">{{ __('Select option...') }}</option>
                        <option value="0">{{ __('No') }}</option>
                        <option value="1">{{ __('Yes') }}</option>
                    </x-select>
                </x-form-group>

                <x-form-group label="{{ __('Marked New') }}">
                    <x-select wire:model="filters.new" id="filters_new">
                        <option value="">{{ __('Select option...') }}</option>
                        <option value="0">{{ __('No') }}</option>
                        <option value="1">{{ __('Yes') }}</option>
                    </x-select>
                </x-form-group>

                <x-form-group label="{{ __('Marked as Featured') }}">
                    <x-select wire:model="filters.highlighted" id="filters_highlighted">
                        <option value="">{{ __('Select option...') }}</option>
                        <option value="0">{{ __('No') }}</option>
                        <option value="1">{{ __('Yes') }}</option>
                    </x-select>
                </x-form-group>

                <x-form-group label="{{ __('Has Documentation') }}">
                    <x-select wire:model="filters.docs" id="filters_docs">
                        <option value="">{{ __('Select option...') }}</option>
                        <option value="0">{{ __('No') }}</option>
                        <option value="1">{{ __('Yes') }}</option>
                    </x-select>
                </x-form-group>

                <x-form-group label="{{ __('Has Blog Post') }}">
                    <x-select wire:model="filters.blogpost" id="filters_blogpost">
                        <option value="">{{ __('Select option...') }}</option>
                        <option value="0">{{ __('No') }}</option>
                        <option value="1">{{ __('Yes') }}</option>
                    </x-select>
                </x-form-group>

                <x-form-group label="{{ __('Sort By') }}">
                    <div class="flex">
                        <div class="flex-1">
                            <x-select wire:model="sortBy" id="filters_sort" class="rounded-r-none">
                                <option value="name">{{ __('Name') }}</option>
                                <option value="type">{{ __('Type') }}</option>
                                <option value="highlighted">{{ __('Featured') }}</option>
                                <option value="new">{{ __('New') }}</option>
                            </x-select>
                        </div>
                        <div>
                            <x-select wire:model="sortDirection" id="filters_sort_dir" class="rounded-l-none -mx-px">
                                <option value="asc">{{ __('Ascending') }}</option>
                                <option value="desc">{{ __('Descending') }}</option>
                            </x-select>
                        </div>
                    </div>
                </x-form-group>
            </div>
        @endif
    </x-elements.advanced-search>
</div>
