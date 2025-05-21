@props([
    'text',
    'url',
    'urlDisplay',
])

@lang(
    'mail.action_link_help',
    [
        'actionText' => $text,
    ]
) <span class="break-all">[{{ $urlDisplay }}]({{ $url }})</span>
