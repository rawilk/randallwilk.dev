<div>
    <div class="py-4 space-y-4">
        {{-- top bar --}}
        @include('livewire.admin.users.index.partials.topbar')

        {{-- results --}}
        @include('livewire.admin.users.index.partials.results')
    </div>

    {{-- delete --}}
    @can(\App\Enums\PermissionEnum::USERS_DELETE->value)
        @include('livewire.admin.users.edit.partials.confirm-delete', ['user' => $deleting])
        @include('livewire.admin.users.index.partials.confirm-delete-bulk')
    @endcan

    {{-- import --}}
    @canany([\App\Enums\PermissionEnum::USERS_CREATE->value, \App\Enums\PermissionEnum::USERS_EDIT->value])
        <livewire:csv-importer
            :model="\App\Models\User\User::class"
            :import-class="\App\imports\Users\UsersImport::class"
            :columns-to-map="['first_name', 'last_name', 'email', 'password', 'timezone', 'roles', 'permissions']"
            :required-columns="['first_name', 'email']"
            :column-labels="['first_name' => 'First name', 'last_name' => 'Last name', 'email' => 'Email', 'password' => 'Password', 'timezone' => 'Timezone', 'roles' => 'Roles', 'permissions' => 'Permissions']"
            :guesses="[
                'first_name' => ['first_name', 'name'],
                'last_name' => ['last_name'],
                'email' => ['email', 'email_address'],
                'timezone' => ['timezone', 'time_zone'],
                'permissions' => ['permissions', 'abilities'],
                'roles' => ['roles'],
                'password' => ['password'],
            ]"
        />
    @endcanany
</div>
