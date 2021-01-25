<x-elements.dashboard-stat
    url="{{ $this->url }}"
    url-description="open github issues"
    icon="css-git-branch"
    title="{{ __('Open Github Issues') }}"
    :count="$this->issuesCount"
/>
