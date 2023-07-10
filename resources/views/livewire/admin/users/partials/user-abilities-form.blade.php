<div class="space-y-4 divide-y divide-slate-200">

    @if ($this->canAssignRoles)
        <x-form-group name="roles" :label="__('Roles')">
            <div x-data="{
                @if ($this->canAssignPermissions)
                    allRoles: {{ $roles->toJson() }},
                @endif
                {{-- roles assigned to the user --}}
                roles: @entangle('userRoles').defer,
                has(id) {
                    return this.roles.includes(id);
                },
            }"
                 x-init="
                $watch('roles', () => {
                    const rolePermissions = allRoles.filter(role => roles.includes(String(role.id)))
                        .map(role => role.mapped_permissions)
                        .flat();

                    $dispatch('roles-updated', Array.from(new Set(rolePermissions)));
                });
            "
                 id="roles-container"
                 class="bg-white rounded-md -space-y-px"
            >
                @foreach ($roles as $role)
                    <div wire:key="role-{{ $role->id }}"
                         class="relative border first:rounded-tl-md first:rounded-tr-md last:rounded-bl-md last:rounded-br-md"
                         x-bind:class="{ 'bg-blue-50 border-blue-200 z-10': has('{{ $role->id }}'), 'border-slate-200': ! has('{{ $role->id }}') }"
                    >
                        <label class="flex p-4 cursor-pointer">
                            <div class="flex items-center h-5">
                                <x-checkbox
                                    x-model="roles"
                                    :value="$role->id"
                                    id="role-option-{{ $role->id }}"
                                    name="roles[]"
                                />
                            </div>

                            <div class="ml-3 flex flex-col">
                                <span class="block text-sm font-medium"
                                      x-bind:class="{ 'text-blue-900': has('{{ $role->id }}'), 'text-slate-900': ! has('{{ $role->id }}') }"
                                >
                                    {{ $role->name }}
                                </span>

                                @if ($role->description)
                                    <span class="text-sm block"
                                          x-bind:class="{ 'text-blue-700': has('{{ $role->id }}'), 'text-slate-500': ! has('{{ $role->id }}') }"
                                    >
                                        {{ $role->description }}
                                    </span>
                                @endif
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </x-form-group>
    @endif

    @if ($this->canAssignPermissions)
        <div @class(['pt-4' => $this->canAssignRoles]) x-data="{ showPermissions: false }">
            <x-form-group name="permissions" :label="__('Permissions')">

                <p class="text-sm text-gray-500 leading-5 mt-2 mb-4">
                    {{ __('You may grant this user additional abilities that are not granted through their assigned roles.') }}
                </p>

                <div class="mb-4">
                    <x-button x-on:click="showPermissions = ! showPermissions"
                              x-text="showPermissions ? '{{ __('Hide Permissions') }}' : '{{ __('Show Permissions') }}'"
                              color="slate"
                              variant="outlined"
                              size="sm"
                    >
                    </x-button>
                </div>

                <div x-show="showPermissions"
                     x-collapse
                     x-data="{
                        permissions: @entangle('userPermissions').defer,
                        selected: @entangle('currentlySelectedPermissions').defer,
                        rolePermissions: @entangle('rolePermissions').defer,
                        has(id, directOnly = false) {
                            if (this.permissions.includes(id)) {
                                return true;
                            }

                            return directOnly ? false : this.hasViaRoles(id);
                        },
                        hasViaRoles(id) {
                            return this.rolePermissions.includes(id);
                        },
                        selectAllIn(el) {
                            el && el.querySelectorAll('[type=checkbox]').forEach(el => {
                                if (! el.checked && ! this.hasViaRoles(el.value)) {
                                    this.selected.push(el.value);
                                }
                            });
                        },
                        removeAllIn(el) {
                            el && el.querySelectorAll('[type=checkbox]').forEach(el => {
                                if (el.checked && ! this.hasViaRoles(el.value)) {
                                    this.selected.splice(
                                        this.selected.indexOf(el.value), 1
                                    );
                                }
                            });
                        },
                     }"
                     x-on:roles-updated.window="event => { rolePermissions = event.detail }"
                     x-init="
                        $watch('rolePermissions', value => {
                            selected = [...value, ...permissions];
                        });

                        $watch('selected', value => {
                            let set = new Set(rolePermissions);

                            permissions = [...value].filter(id => ! set.has(id));
                        });
                     "
                     class="space-y-4 pt-2"
                >
                    <div class="sm:flex sm:justify-between sm:space-x-2">
                        {{-- select all --}}
                        <div class="text-xs">
                            <span>{{ __('base::messages.labels.form.make_selection') }}</span>

                            <x-blade::button.link x-on:click="selectAllIn($root)">
                                {{ __('base::messages.labels.form.select_all') }}
                            </x-blade::button.link>
                            <span>/</span>
                            <x-blade::button.link x-on:click="removeAllIn($root)">
                                {{ __('base::messages.labels.form.select_none') }}
                            </x-blade::button.link>
                        </div>

                        {{-- collapse/expand all --}}
                        <div class="text-xs mt-2 sm:mt-0">
                            <x-blade::button.link x-on:click="$dispatch('perm-collapse')">
                                {{ __('base::messages.labels.form.collapse_all') }}
                            </x-blade::button.link>
                            <span>/</span>
                            <x-blade::button.link x-on:click="$dispatch('perm-expand')">
                                {{ __('base::messages.labels.form.expand_all') }}
                            </x-blade::button.link>
                        </div>
                    </div>

                    @foreach ($this->permissionModel::groupedPermissions() as $groupName => $groupedPermissions)
                        <div wire:key="perm-group-{{ $loop->index }}"
                             x-data="{
                                open: true,
                                groupPermissionIds: {{ $groupedPermissions->pluck('id')->toJson() }},
                                allSelected() {
                                    return this.groupPermissionIds.every(id => this.has(String(id)));
                                },
                                someSelected() {
                                    return this.groupPermissionIds.some(id => this.has(String(id)));
                                },
                                status() {
                                    if (this.allSelected()) {
                                        return 'all';
                                    }

                                    if (this.someSelected()) {
                                        return 'some';
                                    }

                                    return 'none';
                                },
                             }"
                             x-on:perm-collapse.window="open = false"
                             x-on:perm-expand.window="open = true"
                             id="perm-group-{{ $loop->index }}"
                             x-cloak
                        >
                            <div class="bg-slate-50 rounded-lg">
                                <div class="px-4 pb-5 sm:pb-6 sm:px-6"
                                     x-bind:class="{ 'pb-5 sm:pb-6': open, 'pb-3': ! open }"
                                >
                                    <div x-on:click="open = ! open"
                                         class="flex items-center justify-between space-x-1 cursor-pointer group pt-5 sm:pt-6"
                                         role="button"
                                    >
                                        <h4 class="capitalize text-sm leading-6 text-slate-900 font-medium group-hover:text-slate-600 transition-colors flex items-center space-x-1">
                                            <span>{{ $groupName }}</span>

                                            <x-heroicon-s-check-circle
                                                class="w-5 h-5 text-green-500"
                                                x-show="status() === 'all'"
                                            />

                                            <x-heroicon-o-minus-circle
                                                class="w-5 h-5 text-yellow-500"
                                                x-show="status() === 'some'"
                                            />
                                        </h4>

                                        <div class="p-1 h-6 w-6 rounded-full group-hover:bg-slate-400 transition-all flex justify-center items-center">
                                            <x-css-chevron-down
                                                class="h-5 w-5 text-slate-600 group-hover:text-slate-200 transition-all"
                                                x-bind:class="{ 'rotate-[270deg]': ! open }"
                                            />
                                        </div>
                                    </div>

                                    <div class="pt-3" x-show="open" x-collapse>
                                        {{-- select all in group --}}
                                        <div class="text-xs">
                                            <span>{{ __('base::messages.labels.form.make_selection') }}</span>

                                            <x-blade::button.link x-on:click="selectAllIn($root)">
                                                {{ __('base::messages.labels.form.select_all') }}
                                            </x-blade::button.link>
                                            <span>/</span>
                                            <x-blade::button.link x-on:click="removeAllIn($root)">
                                                {{ __('base::messages.labels.form.select_none') }}
                                            </x-blade::button.link>
                                        </div>

                                        <div class="mt-2 -space-y-px bg-white rounded-md">
                                            @foreach ($groupedPermissions as $permission)
                                                <div wire:key="perm-{{ $permission->id }}"
                                                     class="relative border first:rounded-tl-md first:rounded-tr-md last:rounded-bl-md last:rounded-br-md"
                                                     x-bind:class="{
                                                        'bg-blue-50 border-blue-200 z-10': has('{{ $permission->id }}', true),
                                                        'bg-green-50 border-green-200 z-10 opacity-75': hasViaRoles('{{ $permission->id }}'),
                                                        'border-slate-200': ! has('{{ $permission->id }}')
                                                     }"
                                                >
                                                    <label class="flex p-4"
                                                           x-bind:class="{
                                                               'cursor-pointer': ! hasViaRoles('{{ $permission->id }}'),
                                                               'cursor-not-allowed': hasViaRoles('{{ $permission->id }}')
                                                           }"
                                                           x-bind:title="hasViaRoles('{{ $permission->id }}') ? '{{ __('User is granted this permission through one of the selected roles.') }}' : null"
                                                    >
                                                        <div class="flex items-center h-5">
                                                            <x-checkbox
                                                                x-model="selected"
                                                                value="{{ $permission->id }}"
                                                                id="perm-option-{{ $permission->id }}"
                                                                name="permissions[]"
                                                                x-bind:disabled="hasViaRoles('{{ $permission->id }}')"
                                                            />
                                                        </div>

                                                        <div class="ml-3 flex flex-col">
                                                            <span class="block text-sm font-medium"
                                                                  x-bind:class="{
                                                                      'text-blue-900': has('{{ $permission->id }}', true),
                                                                      'text-green-900': hasViaRoles('{{ $permission->id }}'),
                                                                      'text-slate-900': ! has('{{ $permission->id }}') && ! hasViaRoles('{{ $permission->id }}'),
                                                                  }"
                                                            >
                                                                {{ $permission->name }}
                                                            </span>

                                                            @if ($permission->description)
                                                                <span class="text-sm block"
                                                                      x-bind:class="{
                                                                          'text-blue-700': has('{{ $permission->id }}', true),
                                                                          'text-green-700': hasViaRoles('{{ $permission->id }}'),
                                                                          'text-slate-500': ! has('{{ $permission->id }}') && ! hasViaRoles('{{ $permission->id }}'),
                                                                      }"
                                                                >
                                                                    {{ $permission->description }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-form-group>
        </div>
    @endif

</div>
