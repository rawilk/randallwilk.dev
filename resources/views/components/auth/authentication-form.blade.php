<div class="mx-auto w-full lg:max-w-lg">
    <div>
        <a href="{{ route('home') }}" title="{{ __('Home') }}">
            <div>
                <x-logo class="h-12 w-auto" />
            </div>
        </a>

        @if ($title)
            <h2 class="mt-6 text-2xl leading-9 font-extrabold text-gray-900">
                {{ $title }}
            </h2>
        @endif

        @if ($subTitle)
            <p class="mt-2 text-sm leading-5 text-gray-600 max-w">
                {{ $subTitle }}
            </p>
        @endif

    </div>

    <div class="mt-8">
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
