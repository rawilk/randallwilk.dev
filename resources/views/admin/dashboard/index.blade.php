<x-admin-app title="Dashboard" :show-title="false">
    <div class="md:px-4 lg:px-0">
        <dl class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <livewire:admin.dashboard.total-users-stat />

            <livewire:admin.dashboard.open-issues-stat />
        </dl>
    </div>
</x-admin-app>
