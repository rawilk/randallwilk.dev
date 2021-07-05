@props([
    'title' => '',
    'placement' => \App\View\Components\Elements\Tooltip::TOP,
    'triggers' => [\App\View\Components\Elements\Tooltip::TRIGGER_HOVER, \App\View\Components\Elements\Tooltip::TRIGGER_FOCUS],
])

<x-tooltip :title="$title" :placement="$placement" :triggers="$triggers">
    <span tabindex="-1"
          class="text-xs cursor-help text-blue-gray-500 hover:text-blue-gray-400"
    >
        [?]
    </span>
</x-tooltip>
