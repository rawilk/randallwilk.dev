<x-modal :id="$id" :max-width="$maxWidth" :show-close="$showClose" {{ $attributes }}>
    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
            @if ($showIcon)
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <x-heroicon-o-exclamation class="h-6 w-6 text-red-600" />
                </div>
            @endif

            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full @if ($showIcon) sm:ml-4 @endif">
                <h3 class="modal-title">
                    {{ $title ?? '' }}
                </h3>

                <div class="mt-2 text-left modal-content">
                    {{ $content ?? '' }}
                </div>
            </div>
        </div>
    </div>

    <footer class="modal-footer">
        {{ $footer ?? '' }}
    </footer>
</x-modal>
