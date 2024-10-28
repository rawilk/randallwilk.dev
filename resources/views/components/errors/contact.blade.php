@props([
    'title' => 'If you still have questions, please contact me so I can help you out.',
])

<div class="[&_a]:text-blue-600 hover:[&_a]:text-blue-500 hover:[&_a]:underline">
    <p class="text-lg !mb-3">
        {{ $title }}
    </p>

    <div class="text-lg">
        <a href="mailto:{{ config('randallwilk.contact.email') }}" class="block mb-1">
            {{ config('randallwilk.contact.email') }}
        </a>

        <a
            href="https://twitter.com/intent/tweet?text=Dear+@wilkrandall+..."
            target="_blank"
            rel="nofollow noopener"
        >
            @wilkrandall
        </a>
    </div>
</div>
