<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        @can('auth-check', $userAuth->authorize('admin-groups-edit'))
            <a href="{{ route('admin.groups.edit', ['group' => $group]) }}" class="dropdown-item">{{ __('Edit') }}</a>
        @endcan

        @can('auth-check', $userAuth->authorize('admin-groups-permissions'))
            <a href="{{ route('admin.groups.member-permissions.edit', ['group' => $group]) }}" class="dropdown-item">{{ __('Edit Permissions') }}</a>
        @endcan

        @if ($group->is_removable == 'Y')
            @can('auth-check', $userAuth->authorize('admin-groups-delete'))
                <button
                    type="button"
                    class="dropdown-item"
                    data-toggle="modal"
                    data-target="#modalDelete"
                    data-link-delete="{{ route('admin.groups.destroy', ['group' => $group]) }}"
                    data-name="{{ $group->name }}"
                    @click="deleteItem"
                >{{ __('Delete') }}</button>
            @endcan
        @endif
    </div>
</div>