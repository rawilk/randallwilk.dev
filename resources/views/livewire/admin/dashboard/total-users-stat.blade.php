<x-elements.dashboard-stat
    url="{{ route('admin.users') }}"
    url-description="users"
    icon="heroicon-o-users"
    title="{{ __('Total Users') }}"
    :count="$this->totalUsers"
/>
