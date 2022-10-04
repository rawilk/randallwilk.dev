<div @class(['wrap' => $wrap])>
    <div @class([
        'prose prose-slate max-w-none',
        'sm:prose-lg' => $largeText,
        // links
        'prose-a:text-slate-500',
        // link underline
        'prose-a:no-underline prose-a:shadow-[inset_0_-2px_0_0_var(--tw-prose-background,#fff),inset_0_calc(-1*(var(--tw-prose-underline-size,4px)+2px))_0_0_var(--tw-prose-underline,theme(colors.slate.300))] hover:prose-a:[--tw-prose-underline-size:6px]',
        // lists
        '[--tw-prose-bullets:theme(colors.slate.500)]',
        'prose-ul:sm:pl-10' => $indentLists,
        'counters prose-h3:text-base' => $headingsAsBullets,
    ])>
        <div {{ $attributes }}>
            {{ $slot }}
        </div>
    </div>
</div>
