@props([
    'title' => '',
])

<li {{ $attributes->class('group relative flex flex-col items-start') }}>
    <h3 class="text-base font-semibold tracking-tight text-slate-800">{{ $title }}</h3>
    <div class="relative mt-4 prose prose-sm max-w-none prose-p:mt-0 [&:not(:last-child)]:prose-p:mb-3 prose-a:text-slate-500 prose-a:no-underline prose-a:shadow-[inset_0_-2px_0_0_var(--tw-prose-background,#fff),inset_0_calc(-1*(var(--tw-prose-underline-size,4px)+2px))_0_0_var(--tw-prose-underline,theme(colors.slate.300))] hover:prose-a:[--tw-prose-underline-size:6px]">
        {{ $slot }}
    </div>
</li>
