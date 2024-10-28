<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @php
        $record = $getRecord();
        $state = $getState();
        $isCircular = $isCircular();
        $isSquare = $isSquare();
        $height = $getHeight() ?? '8rem';
        $width = $getWidth() ?? (($isCircular || $isSquare) ? $height : null);

        $defaultImageUrl = $getDefaultImageUrl();
    @endphp

    <div
        {{
            $attributes
                ->merge($getExtraAttributes(), escape: false)
                ->class([
                    'fi-in-image flex items-center gap-x-2.5',
                ])
        }}
    >
        <div class="flex">
            <div class="relative">
                <img
                    src="{{ filled($state) ? $getImageUrl($state) : $defaultImageUrl }}"
                    {{
                        $getExtraImgAttributeBag()
                            ->class([
                                'max-w-none object-cover object-center',
                                'rounded-full' => $isCircular,
                            ])
                            ->style([
                                "height: {$height}" => $height,
                                "width: {$width}" => $width,
                            ])
                    }}
                />

                @can('update', $record)
                    <div class="absolute bottom-0 left-0 mb-2 ml-1">
                        {{ $avatarActions($record) }}
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-dynamic-component>
