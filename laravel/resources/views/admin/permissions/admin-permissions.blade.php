<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ $group->name }}</x-slot>

    @if (session('success'))
        <x-admin.components.alert>
            {{ session('success') }}
        </x-admin.components.alert>
    @endif

    @if (session('error'))
        <x-admin.components.alert type="danger">
            {{ session('error') }}
        </x-admin.components.alert>
    @endif

    <div x-data="permission">
        <form action="{{ route('admin.groups.admin-permissions.update', ['group' => $group]) }}" method="POST">
            @csrf
            @method('PUT')
    
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Permissions') }}</div>
                </div>
                <div class="card-body">
                    <div>
                        <x-admin.forms.radio name="is_restricted" value="Y" :label="__('Restrict access')" x-model="isRestricted" />
                        <x-admin.forms.radio name="is_restricted" value="N" :label="__('Unrestricted')" x-model="isRestricted" />
                    </div>
                </div>
            </div>
    
            <div class="row" x-show="isRestricted == 'Y'" x-cloak>
                @foreach (config('permissions.admin') as $subPermissionName => $subPermissions)
                    <div class="col-12 col-lg-6 d-flex">
                        <div class="card flex-grow-1">
                            <div class="card-header">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="cat[]" value="{{ $subPermissionName }}" id="checkboxCat{{ Str::studly($subPermissionName) }}" @change="categoryChecked" @checked($permissionsCategory[$subPermissionName] || $group->is_admin_restricted == 'N')>
                                    <label for="checkboxCat{{ Str::studly($subPermissionName) }}" class="custom-control-label">{{ $subPermissionName }}</label>
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach ($subPermissions as $permissionName => $permissionCode)
                                    <div>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{ $permissionCode }}" id="checkbox{{ Str::studly($permissionCode) }}" data-parent="{{ $subPermissionName }}" @change="permissionChecked" @checked(in_array($permissionCode, $permissions) || $group->is_admin_restricted == 'N')>
                                            <label for="checkbox{{ Str::studly($permissionCode) }}" class="custom-control-label">{{ $permissionName }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
    
            <div>
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </form>
    </div>

    @push('scriptsBottom')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('permission', () => ({
                    isRestricted: '{{ $group->is_admin_restricted }}',
                    categoryChecked(e) {
                        const categoryName = e.target.getAttribute('value');
                        const checkboxes = document.querySelectorAll('[data-parent="' + categoryName + '"]');
                        checkboxes.forEach(checkbox => {
                            if (e.target.checked) {
                                checkbox.checked = true;
                            }
                            else {
                                checkbox.checked = false;
                            }
                        });
                    },
                    permissionChecked(e) {
                        const categoryName = e.target.dataset.parent;
                        const parentCheckbox = document.querySelector('[value="' + categoryName + '"]');
                        const siblings = document.querySelectorAll('[data-parent="' + categoryName + '"]').length;
                        const siblingsChecked = document.querySelectorAll('[data-parent="' + categoryName + '"]:checked').length;
                        if (siblings === siblingsChecked) {
                            parentCheckbox.checked = true;
                        }
                        else {
                            parentCheckbox.checked = false;
                        }
                    },
                }));
            });
        </script>
    @endpush
</x-admin.layouts.app>